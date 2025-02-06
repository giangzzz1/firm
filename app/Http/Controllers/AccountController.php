<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class AccountController extends Controller
{
    public function register()
    {
        return view('account.sigup');
    }

    public function register_(Request $request)
    {
        $user = $request->validate([
            'email' => ['required', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,4}$/', 'unique:users,email'],
            'password' => 'required|string|min:6|confirmed', // Use 'confirmed' for password confirmation
            'referral_code' => 'nullable|exists:users,referral_code'
        ]);
    
        try {
            $user['password'] = Hash::make($request->input('password'));
            $user['role'] = $request->filled('role') ? $request->input('role') : 0;
            $user['referral_code'] = Str::random(10); // Tạo mã giới thiệu ngẫu nhiên


            $user = User::query()->create($user);

            if ($request->referral_code) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if ($referrer) {
                    $referrer->increment('point', 200); // Tăng điểm của người giới thiệu
                }
            }
            Auth::login($user);
            $request->session()->regenerate();
    
            return redirect('/')->with('success', 'Đăng kí tài khoản thành công');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    

    public function login()
    {
        return view('account.sigin');
    }

    public function login_(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
    
            /** @var User $user */
            $user = Auth::user();
    
            // Kiểm tra nếu tài khoản của người dùng đang bị khóa
            if ($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ])->onlyInput('email');
            }
    
            // Chuyển hướng với thông báo thành công
            return redirect('/')->with('success', 'Đăng nhập thành công');
        }
    
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }
    
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
    
        return redirect('/')->with('success', 'Đăng xuất tài khoản thành công');
    }
    
    


    public function rspassword()
    {
        return view('account.forgot');
    }

    public function rspassword_(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Thành công, vui lòng mở hòm thư trong địa chỉ email đã nhập'])
            : back()->withErrors(['errors' => 'Thất bại, không tìm thấy địa chỉ email này']);
    }

    public function updatepassword($token)
    {
        $email = request()->query('email');
        return view('account.resetpass', ['token' => $token, 'email' => $email]);
    }

    public function updatepassword_(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Cập nhật mật khẩu thành công, xin mời đăng nhập');
        } else {
            return back()->withErrors(['errors' => $status]);
        }
    }

    public function verify(Request $request)
    {
            /**
             * @var User $user
             */
        $user = Auth::user();

        // Kiểm tra xem email đã được xác thực chưa
        if ($user->email_verified_at != null) {
            return redirect()->back()->with('success', 'Email của bạn đã được xác thực');
        }
    
        // Gửi email xác minh
        $user->sendEmailVerificationNotification();
    

        return redirect()->back()->with('success', 'Email xác minh đã được gửi tới hòm thư của bạn');
    }

  public function verifydone(Request $request, $id, $hash)
{

    $user = User::findOrFail($id);

    // Kiểm tra mã hash với email đã được băm
    if (! hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
        return redirect()->route('home')->withErrors(['email' => 'Invalid verification link.']);
    }

    // Xác thực email
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('edit')->with('success', 'Xác minh email thành công');
}

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function user()
    {
        return view('LayoutUser.dashboard');
    }

    public function changepass()
    {
        return view('LayoutUser.changepass');
    }
    public function changepass_(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Yêu cầu phải xác nhận mật khẩu mới
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại có khớp không
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('LayoutUser.update', compact('user'));
    }

    public function update(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'birth_day' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->fullname = $request->input('fullname');
        $user->birth_day = $request->input('birth_day');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->address = $request->input('address');

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('UserAvatar', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $revenueData = OrderDetail::select('tickets.name as ticket_name', DB::raw('SUM(order_details.total) as total_revenue'))->join('tickets', 'order_details.ticket_id', '=', 'tickets.id')->groupBy('order_details.ticket_id', 'tickets.name')->orderBy('total_revenue', 'desc')->get();

        // Số lượng vé bán ra theo ticket_id cùng với tên của vé
        $quantityData = OrderDetail::select('tickets.name as ticket_name', DB::raw('SUM(order_details.quantity) as total_quantity'))->join('tickets', 'order_details.ticket_id', '=', 'tickets.id')->groupBy('order_details.ticket_id', 'tickets.name')->orderBy('total_quantity', 'desc')->get();

        return view('admin.dashboard', compact('revenueData', 'quantityData'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('admin.update', compact('user'));
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
            $avatarPath = $request->file('avatar')->store('AdminAvatar', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->back()->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    public function changepass()
    {
        return view('admin.changepass');
    }

    public function changepass_(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}

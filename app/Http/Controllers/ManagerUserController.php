<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class ManagerUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if (!$request->has('role')) {
            $query->whereIn('role', [0, 1]);
        } else {
            $role = $request->get('role');
            $query->where('role', $role);
        }

        $data = $query->latest()->paginate(5);

        return view('managers.index', compact('data'));
    }


    public function update($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = !$user->is_active; // Chuyển đổi trạng thái
            $user->save();

            $message = $user->is_active ? 'Mở khóa tài khoản thành công.' : 'Khóa tài khoản thành công.';
            return back()->with('success', $message);
        } catch (Throwable $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user) {
            $user->delete();
            return redirect()->route('managers.index')->with('success', 'Tài khoản đã được xóa thành công.');
        }

        return back()->with('error', 'Không tìm thấy tài khoản.');
    }
}


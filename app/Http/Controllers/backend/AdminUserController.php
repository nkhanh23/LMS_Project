<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminUserService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected $userService;

    public function __construct(AdminUserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $all_users = $this->userService->getUsers($search, null, 10);
        return view('backend.admin.user.index', compact('all_users'));
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            $user->status  = $request->status;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        }
        return response()->json(['success' => false, 'message' => 'Không tìm thấy giảng viên']);
    }

    public function userActive(Request $request)
    {
        $active_user = User::where('status', 1)->where('role', 'user')->latest()->get();
        return view('backend.admin.user.active', compact('active_user'));
    }
}

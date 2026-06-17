<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\AdminUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function accountDeletionRequests()
    {
        $requests = UserSetting::with(['user.orders', 'user.enrollments'])
            ->whereNotNull('account_deletion_requested_at')
            ->latest('account_deletion_requested_at')
            ->paginate(10);

        return view('backend.admin.user.account-deletion-requests', compact('requests'));
    }

    public function approveAccountDeletion(UserSetting $userSetting)
    {
        abort_if(is_null($userSetting->account_deletion_requested_at), 404);

        DB::transaction(function () use ($userSetting) {
            $user = $userSetting->user()->lockForUpdate()->firstOrFail();

            $user->update([
                'name' => 'Deleted User #' . $user->id,
                'email' => 'deleted_user_' . $user->id . '_' . time() . '@deleted.stacklearn.local',
                'phone' => null,
                'address' => null,
                'photo' => null,
                'status' => 0,
                'email_verified_at' => null,
            ]);

            $userSetting->update([
                'account_deletion_requested_at' => null,
                'account_deletion_reason' => null,
            ]);
        });

        return redirect()
            ->route('admin.user.account-deletion.index')
            ->with('success', 'Đã duyệt yêu cầu và vô hiệu hóa tài khoản.');
    }

    public function rejectAccountDeletion(UserSetting $userSetting)
    {
        abort_if(is_null($userSetting->account_deletion_requested_at), 404);

        $userSetting->update([
            'account_deletion_requested_at' => null,
            'account_deletion_reason' => null,
        ]);

        return redirect()
            ->route('admin.user.account-deletion.index')
            ->with('success', 'Đã từ chối yêu cầu xóa tài khoản.');
    }
}

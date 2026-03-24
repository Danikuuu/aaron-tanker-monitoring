<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Requests\Admin\CreateStaffRequest;
use App\Services\Admin\StaffManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffManagementController extends Controller
{
    public function __construct(
        protected StaffManagementService $service
    ) {}

    /**
     * List all staff with pagination.
     */
    public function index(Request $request)
    {
        $staff = $this->service->getPaginatedStaff(10);

        return view('super_admin.staff-management', compact('staff'));
    }

    /**
     * Create a new staff member and log the action.
     */
    public function store(CreateStaffRequest $request)
    {
        $user = $this->service->createUser(
            firstName: $request->first_name,
            lastName:  $request->last_name,
            email:     $request->email,
            password:  $request->password,
            role:      $request->role,
        );

        AuditLog::record('Staff Created', "Super Admin manually created a {$user->role} account: {$user->email}", $user);

        return redirect()->route('super_admin.staff-management')
            ->with('success', ucfirst($request->role) . ' account created successfully.');
    }

    /**
     * Approve a pending staff member.
     */
    public function approve(int $id)
    {
        $this->service->approve($id);

        return back()->with('success', 'User approved successfully.');
    }

    /**
     * Block a staff member and immediately invalidate all their sessions.
     */
    public function block(int $id)
    {
        $this->service->block($id);
        $this->invalidateUserSessions($id);

        AuditLog::record('Staff Blocked', "Staff account ID:{$id} was blocked.", User::find($id));

        return back()->with('success', 'User blocked and logged out successfully.');
    }

    /**
     * Unblock a staff member.
     */
    public function unblock(int $id)
    {
        $this->service->unblock($id);

        return back()->with('success', 'User unblocked successfully.');
    }

    /**
     * Delete a staff member and immediately invalidate all their sessions.
     */
    public function delete(int $id)
    {
        $user = User::find($id);
        $this->invalidateUserSessions($id);
        $this->service->delete($id);

        AuditLog::record('Staff Deleted', "Staff account {$user?->email} was permanently deleted.", $user);

        return back()->with('success', 'User deleted and logged out successfully.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Wipe all active sessions for a given user ID.
     *
     * Works with the default Laravel database session driver.
     * If you use file-based sessions, the middleware will handle logout
     * on the user's next request instead.
     */
    private function invalidateUserSessions(int $userId): void
    {
        // Database session driver: delete all session rows for this user
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $userId)
                ->delete();
        }
        // File/cookie/other drivers: the CheckUserStatus middleware handles
        // logout on the user's very next request automatically.
    }
}
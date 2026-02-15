<?php

namespace App\Services\Auth;

use App\Mail\StaffApproved;
use App\Mail\StaffBlocked;
use App\Mail\StaffDeleted;
use App\Mail\StaffUnBlocked;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ApprovalService
{
    public function approve(User $admin, int $staffId): User
    {
        $staff = $this->findStaff($staffId);

        if ($staff->isApproved()) {
            throw ValidationException::withMessages([
                'approval' => 'This user is already approved.',
            ]);
        }

        $staff->update([
            'status'      => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        Mail::to($staff->email)->send(new StaffApproved($staff));
        AuditLog::record('staff_approved', "Admin approved staff: {$staff->email}", $staff);

        return $staff->fresh();
    }

    public function block(int $staffId): User
    {
        $staff = $this->findStaff($staffId);

        if ($staff->isBlocked()) {
            throw ValidationException::withMessages([
                'approval' => 'This user is already blocked.',
            ]);
        }

        $staff->update(['status' => 'blocked']);

        Mail::to($staff->email)->send(new StaffBlocked($staff));
        AuditLog::record('staff_blocked', "Admin blocked staff: {$staff->email}", $staff);

        return $staff->fresh();
    }

    public function unblock(int $staffId): User
    {
        $staff = $this->findStaff($staffId);

        if (!$staff->isBlocked()) {
            throw ValidationException::withMessages([
                'approval' => 'This user is not blocked.',
            ]);
        }

        $staff->update([
            'status'      => 'pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);

        Mail::to($staff->email)->send(new StaffUnblocked($staff));
        AuditLog::record('staff_unblocked', "Admin unblocked staff: {$staff->email}", $staff);

        return $staff->fresh();
    }

    public function delete(int $staffId): void
    {
        $staff = $this->findStaff($staffId);

        if (!$staff->isBlocked()) {
            throw ValidationException::withMessages([
                'approval' => 'Only blocked staff members can be deleted.',
            ]);
        }

        // Send email before deleting so the record still exists
        Mail::to($staff->email)->send(new StaffDeleted($staff));
        AuditLog::record('staff_deleted', "Admin deleted staff: {$staff->email}");

        $staff->delete();
    }

    private function findStaff(int $staffId): User
    {
        return User::where('role', 'staff')->findOrFail($staffId);
    }
}
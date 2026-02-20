<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService) {}

    /**
     * list all staff members with their approval status, ordered by pending first, then approved, then blocked, and finally by creation date.
     */
    public function index()
    {
        $staff = User::where('role', 'staff')
            ->orderByRaw("CASE
                WHEN status = 'pending'  THEN 1
                WHEN status = 'approved' THEN 2
                WHEN status = 'blocked'  THEN 3
            END")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.staff-management', compact('staff'));
    }

    /**
     * Approve a pending staff member. Only users with 'admin' role can perform this action.
     */
    public function approve(Request $request, int $staffId)
    {
        try {
            $this->approvalService->approve($request->user(), $staffId);
            return back()->with('success', 'Staff member approved successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['approval' => $e->getMessage()]);
        }
    }

    /**
     * Block a staff member. Only users with 'admin' role can perform this action.
     */
    public function block(int $staffId)
    {
        try {
            $this->approvalService->block($staffId);
            return back()->with('success', 'Staff member blocked.');
        } catch (\Exception $e) {
            return back()->withErrors(['approval' => $e->getMessage()]);
        }
    }

    /**
     * Unblock a staff member. Only users with 'admin' role can perform this action.
     */
    public function unblock(int $staffId)
    {
        try {
            $this->approvalService->unblock($staffId);
            return back()->with('success', 'Staff member unblocked.');
        } catch (\Exception $e) {
            return back()->withErrors(['approval' => $e->getMessage()]);
        }
    }

    /**
     * Delete a staff member. Only users with 'admin' role can perform this action.
     */
    public function destroy(int $staffId)
    {
        try {
            $this->approvalService->delete($staffId);
            return back()->with('success', 'Staff member deleted.');
        } catch (\Exception $e) {
            return back()->withErrors(['approval' => $e->getMessage()]);
        }
    }
}
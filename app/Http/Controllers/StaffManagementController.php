<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Requests\Admin\CreateStaffRequest;
use App\Services\Admin\StaffManagementService;

class StaffManagementController extends Controller
{
    /**
     * Handle the creation of a new staff member (admin or staff) and log the action in the audit log.
     */
    public function store(CreateStaffRequest $request, StaffManagementService $service)
    {
        $user = $service->createUser(
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
}
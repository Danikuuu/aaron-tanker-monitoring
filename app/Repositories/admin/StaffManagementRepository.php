<?php

namespace App\Repositories\Admin;

use App\Models\User;
use App\Repositories\Admin\StaffManagementInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class StaffManagementRepository implements StaffManagementInterface
{
    public function getPaginatedStaff(int $perPage = 10): LengthAwarePaginator
    {
        return User::whereIn('role', ['staff', 'admin'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function approve(User $user): void
    {
        $user->status = 'approved';
        $user->save();
    }

    public function block(User $user): void
    {
        $user->status = 'blocked';
        $user->save();
    }

    public function unblock(User $user): void
    {
        $user->status = 'approved';
        $user->save();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     * Create a manually-added user — pre-approved so they can log in immediately.
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $role,
    ): User {
        return User::create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'password'   => Hash::make($password),
            'role'       => $role,
            'status'     => 'approved', // manually added users skip the approval queue
        ]);
    }
}
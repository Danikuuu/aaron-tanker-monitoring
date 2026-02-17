<?php

namespace App\Repositories\Admin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface StaffManagementInterface
{
    public function getPaginatedStaff(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?User;

    public function approve(User $user): void;

    public function block(User $user): void;

    public function unblock(User $user): void;

    public function delete(User $user): void;

    /**
     * Manually create a new user (staff or admin) with an already-approved status.
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $role,
    ): User;
}
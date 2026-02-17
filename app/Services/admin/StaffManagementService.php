<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Repositories\Admin\StaffManagementInterface;
use Illuminate\Validation\ValidationException;

class StaffManagementService
{
    public function __construct(
        protected StaffManagementInterface $staffManagementRepository,
    ) {}

    public function getPaginatedStaff(int $perPage = 10)
    {
        return $this->staffManagementRepository->getPaginatedStaff($perPage);
    }

    public function approve(int $id): void
    {
        $user = $this->findOrFail($id);
        $this->staffManagementRepository->approve($user);
    }

    public function block(int $id): void
    {
        $user = $this->findOrFail($id);
        $this->staffManagementRepository->block($user);
    }

    public function unblock(int $id): void
    {
        $user = $this->findOrFail($id);
        $this->staffManagementRepository->unblock($user);
    }

    public function delete(int $id): void
    {
        $user = $this->findOrFail($id);
        $this->staffManagementRepository->delete($user);
    }

    /**
     * Manually create a new staff member or admin.
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $role,
    ): User {
        return $this->staffManagementRepository->createUser(
            $firstName,
            $lastName,
            $email,
            $password,
            $role,
        );
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function findOrFail(int $id): User
    {
        $user = $this->staffManagementRepository->findById($id);
    
        if (!$user) {
            throw ValidationException::withMessages(['user' => 'User not found.']);
        }

        return $user;
    }
}
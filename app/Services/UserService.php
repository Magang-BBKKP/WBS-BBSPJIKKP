<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginatedUsers(int $perPage = 10, ?string $search = null)
    {
        return $this->userRepository->getPaginated($perPage, $search);
    }

    public function getUserById(int $id): User
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            abort(404, 'User not found.');
        }
        return $user;
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'] ?? null,
                'unit_kerja' => $data['unit_kerja'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'status' => isset($data['is_active']) && !$data['is_active'] ? 'inactive' : 'active',
            ]);

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function updateUser(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->getUserById($id);

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'unit_kerja' => $data['unit_kerja'] ?? null,
                'is_active' => $data['is_active'] ?? $user->is_active,
                'status' => isset($data['is_active']) && !$data['is_active'] ? 'inactive' : 'active',
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $this->userRepository->update($user, $updateData);

            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            return $user;
        });
    }

    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        return $this->userRepository->delete($user);
    }

    public function toggleUserStatus(int $id): User
    {
        $user = $this->getUserById($id);
        $newActiveStatus = !$user->is_active;

        $this->userRepository->update($user, [
            'is_active' => $newActiveStatus,
            'status' => $newActiveStatus ? 'active' : 'inactive',
        ]);

        return $user;
    }
}

<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getPaginated(int $perPage = 10, ?string $search = null)
    {
        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}

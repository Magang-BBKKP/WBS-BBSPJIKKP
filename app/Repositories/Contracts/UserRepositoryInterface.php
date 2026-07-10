<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
    public function update(User $user, array $data): bool;
    public function create(array $data): User;
    public function getPaginated(int $perPage = 10, ?string $search = null);
    public function delete(User $user): bool;
}

<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    protected $userRepository;
    protected $auditLogRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuditLogRepositoryInterface $auditLogRepository
    ) {
        $this->userRepository = $userRepository;
        $this->auditLogRepository = $auditLogRepository;
    }

    public function updateProfile(User $user, array $data, string $ip, string $userAgent): bool
    {
        $success = $this->userRepository->update($user, $data);

        if ($success) {
            $this->auditLogRepository->log([
                'user_id' => $user->id,
                'action' => 'Update Profile',
                'description' => 'User updated their profile information.',
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }

        return $success;
    }

    public function changePassword(User $user, string $newPassword, string $ip, string $userAgent): bool
    {
        $success = $this->userRepository->update($user, [
            'password' => Hash::make($newPassword)
        ]);

        if ($success) {
            $this->auditLogRepository->log([
                'user_id' => $user->id,
                'action' => 'Change Password',
                'description' => 'User successfully changed their password.',
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }

        return $success;
    }
}

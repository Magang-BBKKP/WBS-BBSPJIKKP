<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthService
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

    public function attemptLogin(array $credentials, bool $remember, string $ip, string $userAgent): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Update last login info
            $this->userRepository->update($user, [
                'last_login_at' => now(),
                'last_login_ip' => $ip,
                'last_login_browser' => $userAgent,
            ]);

            // Log activity
            $this->logActivity($user->id, 'Login', 'User successfully logged in.', $ip, $userAgent);

            return true;
        }

        // Log failed attempt if user exists
        $user = $this->userRepository->findByEmail($credentials['email']);
        if ($user) {
            $this->logActivity($user->id, 'Failed Login', 'User failed to log in due to invalid credentials.', $ip, $userAgent);
        }

        return false;
    }

    public function logout(User $user, string $ip, string $userAgent): void
    {
        $this->logActivity($user->id, 'Logout', 'User logged out.', $ip, $userAgent);
        Auth::logout();
    }

    public function sendResetLink(array $data, string $ip, string $userAgent): string
    {
        $status = Password::sendResetLink($data);

        if ($status == Password::RESET_LINK_SENT) {
            $user = $this->userRepository->findByEmail($data['email']);
            if ($user) {
                $this->logActivity($user->id, 'Forgot Password', 'User requested password reset link.', $ip, $userAgent);
            }
        }

        return $status;
    }

    public function register(array $data, string $ip, string $userAgent): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        $this->logActivity($user->id, 'Register', 'User successfully registered.', $ip, $userAgent);

        Auth::login($user);
        
        $this->userRepository->update($user, [
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'last_login_browser' => $userAgent,
        ]);
        
        $this->logActivity($user->id, 'Login', 'User automatically logged in after registration.', $ip, $userAgent);

        return $user;
    }

    public function resetPassword(array $data, string $ip, string $userAgent): string
    {
        $status = Password::reset($data, function ($user, string $password) use ($ip, $userAgent) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(\Illuminate\Support\Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            $this->logActivity($user->id, 'Reset Password', 'User successfully reset their password.', $ip, $userAgent);
        });

        return $status;
    }

    protected function logActivity(?int $userId, string $action, string $description, string $ip, string $userAgent): void
    {
        $this->auditLogRepository->log([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}

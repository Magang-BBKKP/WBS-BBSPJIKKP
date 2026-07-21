<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated(), 
            $request->ip(), 
            $request->userAgent()
        );

        $request->session()->regenerate();
        
        if ($user && $user->can('view-dashboard')) {
            return redirect()->route('dashboard');
        }
        
        return redirect()->route('home');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if ($this->authService->attemptLogin($credentials, $remember, $request->ip(), $request->userAgent())) {
            $request->session()->regenerate();
            
            $user = auth()->user();
            if ($user && $user->can('view-dashboard')) {
                return redirect()->intended(route('dashboard'));
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $this->authService->logout($request->user(), $request->ip(), $request->userAgent());
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $status = $this->authService->sendResetLink($request->only('email'), $request->ip(), $request->userAgent());

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['request' => $request, 'token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->authService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            $request->ip(),
            $request->userAgent()
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}

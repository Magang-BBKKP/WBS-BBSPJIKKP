<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $search = $request->input('search');
        $users = $this->userService->getPaginatedUsers(10, $search);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);

        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $this->userService->createUser($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $roles = Role::all();
        $userRole = $user->roles->first()?->name;

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $this->userService->updateUser($user->id, $data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $this->userService->deleteUser($user->id);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(User $user)
    {
        Gate::authorize('update', $user);

        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $this->userService->toggleUserStatus($user->id);

        return redirect()->back()->with('success', 'Status keaktifan user berhasil diubah.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $this->profileService->updateProfile($request->user(), $data, $request->ip(), $request->userAgent());

        return back()->with('status', 'profile-updated');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->profileService->changePassword($request->user(), $request->validated('password'), $request->ip(), $request->userAgent());

        return back()->with('status', 'password-updated');
    }
}

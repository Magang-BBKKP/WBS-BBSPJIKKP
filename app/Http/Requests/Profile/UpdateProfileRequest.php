<?php

namespace App\Http\Requests\Profile;

use App\Rules\UniquePhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20', new UniquePhoneNumber(auth()->id())],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ];
    }
}

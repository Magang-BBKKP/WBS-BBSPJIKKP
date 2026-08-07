<?php

namespace App\Http\Requests\Auth;

use App\Rules\UniquePhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class CompletePhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{7,11}$/', new UniquePhoneNumber(auth()->id())],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'phone_number.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format Indonesia, mis. 0812-3456-7890.',
        ];
    }
}

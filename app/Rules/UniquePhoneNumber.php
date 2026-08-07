<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniquePhoneNumber implements ValidationRule
{
    protected ?int $ignoreId;

    public function __construct(?int $ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = $this->normalize($value);

        if ($normalized === null) {
            $fail('Format nomor WhatsApp tidak valid.');
            return;
        }

        $phones = DB::table('users')
            ->whereNotNull('phone_number')
            ->when($this->ignoreId !== null, fn ($q) => $q->where('id', '!=', $this->ignoreId))
            ->pluck('phone_number');

        foreach ($phones as $phone) {
            if ($this->normalize($phone) === $normalized) {
                $fail('Nomor WhatsApp sudah digunakan oleh akun lain.');
                return;
            }
        }
    }

    protected function normalize(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phoneNumber);

        if ($normalized === null || $normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, '0')) {
            $normalized = '62' . substr($normalized, 1);
        } elseif (str_starts_with($normalized, '8')) {
            $normalized = '62' . $normalized;
        }

        return $normalized;
    }
}

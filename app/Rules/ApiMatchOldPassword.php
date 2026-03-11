<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class ApiMatchOldPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $user;

    // Parameter diambil lewat constructor di sini
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Hash::check($value, $this->user->password)) {
            $fail('Password lama salah');
        }
    }
}

<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {

        $this->ensureIsNotRateLimited();
        if($this->LdapLogin($this->email, $this->password)) {

            $bilgiler = User::where('email','=',$this->email)->first();
            Auth::loginUsingId($bilgiler->id);
            RateLimiter::clear($this->throttleKey());
        }else{
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    private function LdapLogin($email,$password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;

        $fp = fsockopen ( "79.123.186.104" , 110 );
        if (!$fp) {
            return false;
        }
        $trash = fgets ( $fp, 128 );
        fwrite ( $fp, "USER ".$email."\r\n" );

        $trash = fgets ( $fp, 128 );
        fwrite ( $fp, "PASS ".$password."\r\n" );
        $result = fgets ( $fp, 128 );

        if(str_starts_with($result, '+OK'))
            return true;
        else
            return false;
    }
}

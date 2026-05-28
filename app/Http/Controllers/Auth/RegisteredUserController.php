<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Domain email sekali pakai / disposable yang diblokir.
     * Tambah sendiri kalau ada yang lolos.
     */
    private const BLOCKED_DOMAINS = [
        'mailinator.com', 'guerrillamail.com', 'tempmail.com', 'throwam.com',
        'yopmail.com', 'trashmail.com', 'sharklasers.com', 'guerrillamailblock.com',
        'grr.la', 'guerrillamail.info', 'guerrillamail.biz', 'guerrillamail.de',
        'guerrillamail.net', 'guerrillamail.org', 'spam4.me', 'dispostable.com',
        'maildrop.cc', 'mintemail.com', 'tempinbox.com', 'fakeinbox.com',
        'mailnull.com', 'spamgourmet.com', 'trashmail.at', 'trashmail.io',
        'trashmail.me', 'getairmail.com', 'discard.email', 'spambog.com',
        'tempr.email', 'zetmail.com', 'mohmal.com', 'throwam.com',
        'spambox.us', 'mailnesia.com', 'mailbucket.org', 'mytrashmail.com',
        'mailexpire.com', '10minutemail.com', '10minutemail.net', 'tempmail.net',
        'emailondeck.com', 'burnermail.io', 'inboxbear.com',
    ];

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Blokir domain email disposable / sekali pakai
        $emailDomain = strtolower(substr(strrchr($request->email, '@'), 1));
        if (in_array($emailDomain, self::BLOCKED_DOMAINS)) {
            throw ValidationException::withMessages([
                'email' => 'Email dari domain tersebut tidak diizinkan untuk registrasi.',
            ]);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminNewPasswordController extends Controller
{
    /**
     * Display the admin password reset view.
     */
    public function create(Request $request): View
    {
        return view('admin.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new admin password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ], [
            'token.required'                 => 'Token atur ulang kata sandi tidak ditemukan.',
            'email.required'                 => 'Alamat email wajib diisi.',
            'email.email'                    => 'Format alamat email tidak valid.',
            'password.required'              => 'Kata sandi baru wajib diisi.',
            'password.confirmed'             => 'Konfirmasi kata sandi baru tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin) use ($request) {
                $admin->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Kata sandi Administrator berhasil diperbarui! Silakan masuk kembali.')
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}

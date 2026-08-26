<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPasswordResetLinkController extends Controller
{
    /**
     * Display the admin password reset link request view.
     */
    public function create(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle an incoming admin password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email admin wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        try {
            $status = Password::broker('admins')->sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email reset password admin: ' . $e->getMessage(), [
                'email' => $request->input('email'),
            ]);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Gagal memproses permintaan atur ulang kata sandi. Silakan coba beberapa saat lagi.']);
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        return back()->with('status', __($status));
    }
}

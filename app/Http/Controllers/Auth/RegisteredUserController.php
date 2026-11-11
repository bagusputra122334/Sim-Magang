<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
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
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = strtolower(trim((string) $request->email));
        $existingUser = User::where('email', $email)->first();

        // Anti-User Enumeration (Standar Keamanan Siber Diskominfo SP):
        // Jika email sudah terdaftar, jangan bocorkan eksistensi akun melalui pesan error validasi.
        // Kirimkan notifikasi keamanan ke email pemilik asli secara senyap (silent notification)
        // dan kembalikan pesan status yang generik dan konsisten ke pendaftar.
        if ($existingUser) {
            try {
                \Illuminate\Support\Facades\Mail::to($existingUser->email)->send(
                    new \App\Mail\AccountAlreadyRegisteredMail($existingUser)
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::info('[SECURITY] Gagal mengirim alert registrasi ganda: ' . $e->getMessage());
            }

            return redirect()->route('login')->with('status', 'Permintaan pendaftaran Anda telah diterima. Jika alamat email Anda belum terdaftar, tautan verifikasi telah dikirimkan. Jika sudah pernah terdaftar, silakan langsung masuk ke sistem.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        if (! $user->role instanceof UserRole || $user->role !== UserRole::Peserta) {
            $user->role = UserRole::Peserta;
            $user->save();
        }

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Permintaan pendaftaran Anda telah diterima. Silakan periksa kotak masuk atau folder spam email Anda untuk mengaktifkan akun sebelum masuk ke sistem.');
    }
}

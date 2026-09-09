<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle incoming contact form submission from public landing page.
     */
    public function send(Request $request): JsonResponse|RedirectResponse
    {
        // Normalize parameter aliases (nama, whatsapp, wa_number, phone, kategori, pesan)
        if (!$request->has('phone') || empty($request->input('phone'))) {
            $request->merge([
                'phone' => $request->input('wa_number') ?? $request->input('whatsapp') ?? $request->input('telepon'),
            ]);
        }
        if (!$request->has('name') || empty($request->input('name'))) {
            $request->merge([
                'name' => $request->input('nama'),
            ]);
        }
        if (!$request->has('category') || empty($request->input('category'))) {
            $request->merge([
                'category' => $request->input('kategori'),
            ]);
        }
        if (!$request->has('message') || empty($request->input('message'))) {
            $request->merge([
                'message' => $request->input('pesan'),
            ]);
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'phone'    => ['required', 'string', 'min:8', 'max:25'],
            'email'    => ['required', 'email', 'max:150'],
            'category' => ['required', 'string'],
            'message'  => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'name.min'          => 'Nama lengkap minimal 3 karakter.',
            'phone.required'    => 'Nomor WhatsApp / telepon wajib diisi.',
            'phone.min'         => 'Nomor telepon minimal 8 digit.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'category.required' => 'Silakan pilih kategori peserta.',
            'message.required'  => 'Pesan atau pertanyaan wajib diisi.',
            'message.min'       => 'Pesan minimal berisi 10 karakter.',
        ]);

        $destinationEmail = 'bagusdwijunior@gmail.com';

        try {
            Mail::to($destinationEmail)->send(new ContactMessageMail(
                name: $validated['name'],
                phone: $validated['phone'],
                email: $validated['email'],
                category: $validated['category'],
                messageContent: $validated['message'],
                submittedAt: now()->translatedFormat('d F Y, H:i') . ' WIB'
            ));

            $successMessage = 'Pesan Anda telah berhasil dikirim! Tim Diskominfo SP Kabupaten Tuban akan segera meninjau dan merespons pertanyaan Anda.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                ]);
            }

            return redirect()->to(url('/#contact'))
                ->with('success', $successMessage)
                ->with('contact_success', $successMessage);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email formulir kontak landing page: ' . $e->getMessage(), [
                'sender_email' => $validated['email'] ?? null,
                'category'     => $validated['category'] ?? null,
                'error'        => $e->getMessage(),
            ]);

            $errorMessage = 'Maaf, terjadi kendala teknis saat memproses pengiriman pesan. Silakan hubungi kami langsung melalui telepon (0356) 321000 atau email resmi diskominfo@tubankab.go.id.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 500);
            }

            return redirect()->to(url('/#contact'))
                ->withInput()
                ->with('error', $errorMessage)
                ->with('contact_error', $errorMessage);
        }
    }
}


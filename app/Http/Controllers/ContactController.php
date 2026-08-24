<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactMessageMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Handle incoming contact form submission from public landing page.
     */
    public function send(ContactMessageRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $destinationEmail = config('mail.from.address', 'diskominfo@tubankab.go.id');

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

            return redirect()->to(url('/#contact'))->with('contact_success', $successMessage);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email formulir kontak landing page: ' . $e->getMessage(), [
                'sender_email' => $validated['email'] ?? null,
                'category'     => $validated['category'] ?? null,
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
                ->with('contact_error', $errorMessage);
        }
    }
}

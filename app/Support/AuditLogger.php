<?php

namespace App\Support;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * Lightweight Audit Logger (Sprint 20 Bagian 5)
 *
 * TIDAK MEMBUAT TABEL BARU / MIGRASI.
 * Hanya menulis ke Laravel Log (storage/logs/laravel.log) via Log facade
 * dengan FORMAT KONSISTEN JSON context untuk memudahkan grep / parsing ELK nanti.
 *
 * Aktivitas yang dicatat sesuai Sprint 20 Spec:
 *  - Login, Logout (Breeze lifecycle)
 *  - Submit / Update / Hapus Pendaftaran Magang (Peserta)
 *  - Verifikasi Admin (Under Review auto, Accepted, Rejected)
 *  - Upload / Replace Surat Balasan PDF (Admin)
 *  - Export Excel Data Pendaftaran (Admin)
 *  - Kirim Email Notification (5 jenis Mailable)
 *
 * Safety: SEMUA method WRAPPED try/catch Throwable internal.
 * Jika Audit Log GAGAL (permission write log, disk full), operasi bisnis TETAP BERJALAN.
 * Tidak pernah throw exception ke user / controller caller.
 */
final class AuditLogger
{
    public const CHANNEL = 'single';

    public const ACT_LOGIN = 'auth.login';
    public const ACT_LOGOUT = 'auth.logout';
    public const ACT_REGISTER = 'auth.register';

    public const ACT_POSITION_CREATE = 'admin.position.create';
    public const ACT_POSITION_UPDATE = 'admin.position.update';
    public const ACT_POSITION_DELETE = 'admin.position.delete';

    public const ACT_PROFILE_CREATE = 'participant.profile.create';
    public const ACT_PROFILE_UPDATE = 'participant.profile.update';

    public const ACT_REGISTRATION_SUBMIT = 'participant.registration.submit';
    public const ACT_REGISTRATION_UPDATE = 'participant.registration.update';
    public const ACT_REGISTRATION_DELETE = 'participant.registration.delete';

    public const ACT_VERIFY_UNDER_REVIEW_AUTO = 'admin.verify.auto_under_review';
    public const ACT_VERIFY_ACCEPTED = 'admin.verify.accepted';
    public const ACT_VERIFY_REJECTED = 'admin.verify.rejected';

    public const ACT_REPLY_LETTER_UPLOAD = 'admin.reply_letter.upload';
    public const ACT_REPLY_LETTER_DOWNLOAD_ADMIN = 'admin.reply_letter.download';
    public const ACT_REPLY_LETTER_DOWNLOAD_PARTICIPANT = 'participant.reply_letter.download';

    public const ACT_EXPORT_EXCEL = 'admin.export.excel';

    public const ACT_EMAIL_SENT_OK = 'notification.email.sent_ok';
    public const ACT_EMAIL_SENT_FAIL = 'notification.email.sent_failed';

    /**
     * Main Log Writer — FORMAT KONSISTEN.
     *
     * @param string                     $activity Constant ACT_* class ini.
     * @param array<string, mixed>       $detail   Detail bebas (mis. registration_id, position_id, filter_export, etc).
     *                                             TIDAK BOLEH berisi plaintext password / token sensitif.
     * @param User|null                  $actor    User yang melakukan aksi. Default = Auth::user() saat ini.
     */
    public static function write(
        string $activity,
        array $detail = [],
        ?User $actor = null,
    ): void {
        try {
            $user = $actor ?? (Auth::check() ? Auth::user() : null);
            \assert($user === null || $user instanceof User);

            $userId    = $user?->id;
            $userName  = $user?->name ?? '[Guest]';
            $userRole  = $user?->role instanceof UserRole
                ? $user->role->value
                : ($user !== null ? 'unknown' : 'guest');

            $context = [
                // ---- Identitas Pelaku ----
                'user_id'    => $userId,
                'user_name'  => $userName,
                'user_role'  => $userRole,
                // ---- Aktivitas ----
                'activity'   => $activity,
                'detail'     => $detail,
                // ---- Request Metadata (Traceability Security) ----
                'ip_address' => self::getClientIp(),
                'user_agent' => Request::userAgent() ?? '-',
                'method'     => Request::method(),
                'url'        => Request::path(),
                // ---- Waktu ----
                'occurred_at'=> now()->toIso8601String(),
            ];

            $message = sprintf(
                '[AUDIT] %s | user=#%s (%s, role=%s) | ip=%s | act=%s | detail=%s',
                $context['occurred_at'],
                $userId ?? 'guest',
                $userName,
                $userRole,
                $context['ip_address'],
                $activity,
                self::stringifyDetail($detail)
            );

            Log::channel(self::CHANNEL)->info($message, $context);
        } catch (\Throwable) {
            // SILENT FAIL: Audit log error TIDAK BOLEH mengganggu bisnis flow user.
            // (Mis: folder storage/logs permission readonly -> operasi utama (simpan DB) tetep jalan)
        }
    }

    /* =============================================================
     *  Helper Convenience Methods (SUGAR API — per aktivitas spesifik)
     * ============================================================= */

    public static function login(?User $user = null): void
    {
        self::write(self::ACT_LOGIN, actor: $user);
    }

    public static function logout(?User $user = null): void
    {
        self::write(self::ACT_LOGOUT, actor: $user);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public static function exportExcel(array $filters, int $countSqlApproxRow = 0): void
    {
        self::write(self::ACT_EXPORT_EXCEL, [
            'filters'       => $filters,
            'estimated_rows'=> $countSqlApproxRow,
        ]);
    }

    /**
     * @param class-string $mailableClass
     */
    public static function emailSent(
        bool $success,
        string $mailableClass,
        string $recipientEmail,
        int $registrationId,
        ?\Throwable $error = null,
    ): void {
        self::write(
            activity: $success ? self::ACT_EMAIL_SENT_OK : self::ACT_EMAIL_SENT_FAIL,
            detail: [
                'mailable_class'  => class_basename($mailableClass),
                'recipient_email' => $recipientEmail,
                'registration_id' => $registrationId,
                'error_class'     => $error !== null ? $error::class : null,
                'error_message'   => $error?->getMessage(),
            ],
        );
    }

    /* =============================================================
     *  Internal Utility
     * ============================================================= */

    /**
     * Ambil IP Address client konsisten (bisa handle Proxy / Cloudflare / XAMPP localhost).
     */
    private static function getClientIp(): string
    {
        try {
            $ip = Request::ip();
            if (is_string($ip) && $ip !== '') {
                return $ip;
            }
            // Fallback CLI (tinker / queue worker) — 127.0.0.1 = localhost.
            return '127.0.0.1';
        } catch (\Throwable) {
            return '127.0.0.1';
        }
    }

    /**
     * Stringify detail array jadi 1 line singkat untuk log message (mudah grep tanpa buka JSON).
     * Jika panjang > 200 char dipotong + [...].
     *
     * @param array<string, mixed> $detail
     */
    private static function stringifyDetail(array $detail): string
    {
        if ($detail === []) {
            return '{}';
        }
        try {
            $enc = json_encode($detail, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
            if ($enc === false) {
                return '(unserializable-detail-count='.count($detail).')';
            }
            if (mb_strlen($enc) > 220) {
                $enc = mb_substr($enc, 0, 215).' [...]';
            }
            return $enc;
        } catch (\Throwable) {
            return '(detail-serialization-failed)';
        }
    }
}

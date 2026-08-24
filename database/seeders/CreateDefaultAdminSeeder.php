<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateDefaultAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = 'admin@diskominfo-tuban.go.id';
        $password = 'AdminTuban@2026';
        $nama     = 'Administrator Diskominfo Tuban';

        $existing = User::query()->where('email', $email)->first();

        if ($existing instanceof User) {
            if (! $existing->isAdmin()) {
                User::withoutEvents(static function () use ($existing): void {
                    $existing->forceFill(['role' => UserRole::Admin])->save();
                });
                $this->command->warn('  ⚠️  Email '.$email.' SUDAH ADA — ROLE diubah menjadi ADMIN. Silakan re-login.');
            } else {
                $this->command->warn('  ⚠️  Email '.$email.' SUDAH ADA & sudah berperan ADMIN. Gunakan password yang benar, atau reset via Forgot Password.');
            }

            $this->command->line(sprintf('  👤 ID #%d | %s <%s> | role=%s',
                $existing->id,
                $existing->name,
                $existing->email,
                $existing->role?->value ?? 'null'
            ));

            return;
        }

        $admin = User::withoutEvents(static function () use ($email, $password, $nama): User {
            return User::query()->create([
                'name'              => $nama,
                'email'             => $email,
                'password'          => bcrypt($password),
                'email_verified_at' => now(),
                'role'              => UserRole::Admin,
            ]);
        });

        $this->command->info('  ✅ AKUN ADMIN BERHASIL DIBUAT!');
        $this->command->line(sprintf('  👤 ID #%d | Nama  : %s', $admin->id, $admin->name));
        $this->command->line(sprintf('     📧 Email : %s', $admin->email));
        $this->command->line(sprintf('     🔑 Password: %s  (SEGERA GANTI SETELAH LOGIN!)', $password));
        $this->command->line(sprintf('     🎯 Role  : %s', $admin->role?->value ?? 'null'));
        $this->command->newLine();
        $this->command->line('  👉 Kunjungi: '.route('login', absolute: false).' dan login dengan kredensial di atas.');
    }
}

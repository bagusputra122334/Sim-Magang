<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Notifications\QueuedAdminResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Model Admin — Data autentikasi Administrator SIM-MAGANG
 */
class Admin extends User implements CanResetPasswordContract
{
    use CanResetPassword;

    protected $table = 'users';

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('admin_role', function ($builder): void {
            $builder->where('role', UserRole::Admin->value);
        });

        static::creating(function (self $admin): void {
            $admin->role = UserRole::Admin;
        });
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new QueuedAdminResetPassword($token));
    }
}

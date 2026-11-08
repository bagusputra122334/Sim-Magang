<?php

namespace App\Models\Concerns;

use App\Models\Opd;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Trait BelongsToOpd
 *
 * Mengisolasi data multi-tenant OPD:
 * - Saat user yang login adalah Admin OPD (memiliki opd_id dan bukan Super Admin),
 *   query model otomatis terisolasi dengan filter WHERE {table}.opd_id = auth()->user()->opd_id.
 * - Saat Admin OPD membuat record baru dan opd_id kosong, opd_id otomatis terisi auth()->user()->opd_id.
 * - Saat Super Admin, Pembimbing, Peserta, atau Guest yang mengakses, Global Scope TIDAK diterapkan.
 */
trait BelongsToOpd
{
    /**
     * Boot the BelongsToOpd trait for a model.
     */
    public static function bootBelongsToOpd(): void
    {
        // 1. Global Scope isolasi tenant untuk Admin OPD
        static::addGlobalScope('opd_tenant', function (Builder $builder): void {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                if (method_exists($user, 'isAdminOpd') && $user->isAdminOpd() && !empty($user->opd_id)) {
                    $builder->where($builder->getModel()->getTable() . '.opd_id', $user->opd_id);
                }
            }
        });

        // 2. Creating Event: auto-fill opd_id jika dibuat oleh Admin OPD
        static::creating(function (Model $model): void {
            if (empty($model->opd_id) && Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                if (method_exists($user, 'isAdminOpd') && $user->isAdminOpd() && !empty($user->opd_id)) {
                    $model->opd_id = $user->opd_id;
                }
            }
        });
    }

    /**
     * Relasi ke master data OPD.
     */
    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }
}

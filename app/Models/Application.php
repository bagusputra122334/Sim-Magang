<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;

/**
 * Model Application
 *
 * Represents an internship application (alias/inheritor of Registration).
 */
#[Fillable([
    'nomor_pendaftaran',
    'user_id',
    'position_id',
    'cv_path',
    'surat_pengantar_path',
    'proposal_magang_path',
    'status',
    'catatan_admin',
    'surat_balasan_path',
    'tanggal_submit',
    'periode_mulai',
    'periode_selesai',
    'is_terminated',
    'catatan_penonaktifan',
    'terminated_at',
    'certificate_path',
    'certificate_issued_at',
])]
class Application extends Registration
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'registrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_pendaftaran',
        'user_id',
        'position_id',
        'cv_path',
        'surat_pengantar_path',
        'proposal_magang_path',
        'status',
        'catatan_admin',
        'surat_balasan_path',
        'tanggal_submit',
        'periode_mulai',
        'periode_selesai',
        'is_terminated',
        'catatan_penonaktifan',
        'terminated_at',
        'certificate_path',
        'certificate_issued_at',
    ];
}

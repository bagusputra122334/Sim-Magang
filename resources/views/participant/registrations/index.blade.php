@extends('layouts.participant')
@section('title', 'Riwayat Pendaftaran Magang')

@php
if (!function_exists('statusBadgeClass')) {
    function statusBadgeClass($statusValue): string {
        return match($statusValue) {
            'submitted'    => 'bg-primary-subtle text-primary-emphasis border-primary-subtle',
            'under_review' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
            'accepted'     => 'bg-success-subtle text-success-emphasis border-success-subtle',
            'rejected'     => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
            default        => 'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle',
        };
    }
}
if (!function_exists('statusBadgeIcon')) {
    function statusBadgeIcon($statusValue): string {
        return match($statusValue) {
            'submitted'    => '<i class="bi bi-send-check-fill me-1"></i>',
            'under_review' => '<i class="bi bi-hourglass-split me-1"></i>',
            'accepted'     => '<i class="bi bi-check-circle-fill me-1"></i>',
            'rejected'     => '<i class="bi bi-x-circle-fill me-1"></i>',
            default        => '',
        };
    }
}
@endphp

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-11">


        @if(!$punyaProfile)
        <div class="alert alert-warning border-2 rounded-4 mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3 flex-shrink-0 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading fw-semibold mb-1">Profil belum lengkap!</h5>
                <p class="mb-2">Anda tidak dapat mengajukan pendaftaran magang sebelum melengkapi profil data pribadi & data akademik Anda.</p>
                <a href="{{ route('participant.profile.create') }}" class="btn btn-warning fw-semibold shadow-sm">
                    <i class="bi bi-person-plus-fill me-2"></i>Lengkapi Profil Sekarang
                </a>
            </div>
        </div>
        @endif

        <div class="mb-4 d-md-flex justify-content-between align-items-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold text-success">
                    <i class="bi bi-journal-text me-2"></i>Riwayat Pendaftaran Magang
                </h2>
                <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Semua riwayat pengajuan pendaftaran magang Anda akan ditampilkan disertai status prosesnya.</p>
            </div>
            <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                <div id="serverTimeTracker" data-server-timestamp="{{ now()->timestamp }}" class="d-none"></div>
                @if($bisaDaftarBaru)
                <a href="{{ route('participant.registrations.create') }}" class="btn btn-success fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center" style="min-height: 44px;">
                    <i class="bi bi-plus-circle-fill me-2"></i>Ajukan Pendaftaran Baru
                </a>
                @endif
            </div>
        </div>

        @if(!$bisaDaftarBaru && $punyaProfile)
        <div class="alert alert-info border-2 rounded-4 mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-info-circle-fill fs-3 text-info me-3 flex-shrink-0 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading fw-semibold mb-1">Anda masih memiliki pendaftaran magang yang sedang aktif!</h5>
                <p class="mb-0">Tunggu proses seleksi atau selesaikan pendaftaran yang sedang berjalan sebelum mengajukan pendaftaran magang baru.</p>
            </div>
        </div>
        @endif

        @if($registrations->count() === 0)
            <div class="card border-0 shadow-sm rounded-4 text-center py-6 px-4">
                <div class="card-body py-5">
                    <div class="display-1 text-light mb-4">
                        <i class="bi bi-folder2-open text-success opacity-25"></i>
                    </div>
                    <h4 class="fw-semibold mb-2">Belum Ada Riwayat Pendaftaran</h4>
                    <p class="text-muted mb-4">Anda belum pernah mengajukan pendaftaran magang. Klik tombol di bawah untuk mulai mendaftar posisi magang yang tersedia.</p>
                    @if($bisaDaftarBaru)
                    <a href="{{ route('participant.registrations.create') }}" class="btn btn-success btn-lg fw-semibold shadow-sm">
                        <i class="bi bi-send-plus-fill me-2"></i>Ajukan Pendaftaran Pertama
                    </a>
                    @elseif(!$punyaProfile)
                    <a href="{{ route('participant.profile.create') }}" class="btn btn-warning btn-lg fw-semibold shadow-sm">
                        <i class="bi bi-person-plus-fill me-2"></i>Lengkapi Profil Terlebih Dahulu
                    </a>
                    @endif
                </div>
            </div>
        @else

        {{-- Tabel Riwayat Pendaftaran --}}
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="registrationsTable">
                        <thead class="table-success border-0">
                            <tr>
                                @if($registrations->count() > 1)
                                <th class="px-3 py-3 fw-semibold text-center" style="width: 54px;" title="Pilih pendaftaran">Pilih</th>
                                @endif
                                <th class="text-start px-4 py-3 fw-semibold">Nomor Pendaftaran</th>
                                <th class="px-4 py-3 fw-semibold">Posisi Magang</th>
                                <th class="px-4 py-3 fw-semibold">Periode Magang</th>
                                <th class="px-4 py-3 fw-semibold">Tanggal Submit</th>
                                <th class="px-4 py-3 fw-semibold text-center">Status</th>
                                <th class="px-4 py-3 fw-semibold text-center">Surat Balasan</th>
                                <th class="px-4 py-3 fw-semibold text-end" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            @foreach($registrations as $index => $reg)
                                @php 
                                    $sv = $reg->status->value; 
                                    $isSelected = $index === 0;
                                    $hasSurat = $reg->isAccepted() && !empty($reg->surat_balasan_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($reg->surat_balasan_path);
                                    $isAccepted = $reg->isAccepted();
                                    $isTerminated = (bool) ($reg->is_terminated || $reg->operational_status === 'terminated');
                                    $isExpired = false;
                                    if ($isAccepted && $reg->periode_selesai) {
                                        $isExpired = now()->timezone('Asia/Jakarta')->isAfter($reg->periode_selesai->copy()->timezone('Asia/Jakarta')->endOfDay());
                                    }
                                    $statusLabel = $isTerminated ? 'Dinonaktifkan' : ($isAccepted && $isExpired ? 'Selesai Magang' : $reg->status->label());
                                @endphp
                                <tr class="border-bottom registration-row {{ $isSelected ? 'table-active' : '' }}"
                                    role="button"
                                    tabindex="0"
                                    data-id="{{ $reg->id }}"
                                    data-nomor="{{ $reg->nomor_pendaftaran }}"
                                    data-posisi="{{ $reg->position?->nama_posisi ?? '-' }}"
                                    data-status-label="{{ $statusLabel }}"
                                    data-status-val="{{ $sv }}"
                                    data-is-accepted="{{ $isAccepted ? '1' : '0' }}"
                                    data-is-terminated="{{ $isTerminated ? '1' : '0' }}"
                                    data-is-expired="{{ $isExpired ? '1' : '0' }}"
                                    data-can-edit="{{ $reg->dapatDiubah() ? '1' : '0' }}"
                                    data-can-delete="{{ $reg->dapatDihapus() ? '1' : '0' }}"
                                    data-periode-mulai="{{ $reg->periode_mulai ? $reg->periode_mulai->format('Y-m-d') : '' }}"
                                    data-periode-selesai="{{ $reg->periode_selesai ? $reg->periode_selesai->format('Y-m-d') : '' }}"
                                    data-show-url="{{ route('participant.registrations.show', $reg->id) }}"
                                    data-edit-url="{{ route('participant.registrations.edit', $reg->id) }}"
                                    data-delete-url="{{ route('participant.registrations.destroy', $reg->id) }}"
                                    data-has-surat="{{ $hasSurat ? '1' : '0' }}"
                                    data-surat-url="{{ route('participant.applications.reply-letter.download', $reg->id) }}"
                                >
                                    @if($registrations->count() > 1)
                                    <td class="px-3 py-3 text-center">
                                        <input class="form-check-input registration-radio cursor-pointer" 
                                               type="radio" 
                                               name="selected_registration" 
                                               id="reg_radio_{{ $reg->id }}" 
                                               value="{{ $reg->id }}" 
                                               {{ $isSelected ? 'checked' : '' }}
                                               aria-label="Pilih pendaftaran {{ $reg->nomor_pendaftaran }}">
                                    </td>
                                    @endif
                                    <td class="px-4 py-3">
                                        <div class="fw-bold fs-6 text-success font-monospace">
                                            <i class="bi bi-ticket-perforated-fill me-1 opacity-75"></i>{{ $reg->nomor_pendaftaran }}
                                        </div>
                                        <div class="small text-muted mt-1">
                                            ID #{{ $reg->id }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-body">{{ $reg->position?->nama_posisi ?? '-' }}</div>
                                        <div class="small text-muted">
                                            <i class="bi bi-briefcase me-1"></i>{{ $reg->position?->slug ?? 'Diskominfo SP' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-medium text-body">{!! $reg->periode_label ?? '<span class="text-muted">-</span>' !!}</div>
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar-range me-1"></i>{{ $reg->periode_mulai?->diffInDays($reg->periode_selesai) + 1 ?? 0 }} Hari
                                        </div>
                                        @if($isAccepted)
                                        <div class="period-status-indicator mt-1" data-reg-id="{{ $reg->id }}">
                                            @if($isTerminated)
                                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                    <i class="bi bi-x-circle me-1"></i>Dinonaktifkan
                                                </span>
                                            @elseif($isExpired)
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                    <i class="bi bi-clock-history me-1"></i>Periode Berakhir
                                                </span>
                                            @else
                                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                                                    <i class="bi bi-broadcast me-1"></i>Periode Aktif
                                                </span>
                                            @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-medium text-body">{{ $reg->tanggal_submit?->translatedFormat('d M Y') }}</div>
                                        <div class="small text-muted">{{ $reg->tanggal_submit?->translatedFormat('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($isTerminated)
                                            <span class="badge border rounded-pill px-3 py-2 fs-6 bg-danger-subtle text-danger-emphasis border-danger-subtle status-badge-cell" data-reg-id="{{ $reg->id }}">
                                                <i class="bi bi-x-circle-fill me-1"></i>Dinonaktifkan
                                            </span>
                                        @elseif($isAccepted && $isExpired)
                                            <span class="badge border rounded-pill px-3 py-2 fs-6 bg-secondary-subtle text-secondary-emphasis border-secondary-subtle status-badge-cell" data-reg-id="{{ $reg->id }}">
                                                <i class="bi bi-check-all me-1"></i>Selesai Magang
                                            </span>
                                        @else
                                            <span class="badge border rounded-pill px-3 py-2 fs-6 {!! statusBadgeClass($sv) !!} status-badge-cell" data-reg-id="{{ $reg->id }}">
                                                {!! statusBadgeIcon($sv) !!}
                                                {{ $reg->status->label() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($isAccepted)
                                            @if($hasSurat)
                                                <span class="badge bg-success rounded-pill px-3 py-2"
                                                      data-bs-toggle="tooltip"
                                                      title="Surat Balasan sudah diunggah Admin dan siap diunduh.">
                                                    <i class="bi bi-file-earmark-check-fill me-1"></i> Tersedia
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3 py-2"
                                                      data-bs-toggle="tooltip"
                                                      title="Status diterima, tapi Admin belum mengunggah Surat Balasan.">
                                                    <i class="bi bi-clock-history me-1"></i> Menunggu Upload
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted small fst-italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="{{ route('participant.registrations.show', $reg->id) }}"
                                           class="btn btn-sm btn-outline-success rounded-3 px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs"
                                           title="Lihat Detail Pendaftaran">
                                            <i class="bi bi-eye-fill"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($registrations->hasPages())
                <div class="card-footer bg-body border-top py-4 px-5 d-md-flex justify-content-between align-items-center">
                    <div class="small text-muted mb-2 mb-md-0">
                        Menampilkan {{ $registrations->firstItem() ?? 0 }}–{{ $registrations->lastItem() ?? 0 }} dari total {{ $registrations->total() }} pendaftaran
                    </div>
                    <div>
                        {{ $registrations->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Floating Action Panel --}}
        @php
            $firstReg = $registrations->first();
            $firstSv = $firstReg?->status->value;
            $firstIsAccepted = $firstReg?->isAccepted();
            $firstIsTerminated = (bool) ($firstReg?->is_terminated || $firstReg?->operational_status === 'terminated');
            $firstHasSurat = $firstReg && $firstIsAccepted && !empty($firstReg->surat_balasan_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($firstReg->surat_balasan_path);
            $firstIsExpired = false;
            if ($firstIsAccepted && $firstReg?->periode_selesai) {
                $firstIsExpired = now()->timezone('Asia/Jakarta')->isAfter($firstReg->periode_selesai->copy()->timezone('Asia/Jakarta')->endOfDay());
            }
            $firstStatusLabel = $firstIsTerminated ? 'Dinonaktifkan' : ($firstIsAccepted && $firstIsExpired ? 'Selesai Magang' : $firstReg?->status->label());
        @endphp

        <div class="card shadow-sm border border-success-subtle rounded-4 mt-4 bg-white overflow-hidden" id="actionSectionCard">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-3 bg-success-subtle text-success flex-shrink-0">
                            <i class="bi bi-ticket-detailed-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h5 class="fw-bold mb-0 text-dark">
                                    Pendaftaran Terpilih: <span class="font-monospace text-success" id="selectedNomorDisplay">{{ $firstReg->nomor_pendaftaran }}</span>
                                </h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 small fw-semibold" id="selectedStatusDisplay">
                                    {{ $firstStatusLabel }}
                                </span>
                            </div>
                            <div class="text-secondary small mb-0 d-flex align-items-center gap-2 flex-wrap">
                                <span><i class="bi bi-briefcase me-1 text-success"></i>Posisi: <strong class="text-dark" id="selectedPositionDisplay">{{ $firstReg->position?->nama_posisi ?? '-' }}</strong></span>
                                <span class="text-muted">&bull;</span>
                                <span class="text-muted"><i class="bi bi-info-circle me-1"></i>Klik baris lain pada tabel untuk memilih pendaftaran berbeda</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2.5 justify-content-start justify-content-lg-end" id="actionButtonsContainer">
                        {{-- Detail Button (Selalu Tersedia) --}}
                        <a href="{{ route('participant.registrations.show', $firstReg->id) }}"
                           id="btnActionDetail"
                           class="btn btn-success rounded-3 px-4 py-2.5 fw-semibold text-sm d-inline-flex align-items-center justify-content-center gap-2 shadow-sm"
                           data-bs-toggle="tooltip"
                           title="Lihat Detail Lengkap Pendaftaran">
                            <i class="bi bi-eye-fill fs-6"></i>
                            <span>Detail Pendaftaran</span>
                        </a>

                        {{-- Surat Balasan Button (Khusus Accepted jika surat tersedia dan tidak dinonaktifkan) --}}
                        <a href="{{ route('participant.applications.reply-letter.download', $firstReg->id) }}"
                           id="btnActionSurat"
                           class="btn btn-success rounded-3 px-4 py-2.5 fw-semibold text-sm d-inline-flex align-items-center justify-content-center gap-2 shadow-sm {{ !$firstHasSurat || $firstIsTerminated ? 'd-none' : '' }}"
                           data-bs-toggle="tooltip"
                           title="Download Surat Balasan Resmi PDF">
                            <i class="bi bi-file-earmark-pdf-fill fs-6"></i>
                            <span>Download Surat Balasan</span>
                        </a>

                        {{-- Ubah / Edit Button (Hanya jika belum diterima / status submitted) --}}
                        <a href="{{ route('participant.registrations.edit', $firstReg->id) }}"
                           id="btnActionEdit"
                           class="btn btn-outline-primary rounded-3 px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 {{ $firstIsAccepted ? ' d-none' : '' }}{{ !$firstReg->dapatDiubah() ? ' disabled' : '' }}"
                           {!! !$firstReg->dapatDiubah() ? 'aria-disabled="true" tabindex="-1"' : '' !!}
                           data-bs-toggle="tooltip"
                           title="{{ !$firstReg->dapatDiubah() ? 'Hanya pendaftaran berstatus Diajukan yang dapat diubah' : 'Ubah data pendaftaran' }}">
                            <i class="bi bi-pencil-square fs-6"></i>
                            <span>Ubah Data</span>
                        </a>

                        {{-- Hapus / Delete Form & Button (Hanya jika belum diterima / status submitted/rejected) --}}
                        <form action="{{ route('participant.registrations.destroy', $firstReg->id) }}"
                              method="POST"
                              id="formActionDelete"
                              class="d-inline form-delete-registration {{ $firstIsAccepted ? 'd-none' : '' }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    id="btnActionDelete"
                                    class="btn btn-outline-danger rounded-3 px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2"
                                    data-nomor="{{ $firstReg->nomor_pendaftaran }}"
                                    {{ !$firstReg->dapatDihapus() ? 'disabled' : '' }}
                                    data-bs-toggle="tooltip"
                                    title="{{ !$firstReg->dapatDihapus() ? 'Pendaftaran yang sedang diproses atau diterima tidak dapat dihapus' : 'Hapus pendaftaran ini' }}">
                                <i class="bi bi-trash3-fill fs-6"></i>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.registration-row {
    transition: background-color 0.15s ease-in-out;
}
.registration-row.table-active {
    --bs-table-accent-bg: rgba(25, 135, 84, 0.08);
}
[data-bs-theme="dark"] .registration-row.table-active {
    --bs-table-accent-bg: rgba(34, 197, 94, 0.15);
}
.registration-row:focus-visible {
    outline: 2px solid var(--app-primary, #0d6efd);
    outline-offset: -2px;
}
.me-1\.5 {
    margin-right: 0.375rem !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.registration-row');
    const btnDetail = document.getElementById('btnActionDetail');
    const btnEdit = document.getElementById('btnActionEdit');
    const formDelete = document.getElementById('formActionDelete');
    const btnDelete = document.getElementById('btnActionDelete');
    const btnSurat = document.getElementById('btnActionSurat');
    const nomorDisplay = document.getElementById('selectedNomorDisplay');
    const positionDisplay = document.getElementById('selectedPositionDisplay');
    const statusDisplay = document.getElementById('selectedStatusDisplay');
    const clockDisplay = document.getElementById('liveJakartaClock');

    // Server-Synchronized Asia/Jakarta Date Time Engine
    const serverTracker = document.getElementById('serverTimeTracker');
    const initialServerSec = serverTracker && serverTracker.getAttribute('data-server-timestamp')
        ? parseInt(serverTracker.getAttribute('data-server-timestamp'), 10)
        : Math.floor(Date.now() / 1000);
    const initialServerMs = initialServerSec * 1000;
    const clientStartMs = Date.now();
    const serverTimeOffset = initialServerMs - clientStartMs;

    function getJakartaNow() {
        const currentSyncMs = Date.now() + serverTimeOffset;
        const d = new Date(currentSyncMs);
        const utcMs = d.getTime() + (d.getTimezoneOffset() * 60000);
        return new Date(utcMs + (7 * 3600000)); // UTC+7 (Asia/Jakarta)
    }

    function isDateExpired(dateStr) {
        if (!dateStr) return false;
        const parts = dateStr.split('-');
        if (parts.length !== 3) return false;
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);
        
        // Exact End of day in Asia/Jakarta: 23:59:59.999 WIB (= 16:59:59.999 UTC)
        const endOfDayUtcMs = Date.UTC(year, month, day, 16, 59, 59, 999);
        const currentSyncMs = Date.now() + serverTimeOffset;
        return currentSyncMs > endOfDayUtcMs;
    }

    function updateLiveClock() {
        const jakartaNow = getJakartaNow();
        const fullMonths = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const day = String(jakartaNow.getDate()).padStart(2, '0');
        const month = fullMonths[jakartaNow.getMonth()];
        const year = jakartaNow.getFullYear();
        const hours = String(jakartaNow.getHours()).padStart(2, '0');
        const minutes = String(jakartaNow.getMinutes()).padStart(2, '0');
        const seconds = String(jakartaNow.getSeconds()).padStart(2, '0');
        
        if (clockDisplay) {
            clockDisplay.textContent = `${day} ${month} ${year}, ${hours}:${minutes}:${seconds} WIB`;
        }
    }

    function checkAllPeriods() {
        rows.forEach(function (row) {
            const isAccepted = row.getAttribute('data-is-accepted') === '1';
            const isTerminated = row.getAttribute('data-is-terminated') === '1';
            const selesaiStr = row.getAttribute('data-periode-selesai');
            const regId = row.getAttribute('data-id');

            if (isAccepted && selesaiStr) {
                const expired = isDateExpired(selesaiStr);
                row.setAttribute('data-is-expired', expired ? '1' : '0');

                // Update Status Cell in Table
                const statusBadgeCell = row.querySelector('.status-badge-cell');
                if (statusBadgeCell) {
                    if (isTerminated) {
                        statusBadgeCell.className = 'badge border rounded-pill px-3 py-2 fs-6 bg-danger-subtle text-danger-emphasis border-danger-subtle status-badge-cell';
                        statusBadgeCell.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Dinonaktifkan';
                    } else if (expired) {
                        statusBadgeCell.className = 'badge border rounded-pill px-3 py-2 fs-6 bg-secondary-subtle text-secondary-emphasis border-secondary-subtle status-badge-cell';
                        statusBadgeCell.innerHTML = '<i class="bi bi-check-all me-1"></i>Selesai Magang';
                    } else {
                        statusBadgeCell.className = 'badge border rounded-pill px-3 py-2 fs-6 bg-success-subtle text-success-emphasis border-success-subtle status-badge-cell';
                        statusBadgeCell.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Diterima';
                    }
                }

                // Update Period Indicator
                const periodIndicator = row.querySelector('.period-status-indicator');
                if (periodIndicator) {
                    if (isTerminated) {
                        periodIndicator.innerHTML = '<span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-0.5" style="font-size: 0.7rem;"><i class="bi bi-x-circle me-1"></i>Dinonaktifkan</span>';
                    } else if (expired) {
                        periodIndicator.innerHTML = '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;"><i class="bi bi-clock-history me-1"></i>Periode Berakhir</span>';
                    } else {
                        periodIndicator.innerHTML = '<span class="badge bg-success-subtle text-success rounded-pill px-2 py-0.5" style="font-size: 0.7rem;"><i class="bi bi-broadcast me-1"></i>Periode Aktif</span>';
                    }
                }

                // Update currently selected text if active
                if (row.classList.contains('table-active') && statusDisplay) {
                    statusDisplay.textContent = isTerminated ? 'Dinonaktifkan' : (expired ? 'Selesai Magang' : 'Diterima');
                }
            }
        });
    }

    function updateActionTarget(row) {
        if (!row) return;

        const id = row.getAttribute('data-id');
        const nomor = row.getAttribute('data-nomor');
        const posisi = row.getAttribute('data-posisi');
        const statusVal = row.getAttribute('data-status-val');
        const isAccepted = row.getAttribute('data-is-accepted') === '1';
        const isTerminated = row.getAttribute('data-is-terminated') === '1';
        const isExpired = row.getAttribute('data-is-expired') === '1';
        let statusLabel = row.getAttribute('data-status-label');
        if (isTerminated) {
            statusLabel = 'Dinonaktifkan';
        } else if (isAccepted && isExpired) {
            statusLabel = 'Selesai Magang';
        }

        const canEdit = row.getAttribute('data-can-edit') === '1';
        const canDelete = row.getAttribute('data-can-delete') === '1';
        const showUrl = row.getAttribute('data-show-url');
        const editUrl = row.getAttribute('data-edit-url');
        const deleteUrl = row.getAttribute('data-delete-url');
        const hasSurat = row.getAttribute('data-has-surat') === '1';
        const suratUrl = row.getAttribute('data-surat-url');

        // Update active row visual
        rows.forEach(function (r) {
            r.classList.remove('table-active');
        });
        row.classList.add('table-active');

        // Update radio if present
        const radio = row.querySelector('.registration-radio');
        if (radio) {
            radio.checked = true;
        }

        // Update dropdown if present
        if (selectDropdown && selectDropdown.value !== id) {
            selectDropdown.value = id;
        }

        // Update action card theme & badge
        const actionCard = document.getElementById('actionSectionCard');
        if (actionCard) {
            if (isTerminated) {
                actionCard.className = 'card shadow-sm border border-danger-subtle rounded-4 mt-4 bg-danger-subtle bg-opacity-10 overflow-hidden';
            } else {
                actionCard.className = 'card shadow-sm border border-success-subtle rounded-4 mt-4 bg-white overflow-hidden';
            }
        }

        // Update text labels & status badge
        if (nomorDisplay) nomorDisplay.textContent = nomor;
        if (positionDisplay) positionDisplay.textContent = posisi;
        if (statusDisplay) {
            statusDisplay.textContent = statusLabel;
            if (isTerminated) {
                statusDisplay.className = 'badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 small fw-semibold';
            } else {
                statusDisplay.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 small fw-semibold';
            }
        }

        // 1. Detail Button (Always Available)
        if (btnDetail) {
            btnDetail.href = showUrl;
            btnDetail.classList.remove('d-none');
        }

        // 2. Action Rules:
        if (isTerminated) {
            // When Deactivated: SHOW ONLY Detail button. Hide Surat Balasan, Ubah, & Hapus.
            if (btnEdit) btnEdit.classList.add('d-none');
            if (formDelete) formDelete.classList.add('d-none');
            if (btnSurat) btnSurat.classList.add('d-none');
        } else if (isAccepted) {
            if (btnEdit) btnEdit.classList.add('d-none');
            if (formDelete) formDelete.classList.add('d-none');

            // Surat Balasan
            if (btnSurat) {
                btnSurat.href = suratUrl;
                if (hasSurat) {
                    btnSurat.classList.remove('d-none');
                } else {
                    btnSurat.classList.add('d-none');
                }
            }
        } else {
            // Not accepted (Submitted, Under Review, Rejected)
            if (btnSurat) btnSurat.classList.add('d-none');

            // Ubah Button
            if (btnEdit) {
                btnEdit.href = editUrl;
                if (statusVal === 'rejected') {
                    btnEdit.classList.add('d-none');
                } else {
                    btnEdit.classList.remove('d-none');
                    if (canEdit) {
                        btnEdit.classList.remove('disabled');
                        btnEdit.removeAttribute('aria-disabled');
                        btnEdit.removeAttribute('tabindex');
                        btnEdit.setAttribute('data-bs-original-title', 'Ubah data pendaftaran');
                        btnEdit.setAttribute('title', 'Ubah data pendaftaran');
                    } else {
                        btnEdit.classList.add('disabled');
                        btnEdit.setAttribute('aria-disabled', 'true');
                        btnEdit.setAttribute('tabindex', '-1');
                        btnEdit.setAttribute('data-bs-original-title', 'Hanya pendaftaran berstatus Diajukan yang dapat diubah');
                        btnEdit.setAttribute('title', 'Hanya pendaftaran berstatus Diajukan yang dapat diubah');
                    }
                }
            }

            // Hapus Button
            if (formDelete && btnDelete) {
                formDelete.classList.remove('d-none');
                formDelete.action = deleteUrl;
                btnDelete.setAttribute('data-nomor', nomor);
                if (canDelete) {
                    btnDelete.removeAttribute('disabled');
                    btnDelete.setAttribute('data-bs-original-title', 'Hapus pendaftaran ' + nomor);
                    btnDelete.setAttribute('title', 'Hapus pendaftaran ' + nomor);
                } else {
                    btnDelete.setAttribute('disabled', 'disabled');
                    btnDelete.setAttribute('data-bs-original-title', 'Pendaftaran yang sedang diproses atau diterima tidak dapat dihapus');
                    btnDelete.setAttribute('title', 'Pendaftaran yang sedang diproses atau diterima tidak dapat dihapus');
                }
            }
        }
    }

    // Row click and key handlers
    rows.forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'radio') {
                // radio change
            }
            updateActionTarget(this);
        });

        row.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                updateActionTarget(this);
            }
        });
    });

    // Delete confirmation handler
    document.querySelectorAll('.form-delete-registration').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const nomor = btn?.getAttribute('data-nomor') || 'ini';
            const ok = confirm('Apakah Anda yakin ingin MENGHAPUS pendaftaran dengan nomor ' + nomor + '?\n\nTindakan ini juga akan menghapus seluruh file dokumen yang diunggah (CV & Surat Pengantar). Data yang dihapus TIDAK DAPAT DIKEMBALIKAN.');
            if (ok) {
                this.submit();
            }
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    // Start real-time timers
    updateLiveClock();
    checkAllPeriods();
    setInterval(updateLiveClock, 1000);
    setInterval(checkAllPeriods, 1000);
});
</script>
@endpush


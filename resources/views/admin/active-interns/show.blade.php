@extends('layouts.admin')

@section('title', 'Detail Peserta Magang — ' . ($intern->user?->name ?? ''))

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0 text-slate-800 dark:text-slate-100">
                <i class="bi bi-person-bounding-box text-primary me-1.5"></i>Detail Peserta Magang
            </h1>
            <p class="text-muted mb-0 text-xs">Informasi bio peserta, alokasi posisi, pembimbing lapangan, dan kontrol status magang.</p>
        </div>
        <a href="{{ route('admin.statuses.index') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-all duration-150 text-decoration-none">
            <i class="bi bi-arrow-left text-slate-600"></i>
            <span>Kembali ke Status Magang</span>
        </a>
    </div>

    <div class="row g-2.5">
        {{-- Left Column: Bio & Internship Overview --}}
        <div class="col-lg-8">
            {{-- Status Card --}}
            <div class="card border shadow-sm mb-3 rounded-2">
                <div class="card-body p-3 sm:p-4 d-flex align-items-center justify-content-between flex-wrap gap-2.5">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="p-2 rounded-circle bg-light text-primary">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted text-xs d-block">Status Operasional Magang</span>
                            @if ($intern->operational_status === 'completed')
                                @if (! empty($intern->certificate_path))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 mt-0.5">
                                        <i class="bi bi-patch-check-fill text-emerald-700"></i> Selesai & Sertifikat Terbit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 border border-slate-200 mt-0.5" title="Peserta telah selesai magang dan menantikan penerbitan sertifikat">
                                        <i class="bi bi-award text-slate-700"></i> Selesai (Menunggu Sertifikat)
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $intern->operational_status_badge_class }} mt-0.5">
                                    {{ $intern->operational_status_label }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if(!$intern->is_terminated)
                            <button type="button" class="btn btn-danger-subtle text-danger border border-danger-subtle rounded-2 px-3 py-1.5 text-xs font-semibold shadow-sm hover-btn-danger transition-all d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                <i class="bi bi-person-x-fill"></i>
                                <span>Nonaktifkan Magang Peserta</span>
                            </button>
                        @else
                            <form method="POST" action="{{ route('admin.active-interns.toggle-status', $intern->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                        class="btn btn-success-subtle text-success border border-success-subtle rounded-2 px-3 py-1.5 text-xs font-semibold shadow-sm hover-btn-success transition-all d-inline-flex align-items-center gap-1.5"
                                        data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Nama Peserta</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($intern->user?->name ?? '—') }}</div><div class='col-4 text-slate-500 font-semibold'>No. Pendaftaran</div><div class='col-8 font-monospace'>{{ $intern->nomor_pendaftaran }}</div></div>"
                                        data-simang-title="Aktifkan Kembali Magang"
                                        data-simang-icon="bi-check-circle-fill"
                                        data-simang-theme="emerald"
                                        data-simang-alert="Status operasional magang peserta ini akan diaktifkan kembali. Peserta dapat mengakses kembali absensi dan materi penugasan."
                                        data-simang-btn-text="Ya, Aktifkan Kembali"
                                        data-simang-btn-icon="bi-check-circle-fill">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Aktifkan Kembali Magang</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @if($intern->is_terminated && $intern->catatan_penonaktifan)
                    <div class="card-footer bg-danger-subtle border-top border-danger-subtle p-2.5 text-xs">
                        <small class="text-danger fw-bold d-block mb-1"><i class="bi bi-exclamation-octagon me-1"></i> Catatan Penonaktifan Admin:</small>
                        <p class="text-dark mb-0 text-xs">{{ $intern->catatan_penonaktifan }}</p>
                        <small class="text-muted d-block mt-0.5">Waktu Penonaktifan: {{ $intern->terminated_at?->translatedFormat('d F Y, H:i') }} WIB</small>
                    </div>
                @endif
            </div>

            {{-- Bio Data Card --}}
            @php $prof = $intern->user?->profile; @endphp
            <div class="card border shadow-sm mb-3 rounded-2">
                <div class="card-header bg-transparent border-bottom py-2 px-3">
                    <h5 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                        <i class="bi bi-person-vcard text-primary me-1.5"></i>Biodata Peserta Magang
                    </h5>
                </div>
                <div class="card-body p-3 sm:p-4 text-xs">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Nama Lengkap</dt>
                        <dd class="col-sm-8 fw-bold text-dark text-xs mb-0">{{ $intern->user?->name ?? '—' }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Alamat Email</dt>
                        <dd class="col-sm-8 text-body font-monospace text-xs mb-0">{{ $intern->user?->email ?? '—' }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Kategori Peserta</dt>
                        <dd class="col-sm-8 mb-0 text-xs">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5 text-xs">
                                {{ $prof?->isSiswa() ? 'Siswa / SMK' : 'Mahasiswa Perguruan Tinggi' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Asal Instansi / Sekolah</dt>
                        <dd class="col-sm-8 fw-semibold text-dark text-xs mb-0">{{ $prof?->instansi ?? '—' }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Jurusan / Program Studi</dt>
                        <dd class="col-sm-8 text-body text-xs mb-0">{{ $prof?->jurusan ?? '—' }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">NIM / NIS</dt>
                        <dd class="col-sm-8 font-monospace text-xs mb-0">{{ $prof?->nim_nis ?? '—' }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Nomor HP / WhatsApp</dt>
                        <dd class="col-sm-8 font-monospace text-dark text-xs mb-0">{{ $prof?->no_hp ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Sertifikat Kelulusan Magang Card --}}
            @php
                $certPath = $intern->certificate_path;
                $certDisk = \Illuminate\Support\Facades\Storage::disk('public');
                $certExists = !empty($certPath) && is_string($certPath) && $certDisk->exists($certPath);
                $certBasename = $certExists ? basename($certPath) : null;
                $certSize = $certExists ? number_format((int) round($certDisk->size($certPath) / 1024), 0, ',', '.') . ' KB' : null;
                $certIssuedAt = $intern->certificate_issued_at;
            @endphp

            @if ($certExists)
                {{-- KONDISI SUDAH DIUNGGAH (HIJAU SUKSES) --}}
                <div class="card shadow-sm border border-emerald-500 bg-emerald-50/30 dark:bg-emerald-950/20 mb-3 rounded-2 overflow-hidden">
                    <div class="card-header bg-emerald-50/80 dark:bg-emerald-900/40 border-bottom border-emerald-200 dark:border-emerald-800/60 py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="text-xs font-bold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider mb-0 d-flex align-items-center gap-1.5">
                            <i class="bi bi-patch-check-fill text-emerald-600 dark:text-emerald-400"></i>SERTIFIKAT KELULUSAN MAGANG
                        </h5>
                        <span class="badge !bg-emerald-100 !text-emerald-900 font-bold border border-emerald-300 px-2.5 py-0.5 rounded-pill text-xs d-inline-flex align-items-center gap-1 shadow-xs">
                            <i class="bi bi-check-circle-fill !text-emerald-700"></i> Sertifikat Telah Terbit
                        </span>
                    </div>
                    <div class="card-body p-3 sm:p-4 text-xs">
                        <div class="d-flex align-items-start gap-2.5 flex-wrap flex-md-nowrap mb-2.5">
                            <div class="p-2 rounded-2 bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:border-emerald-800 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-award-fill fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 class="text-xs font-bold !text-slate-900 dark:!text-slate-100 uppercase mb-0">Sertifikat Kelulusan Digital</h6>
                                    <span class="badge !bg-emerald-100 !text-emerald-900 border border-emerald-300 font-bold px-2 py-0.5 rounded-pill text-xs">Resmi Diterbitkan</span>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 text-xs mb-2">
                                    Sertifikat kelulusan magang resmi telah diterbitkan dan tersedia untuk diunduh oleh peserta melalui portal magang mereka.
                                </p>

                                <div class="d-flex flex-wrap gap-2 mb-1">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs shadow-xs">
                                        <i class="bi bi-filetype-pdf text-rose-500"></i>
                                        <span class="font-monospace font-bold !text-slate-900 dark:!text-slate-100">{{ $certBasename }}</span>
                                    </div>
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs shadow-xs">
                                        <i class="bi bi-hdd text-slate-500"></i>
                                        <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $certSize }}</span>
                                    </div>
                                    @if($certIssuedAt)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md text-xs shadow-xs">
                                            <i class="bi bi-calendar-check text-emerald-600"></i>
                                            <span class="text-slate-700 dark:text-slate-300 font-medium">Diterbitkan: {{ \Illuminate\Support\Carbon::parse($certIssuedAt)->translatedFormat('d F Y • H:i') }} WIB</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-emerald-200 dark:border-emerald-900/40 justify-content-between align-items-center">
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <a href="{{ route('admin.active-interns.certificate.view', $intern->id) }}" target="_blank" rel="noopener"
                                   class="btn btn-outline-primary btn-sm px-2.5 py-1 text-xs font-semibold shadow-xs d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-eye"></i> Preview
                                </a>
                                <a href="{{ route('admin.active-interns.certificate.download', $intern->id) }}"
                                   class="btn btn-secondary btn-sm px-2.5 py-1 text-xs shadow-sm font-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                            <span class="text-xs text-muted d-inline-flex align-items-center gap-1">
                                <i class="bi bi-shield-check text-emerald-600"></i> Diunggah oleh Pembimbing Lapangan
                            </span>
                        </div>
                    </div>
                </div>
            @else
                {{-- KONDISI AWAL: BELUM DIUNGGAH OLEH PEMBIMBING (STATUS SUPERVISOR / MONITORING ONLY) --}}
                <div class="card shadow-sm border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/20 mb-3 rounded-2 overflow-hidden">
                    <div class="card-header bg-slate-100/80 dark:bg-slate-800/60 border-bottom border-slate-200 dark:border-slate-700 py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="text-xs font-bold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider mb-0 d-flex align-items-center gap-1.5">
                            <i class="bi bi-award text-slate-500"></i>SERTIFIKAT KELULUSAN MAGANG
                        </h5>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-950 border border-amber-300 shadow-sm" style="color: #451a03 !important; background-color: #fef3c7 !important;">
                            <i class="bi bi-clock-history text-amber-700"></i> Belum Diunggah Pembimbing
                        </span>
                    </div>
                    <div class="card-body p-3 sm:p-4 text-xs">
                        <div class="d-flex align-items-start gap-2.5">
                            <div class="p-2 rounded-2 bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 class="text-xs font-bold !text-slate-900 dark:!text-slate-100 uppercase mb-0">Status Penerbitan Sertifikat</h6>
                                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-slate-200 text-slate-900 border border-slate-300" style="color: #0f172a !important; background-color: #e2e8f0 !important;">
                                        Supervisor / View Only
                                    </span>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 text-xs mb-2">
                                    Sertifikat kelulusan magang digital untuk peserta ini belum diunggah oleh <strong>Pembimbing Lapangan ({{ $intern->position?->mentor_name ?? 'Pembimbing Divisi Terkait' }})</strong>.
                                </p>
                                <div class="inline-flex items-center gap-2 px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-600 dark:text-slate-300">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    <span>Hak akses penerbitan dan unggah sertifikat menjadi wewenang penuh pembimbing lapangan pada menu <em>Sertifikat Magang</em>. Admin bertindak sebagai supervisor pemantau status.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Internship Placement & Mentor --}}
        <div class="col-lg-4">
            <div class="card border shadow-sm mb-3 rounded-2">
                <div class="card-header bg-transparent border-bottom py-2 px-3">
                    <h5 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                        <i class="bi bi-briefcase text-primary me-1.5"></i>Informasi Posisi & Unit
                    </h5>
                </div>
                <div class="card-body p-3 sm:p-4 text-xs">
                    <div class="mb-2.5">
                        <span class="text-muted text-xs d-block">Posisi Magang</span>
                        <span class="fw-bold text-primary text-xs">{{ $intern->position?->nama_posisi ?? '—' }}</span>
                    </div>

                    <div class="mb-2.5">
                        <span class="text-muted text-xs d-block">Nomor Pendaftaran</span>
                        <code class="text-xs font-monospace">{{ $intern->nomor_pendaftaran }}</code>
                    </div>

                    <div class="mb-2.5">
                        <span class="text-muted text-xs d-block">Periode Pelaksanaan</span>
                        <div class="fw-bold text-dark text-xs mt-0.5">
                            {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                            <span class="text-muted mx-1">s/d</span>
                            {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <hr class="my-2 text-muted border-slate-200">

                    <div class="mb-0">
                        <span class="text-muted text-xs d-block mb-1">Pembimbing Lapangan (Mentor)</span>
                        @if($intern->position?->mentor_name)
                            <div class="fw-bold text-dark text-xs">{{ $intern->position->mentor_name }}</div>
                            @if($intern->position->mentor_nip)
                                <small class="text-muted font-monospace d-block text-xs">NIP: {{ $intern->position->mentor_nip }}</small>
                            @endif
                        @else
                            <span class="text-muted font-italic text-xs">— Belum Ditentukan —</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Deactivation Modal --}}
    @if(!$intern->is_terminated)
        <div class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-slate-200 dark:border-slate-800 shadow-2xl rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
                    <form method="POST" action="{{ route('admin.active-interns.toggle-status', $intern->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-rose-600 text-white py-2.5 px-3.5">
                            <h5 class="modal-title text-xs mb-0 text-white font-bold uppercase tracking-wider d-flex align-items-center gap-2" id="deactivateModalLabel">
                                <i class="bi bi-person-x-fill fs-6"></i> Konfirmasi Penonaktifan Magang
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 sm:p-4 text-xs space-y-3">
                            <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs">
                                <div class="row g-1">
                                    <div class="col-4 text-slate-500 font-semibold">Nama Peserta</div>
                                    <div class="col-8 fw-bold text-slate-900 dark:text-slate-100">{{ $intern->user?->name ?? '—' }}</div>
                                    <div class="col-4 text-slate-500 font-semibold">No. Pendaftaran</div>
                                    <div class="col-8 font-monospace">{{ $intern->nomor_pendaftaran }}</div>
                                    <div class="col-4 text-slate-500 font-semibold">Unit / Posisi</div>
                                    <div class="col-8">{{ $intern->position?->nama_posisi ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="catatan_penonaktifan" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Alasan / Catatan Penonaktifan (Opsional)</label>
                                <textarea name="catatan_penonaktifan" id="catatan_penonaktifan" rows="3" class="form-control rounded-xl py-1.5 px-2.5 text-xs border-slate-300" placeholder="Contoh: Mengundurkan diri / Pelanggaran aturan..."></textarea>
                            </div>

                            <div class="p-3 text-xs border rounded-xl d-flex align-items-start gap-2 bg-rose-50 text-rose-900 border-rose-200 dark:bg-rose-950/40 dark:text-rose-200 dark:border-rose-800">
                                <i class="bi bi-exclamation-triangle-fill text-rose-600 mt-0.5 flex-shrink-0 fs-6"></i>
                                <div class="leading-relaxed">
                                    Status operasional akan berubah menjadi <strong>Dinonaktifkan / Berhenti</strong>. Peserta tidak dapat melakukan absensi harian dan penerbitan sertifikat akan dibekukan.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-slate-50 dark:bg-slate-800/60 border-top border-slate-200 dark:border-slate-800 py-2 px-3.5 d-flex justify-content-between align-items-center">
                            <button type="button" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm border-0" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm border-0 d-inline-flex align-items-center gap-1.5">
                                <i class="bi bi-person-x-fill"></i>
                                <span>Ya, Nonaktifkan Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

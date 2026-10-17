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
        <a href="{{ route('admin.active-interns.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Status Magang
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
                            <span class="badge {{ $intern->operational_status_badge_class }} rounded-pill px-2.5 py-0.5 text-xs mt-0.5">
                                {{ $intern->operational_status_label }}
                            </span>
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
                                <button type="submit" class="btn btn-success-subtle text-success border border-success-subtle rounded-2 px-3 py-1.5 text-xs font-semibold shadow-sm hover-btn-success transition-all d-inline-flex align-items-center gap-1.5" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali status magang peserta ini?')">
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
                <div class="modal-content border-0 shadow rounded-2">
                    <form method="POST" action="{{ route('admin.active-interns.toggle-status', $intern->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-danger text-white py-2.5 px-3">
                            <h5 class="modal-header-title text-xs mb-0 text-white font-bold uppercase tracking-wider" id="deactivateModalLabel">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Konfirmasi Penonaktifan Magang
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 sm:p-4 text-xs">
                            <p class="mb-2.5 text-dark">
                                Anda akan menonaktifkan status magang peserta <strong>{{ $intern->user?->name }}</strong>. Status operasional akan berubah menjadi <code>Dinonaktifkan / Berhenti</code>.
                            </p>
                            <div class="mb-2">
                                <label for="catatan_penonaktifan" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Alasan / Catatan Penonaktifan (Opsional)</label>
                                <textarea name="catatan_penonaktifan" id="catatan_penonaktifan" rows="3" class="form-control rounded-2 py-1.5 px-2.5 text-xs" placeholder="Contoh: Mengundurkan diri / Pelanggaran aturan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-3">
                            <button type="button" class="btn btn-secondary btn-sm py-1 px-2.5 text-xs font-semibold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm py-1 px-3 text-xs font-bold">Ya, Nonaktifkan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

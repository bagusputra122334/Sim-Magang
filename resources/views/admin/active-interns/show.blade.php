@extends('layouts.admin')

@section('title', 'Detail Peserta Magang — ' . ($intern->user?->name ?? ''))

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-person-bounding-box text-primary me-2"></i>Detail Peserta Magang
            </h1>
            <p class="text-muted mb-0">Informasi bio peserta, alokasi posisi, pembimbing lapangan, dan kontrol status magang.</p>
        </div>
        <a href="{{ route('admin.active-interns.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Status Magang
        </a>
    </div>

    <div class="row g-4">
        {{-- Left Column: Bio & Internship Overview --}}
        <div class="col-lg-8">
            {{-- Status Card --}}
            <div class="card border shadow-sm mb-4">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-circle bg-light text-primary">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Status Operasional Magang</span>
                            <span class="badge {{ $intern->operational_status_badge_class }} rounded-pill px-3 py-1.5 fs-6 mt-1">
                                {{ $intern->operational_status_label }}
                            </span>
                        </div>
                    </div>
                    <div>
                        @if(!$intern->is_terminated)
                            <button type="button" class="btn btn-danger-subtle text-danger border border-danger-subtle rounded-3 px-3.5 py-2 fw-semibold shadow-sm hover-btn-danger transition-all d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#deactivateModal">
                                <i class="bi bi-person-x-fill fs-6"></i>
                                <span>Nonaktifkan Magang Peserta</span>
                            </button>
                        @else
                            <form method="POST" action="{{ route('admin.active-interns.toggle-status', $intern->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success-subtle text-success border border-success-subtle rounded-3 px-3.5 py-2 fw-semibold shadow-sm hover-btn-success transition-all d-inline-flex align-items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali status magang peserta ini?')">
                                    <i class="bi bi-check-circle-fill fs-6"></i>
                                    <span>Aktifkan Kembali Magang</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @if($intern->is_terminated && $intern->catatan_penonaktifan)
                    <div class="card-footer bg-danger-subtle border-top border-danger-subtle p-3">
                        <small class="text-danger fw-bold d-block mb-1"><i class="bi bi-exclamation-octagon me-1"></i> Catatan Penonaktifan Admin:</small>
                        <p class="text-dark mb-0 small">{{ $intern->catatan_penonaktifan }}</p>
                        <small class="text-muted d-block mt-1">Waktu Penonaktifan: {{ $intern->terminated_at?->translatedFormat('d F Y, H:i') }} WIB</small>
                    </div>
                @endif
            </div>

            {{-- Bio Data Card --}}
            @php $prof = $intern->user?->profile; @endphp
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-vcard text-primary me-2"></i>Biodata Peserta Magang
                    </h5>
                </div>
                <div class="card-body p-4">
                    <dl class="row mb-0 g-3">
                        <dt class="col-sm-4 text-muted fw-normal">Nama Lengkap</dt>
                        <dd class="col-sm-8 fw-bold text-dark mb-0">{{ $intern->user?->name ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Alamat Email</dt>
                        <dd class="col-sm-8 text-body font-monospace mb-0">{{ $intern->user?->email ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Kategori Peserta</dt>
                        <dd class="col-sm-8 mb-0">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                {{ $prof?->isSiswa() ? 'Siswa / SMK' : 'Mahasiswa Perguruan Tinggi' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal">Asal Instansi / Sekolah</dt>
                        <dd class="col-sm-8 fw-semibold text-dark mb-0">{{ $prof?->instansi ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Jurusan / Program Studi</dt>
                        <dd class="col-sm-8 text-body mb-0">{{ $prof?->jurusan ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">NIM / NIS</dt>
                        <dd class="col-sm-8 font-monospace mb-0">{{ $prof?->nim_nis ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Nomor HP / WhatsApp</dt>
                        <dd class="col-sm-8 font-monospace text-dark mb-0">{{ $prof?->no_hp ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Right Column: Internship Placement & Mentor --}}
        <div class="col-lg-4">
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-briefcase text-primary me-2"></i>Informasi Posisi & Unit
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <span class="text-muted small d-block">Posisi Magang</span>
                        <span class="fw-bold text-primary fs-6">{{ $intern->position?->nama_posisi ?? '—' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block">Nomor Pendaftaran</span>
                        <code class="fs-6 font-monospace">{{ $intern->nomor_pendaftaran }}</code>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block">Periode Pelaksanaan</span>
                        <div class="fw-bold text-dark mt-0.5">
                            {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                            <span class="text-muted mx-1">s/d</span>
                            {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <hr class="my-3 text-muted">

                    <div class="mb-0">
                        <span class="text-muted small d-block mb-1">Pembimbing Lapangan (Mentor)</span>
                        @if($intern->position?->mentor_name)
                            <div class="fw-bold text-dark">{{ $intern->position->mentor_name }}</div>
                            @if($intern->position->mentor_nip)
                                <small class="text-muted font-monospace d-block">NIP: {{ $intern->position->mentor_nip }}</small>
                            @endif
                        @else
                            <span class="text-muted font-italic">— Belum Ditentukan —</span>
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
                <div class="modal-content border-0 shadow">
                    <form method="POST" action="{{ route('admin.active-interns.toggle-status', $intern->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-header-title h6 mb-0 text-white fw-bold" id="deactivateModalLabel">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Konfirmasi Penonaktifan Magang
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="mb-3 text-dark">
                                Anda akan menonaktifkan status magang peserta <strong>{{ $intern->user?->name }}</strong>. Status operasional akan berubah menjadi <code>Dinonaktifkan / Berhenti</code>.
                            </p>
                            <div class="mb-3">
                                <label for="catatan_penonaktifan" class="form-label fw-semibold">Alasan / Catatan Penonaktifan (Opsional)</label>
                                <textarea name="catatan_penonaktifan" id="catatan_penonaktifan" rows="3" class="form-control" placeholder="Contoh: Mengundurkan diri / Pelanggaran aturan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Nonaktifkan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

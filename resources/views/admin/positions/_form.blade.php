@php
    $formMode = $formMode ?? 'create';
    $isEdit = $formMode === 'edit';
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Nama Posisi --}}
        <div class="mb-4">
            <label for="nama_posisi" class="form-label fw-semibold">
                Nama Posisi Magang <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                name="nama_posisi"
                id="nama_posisi"
                class="form-control form-control-lg @error('nama_posisi') is-invalid @enderror"
                maxlength="100"
                placeholder="Contoh: Web Developer Backend Laravel"
                value="{{ old('nama_posisi', $position->nama_posisi ?? '') }}"
                required
            >
            <div class="form-text">Maksimal 100 karakter. Nama ini akan ditampilkan di halaman pendaftaran peserta.</div>
            @error('nama_posisi')
                <div class="invalid-feedback"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Slug URL --}}
        <div class="mb-4">
            <label for="slug" class="form-label fw-semibold">
                Slug URL <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted">simang.id/posisi/</span>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control @error('slug') is-invalid @enderror"
                    maxlength="150"
                    placeholder="web-developer-backend-laravel"
                    pattern="[a-z0-9\-_]+"
                    value="{{ old('slug', $position->slug ?? '') }}"
                    required
                >
            </div>
            <div class="form-text">
                Hanya huruf kecil, angka, tanda hubung (-), dan underscore (_).
                Contoh: <code>ui-ux-designer</code>
            </div>
            @error('slug')
                <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="mb-4">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi & Tugas</label>
            <textarea
                name="deskripsi"
                id="deskripsi"
                rows="6"
                maxlength="5000"
                class="form-control @error('deskripsi') is-invalid @enderror"
                placeholder="Jelaskan deskripsi pekerjaan, tugas pokok, dll..."
            >{{ old('deskripsi', $position->deskripsi ?? '') }}</textarea>
            <div class="form-text d-flex justify-content-between">
                <span>Opsional. Maksimal 5000 karakter.</span>
                <span class="text-muted small"><span id="deskripsi_count">0</span>/5000</span>
            </div>
            @error('deskripsi')
                <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Kualifikasi --}}
        <div class="mb-4">
            <label for="kualifikasi" class="form-label fw-semibold">Kualifikasi & Persyaratan</label>
            <textarea
                name="kualifikasi"
                id="kualifikasi"
                rows="5"
                maxlength="5000"
                class="form-control @error('kualifikasi') is-invalid @enderror"
                placeholder="Persyaratan: - Mahasiswa Semester 5+, - Menguasai HTML, CSS, JS, dll..."
            >{{ old('kualifikasi', $position->kualifikasi ?? '') }}</textarea>
            <div class="form-text d-flex justify-content-between">
                <span>Opsional. Maksimal 5000 karakter.</span>
                <span class="text-muted small"><span id="kualifikasi_count">0</span>/5000</span>
            </div>
            @error('kualifikasi')
                <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Pembimbing Lapangan --}}
        <div class="card border-0 bg-light mb-4">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0 text-primary small text-uppercase">
                    <i class="bi bi-person-badge-fill me-1"></i> Pembimbing Lapangan (Mentor Unit)
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="mentor_name" class="form-label fw-semibold mb-1">
                            Nama Pembimbing Lapangan
                        </label>
                        <input
                            type="text"
                            name="mentor_name"
                            id="mentor_name"
                            class="form-control @error('mentor_name') is-invalid @enderror"
                            maxlength="255"
                            placeholder="Contoh: Drs. Eko Prasetyo, M.Kom"
                            value="{{ old('mentor_name', $position->mentor_name ?? '') }}"
                        >
                        <div class="form-text">Nama lengkap beserta gelar pembimbing teknis magang.</div>
                        @error('mentor_name')
                            <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label for="mentor_nip" class="form-label fw-semibold mb-1">
                            NIP Pembimbing Lapangan
                        </label>
                        <input
                            type="text"
                            name="mentor_nip"
                            id="mentor_nip"
                            class="form-control @error('mentor_nip') is-invalid @enderror"
                            maxlength="30"
                            placeholder="19820315 200801 1 004"
                            value="{{ old('mentor_nip', $position->mentor_nip ?? '') }}"
                        >
                        <div class="form-text">NIP / Nomor Induk Pegawai Pembimbing.</div>
                        @error('mentor_nip')
                            <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Kanan: Pengaturan --}}
    <div class="col-lg-4">
        <div class="card border-0 bg-light mb-4">
            <div class="card-header border-0 bg-transparent pt-4 pb-2">
                <h6 class="fw-bold mb-0 text-uppercase text-muted small">
                    <i class="bi bi-gear-wide-connected me-1"></i> Pengaturan
                </h6>
            </div>
            <div class="card-body">
                {{-- Kuota (Tidak Diterapkan Pembatasan Kuota) --}}
                <input type="hidden" name="kuota" value="{{ old('kuota', $position->kuota ?? 0) }}">

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Status Posisi <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex flex-column gap-2">
                        @foreach ($statusOptions as $status)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="status"
                                    id="status_{{ $status->value }}"
                                    value="{{ $status->value }}"
                                    @checked(old('status', $position->status?->value ?? \App\Enums\PositionStatus::Aktif->value) === $status->value)
                                    required
                                >
                                <label class="form-check-label fw-medium" for="status_{{ $status->value }}">
                                    @if ($status->isAktif())
                                        <span class="text-success">
                                            <i class="bi bi-toggle-on me-1"></i>
                                        @else
                                        <span class="text-secondary">
                                            <i class="bi bi-toggle-off me-1"></i>
                                        @endif
                                        {{ $status->label() }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text">
                        Posisi <strong>Aktif</strong> tampil di halaman pendaftaran peserta.
                        <strong>Tidak Aktif</strong> disembunyikan.
                    </div>
                    @error('status')
                        <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                    @enderror
                </div>

                @if($isEdit)
                    {{-- Riwayat Perubahan --}}
                    <div class="card border-0 shadow-sm bg-white mb-4">
                        <div class="card-header bg-light border-bottom py-3">
                            <h6 class="fw-bold mb-0 text-dark small text-uppercase">
                                <i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Perubahan
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Terakhir diperbarui</small>
                                <span class="fw-bold text-dark fs-6">{{ $position->updated_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Dibuat Pada</small>
                                <span class="fw-medium text-secondary small">{{ $position->created_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($isEdit)
            <div class="card border-0 shadow-sm bg-danger text-white mb-4">
                <div class="card-body p-4 text-center">
                    <div class="mb-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-1" aria-hidden="true"></i>
                    </div>
                    <h6 class="fw-bold text-white mb-2 fs-5">Hapus Posisi?</h6>
                    <p class="text-white small mb-3">
                        Posisi yang sudah punya pendaftar tidak bisa dihapus (ON DELETE RESTRICT).
                    </p>
                    <button type="submit" form="delete-position-form" class="btn btn-light text-danger fw-bold btn-sm px-4 py-2 shadow-sm">
                        <i class="bi bi-trash3 me-1" aria-hidden="true"></i> Hapus Posisi Ini
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        // Character counter
        const desc = document.getElementById('deskripsi');
        const kual = document.getElementById('kualifikasi');
        const updateCount = (el, target) => {
            const t = document.getElementById(target);
            if (t && el) t.textContent = el.value.length;
        };
        if (desc) { desc.addEventListener('input', () => updateCount(desc, 'deskripsi_count')); updateCount(desc, 'deskripsi_count'); }
        if (kual) { kual.addEventListener('input', () => updateCount(kual, 'kualifikasi_count')); updateCount(kual, 'kualifikasi_count'); }

        // Simple slug auto-generation (hanya di create, biarkan user edit setelahnya)
        const nama = document.getElementById('nama_posisi');
        const slug = document.getElementById('slug');
        @if(!$isEdit && empty(old('slug')) && empty($position->slug ?? ''))
            if (nama && slug) {
                const toSlug = (s) => s.toString().toLowerCase().trim()
                    .replace(/[^a-z0-9\s-_]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-').slice(0, 150);
                nama.addEventListener('input', () => {
                    if (slug.dataset.manual === 'true') return;
                    slug.value = toSlug(nama.value);
                    slug.dispatchEvent(new Event('input'));
                });
                slug.addEventListener('input', () => { slug.dataset.manual = slug.value.length > 0 ? 'true' : 'false'; });
            }
        @endif
    </script>
@endpush

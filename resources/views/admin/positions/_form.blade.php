@php
    $formMode = $formMode ?? 'create';
    $isEdit = $formMode === 'edit';
@endphp

<div class="row g-2.5">
    <div class="col-lg-8">
        {{-- Nama Posisi --}}
        <div class="mb-3">
            <label for="nama_posisi" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200">
                Nama Posisi Magang <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                name="nama_posisi"
                id="nama_posisi"
                class="form-control py-1.5 px-2.5 text-xs @error('nama_posisi') is-invalid @enderror"
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
        <div class="mb-3">
            <label for="slug" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200">
                Slug URL <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted">simang.id/posisi/</span>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control py-1.5 px-2.5 text-xs @error('slug') is-invalid @enderror"
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
        <div class="mb-3">
            <label for="deskripsi" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Deskripsi & Tugas</label>
            <textarea
                name="deskripsi"
                id="deskripsi"
                rows="6"
                maxlength="5000"
                class="form-control py-1.5 px-2.5 text-xs @error('deskripsi') is-invalid @enderror"
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

        {{-- Target Pendaftar & Kualifikasi Berdasarkan Jenjang --}}
        <div class="card border border-slate-200 dark:border-slate-700 rounded-xl mb-3 shadow-2xs overflow-hidden">
            <div class="card-header bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 py-2.5 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 text-slate-800 dark:text-slate-100 text-xs text-uppercase d-flex align-items-center gap-1.5">
                        <i class="bi bi-mortarboard-fill text-primary"></i> Target Pendaftar &amp; Kualifikasi Jenjang
                    </h6>
                    <div class="d-flex align-items-center gap-2">
                        <label for="tipe_pendaftar" class="text-xs text-slate-600 dark:text-slate-300 font-bold mb-0">Terbuka Untuk:</label>
                        <select name="tipe_pendaftar" id="tipe_pendaftar" class="form-select form-select-sm py-1.5 ps-2.5 pe-4 text-xs font-bold text-blue-700 bg-white border border-blue-300 dark:bg-slate-800 dark:text-blue-300 dark:border-blue-600 rounded-md shadow-2xs" style="width: auto; min-width: 210px;">
                            <option value="all" {{ old('tipe_pendaftar', $position->tipe_pendaftar ?? 'all') === 'all' ? 'selected' : '' }}>Mahasiswa &amp; Siswa SMK</option>
                            <option value="mahasiswa" {{ old('tipe_pendaftar', $position->tipe_pendaftar ?? 'all') === 'mahasiswa' ? 'selected' : '' }}>Khusus Mahasiswa</option>
                            <option value="siswa" {{ old('tipe_pendaftar', $position->tipe_pendaftar ?? 'all') === 'siswa' ? 'selected' : '' }}>Khusus Siswa (SMK)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <!-- Nav Tabs untuk Kualifikasi Siswa & Mahasiswa -->
                <style>
                    #kualifikasiTabs .nav-link {
                        color: #334155 !important;
                        background-color: #ffffff !important;
                        border: 1px solid #cbd5e1 !important;
                        font-weight: 700 !important;
                        transition: all 0.2s ease !important;
                    }
                    #kualifikasiTabs .nav-link:hover {
                        color: #1d4ed8 !important;
                        background-color: #eff6ff !important;
                        border-color: #93c5fd !important;
                    }
                    #kualifikasiTabs .nav-link.active {
                        color: #ffffff !important;
                        background-color: #2563eb !important;
                        border-color: #2563eb !important;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.15) !important;
                    }
                    html[data-theme="dark"] #kualifikasiTabs .nav-link {
                        color: #e2e8f0 !important;
                        background-color: #1e293b !important;
                        border-color: #475569 !important;
                    }
                    html[data-theme="dark"] #kualifikasiTabs .nav-link.active {
                        color: #ffffff !important;
                        background-color: #3b82f6 !important;
                        border-color: #3b82f6 !important;
                    }
                </style>
                <ul class="nav nav-pills nav-fill gap-2 mb-3 bg-slate-100 dark:bg-slate-700/50 p-1.5 rounded-xl text-xs border border-slate-200 dark:border-slate-600" id="kualifikasiTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-2 px-3 rounded-lg fw-bold d-flex align-items-center justify-content-center gap-1.5" id="tab-mahasiswa-btn" data-bs-toggle="pill" data-bs-target="#tab-mahasiswa" type="button" role="tab" aria-controls="tab-mahasiswa" aria-selected="true">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span>Kualifikasi Mahasiswa</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-2 px-3 rounded-lg fw-bold d-flex align-items-center justify-content-center gap-1.5" id="tab-siswa-btn" data-bs-toggle="pill" data-bs-target="#tab-siswa" type="button" role="tab" aria-controls="tab-siswa" aria-selected="false">
                            <i class="bi bi-backpack-fill"></i>
                            <span>Kualifikasi Siswa (SMK)</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-2 px-3 rounded-lg fw-bold d-flex align-items-center justify-content-center gap-1.5" id="tab-umum-btn" data-bs-toggle="pill" data-bs-target="#tab-umum" type="button" role="tab" aria-controls="tab-umum" aria-selected="false">
                            <i class="bi bi-card-text"></i>
                            <span>Umum / Tambahan</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="kualifikasiTabsContent">
                    <!-- Tab 1: Kualifikasi Mahasiswa -->
                    <div class="tab-pane fade show active" id="tab-mahasiswa" role="tabpanel" aria-labelledby="tab-mahasiswa-btn">
                        <label for="kualifikasi_mahasiswa" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 d-flex align-items-center gap-1 mb-1">
                            <i class="bi bi-mortarboard text-primary"></i> Persyaratan Khusus Mahasiswa (D3 / D4 / S1)
                        </label>
                        <textarea
                            name="kualifikasi_mahasiswa"
                            id="kualifikasi_mahasiswa"
                            rows="4"
                            maxlength="5000"
                            class="form-control py-1.5 px-2.5 text-xs @error('kualifikasi_mahasiswa') is-invalid @enderror"
                            placeholder="Contoh:&#10;- Mahasiswa aktif minimal Semester 5&#10;- Jurusan Teknik Informatika, Sistem Informasi, atau serumpun&#10;- Menguasai dasar pemrograman web (PHP / Laravel / JS)&#10;- Memiliki portofolio atau projek yang pernah dibuat"
                        >{{ old('kualifikasi_mahasiswa', $position->kualifikasi_mahasiswa ?? '') }}</textarea>
                        <div class="form-text text-[11px] text-slate-500">Tuliskan persyaratan akademik, semester, dan kompetensi teknis bagi mahasiswa.</div>
                        @error('kualifikasi_mahasiswa')
                            <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tab 2: Kualifikasi Siswa SMK -->
                    <div class="tab-pane fade" id="tab-siswa" role="tabpanel" aria-labelledby="tab-siswa-btn">
                        <label for="kualifikasi_siswa" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 d-flex align-items-center gap-1 mb-1">
                            <i class="bi bi-backpack text-primary"></i> Persyaratan Khusus Siswa (SMK)
                        </label>
                        <textarea
                            name="kualifikasi_siswa"
                            id="kualifikasi_siswa"
                            rows="4"
                            maxlength="5000"
                            class="form-control py-1.5 px-2.5 text-xs @error('kualifikasi_siswa') is-invalid @enderror"
                            placeholder="Contoh:&#10;- Siswa aktif SMK Kelas XI atau XII&#10;- Kompetensi Keahlian: Rekayasa Perangkat Lunak (RPL) / TKJ / Multimedia&#10;- Membawa surat permohonan PKL / Magang dari pihak sekolah&#10;- Memiliki motivasi belajar dan disiplin tinggi"
                        >{{ old('kualifikasi_siswa', $position->kualifikasi_siswa ?? '') }}</textarea>
                        <div class="form-text text-[11px] text-slate-500">Tuliskan kualifikasi jurusan SMK, tingkatan kelas, dan berkas dari sekolah.</div>
                        @error('kualifikasi_siswa')
                            <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tab 3: Umum / Cadangan -->
                    <div class="tab-pane fade" id="tab-umum" role="tabpanel" aria-labelledby="tab-umum-btn">
                        <label for="kualifikasi" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 d-flex align-items-center gap-1 mb-1">
                            <i class="bi bi-card-text text-primary"></i> Catatan Persyaratan Umum (Opsional)
                        </label>
                        <textarea
                            name="kualifikasi"
                            id="kualifikasi"
                            rows="4"
                            maxlength="5000"
                            class="form-control py-1.5 px-2.5 text-xs @error('kualifikasi') is-invalid @enderror"
                            placeholder="Syarat umum yang berlaku untuk semua pendaftar, misal: sehat jasmani rohani, mematuhi tata tertib OPD, dll..."
                        >{{ old('kualifikasi', $position->kualifikasi ?? '') }}</textarea>
                        <div class="form-text text-[11px] text-slate-500">Jika tab Mahasiswa / Siswa diisi, sistem akan otomatis merangkum persyaratan ini.</div>
                        @error('kualifikasi')
                            <div class="invalid-feedback d-block"><i class="bi bi-x-circle me-1"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Pembimbing Lapangan --}}
        <div class="card border-0 bg-light mb-3">
            <div class="card-header bg-transparent border-0 pt-2.5 pb-1">
                <h6 class="fw-bold mb-0 text-primary small text-uppercase">
                    <i class="bi bi-person-badge-fill me-1"></i> Pembimbing Lapangan (Mentor Unit)
                </h6>
            </div>
            <div class="card-body p-3 sm:p-4">
                <div class="row g-2.5">
                    <div class="col-md-7">
                        <label for="mentor_name" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            Nama Pembimbing Lapangan
                        </label>
                        <input
                            type="text"
                            name="mentor_name"
                            id="mentor_name"
                            class="form-control py-1.5 px-2.5 text-xs @error('mentor_name') is-invalid @enderror"
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
                        <label for="mentor_nip" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            NIP Pembimbing Lapangan
                        </label>
                        <input
                            type="text"
                            name="mentor_nip"
                            id="mentor_nip"
                            class="form-control py-1.5 px-2.5 text-xs @error('mentor_nip') is-invalid @enderror"
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
        <div class="card border-0 bg-light mb-3">
            <div class="card-header border-0 bg-transparent pt-2.5 pb-1.5">
                <h6 class="fw-bold mb-0 text-uppercase text-muted small">
                    <i class="bi bi-gear-wide-connected me-1"></i> Pengaturan
                </h6>
            </div>
            <div class="card-body p-3 sm:p-4">
                {{-- Kuota (Tidak Diterapkan Pembatasan Kuota) --}}
                <input type="hidden" name="kuota" value="{{ old('kuota', $position->kuota ?? 0) }}">

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200">
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
                                <label class="form-check-label fw-medium text-xs" for="status_{{ $status->value }}">
                                    @if ($status->isAktif())
                                        <span class="text-success font-semibold">
                                            <i class="bi bi-toggle-on me-1"></i>
                                        @else
                                        <span class="text-secondary font-semibold">
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
                    <div class="card border-0 shadow-sm bg-white mb-3">
                        <div class="card-header bg-light border-bottom py-2 px-3">
                            <h6 class="fw-bold mb-0 text-dark small text-uppercase">
                                <i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Perubahan
                            </h6>
                        </div>
                        <div class="card-body p-2.5">
                            <div class="mb-2">
                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Terakhir diperbarui</small>
                                <span class="fw-bold text-dark text-xs">{{ $position->updated_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Dibuat Pada</small>
                                <span class="fw-medium text-secondary text-xs">{{ $position->created_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }} WIB</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($isEdit)
            <div class="card border-0 shadow-sm bg-danger text-white mb-3">
                <div class="card-body p-3 text-center">
                    <div class="mb-1">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-3" aria-hidden="true"></i>
                    </div>
                    <h6 class="fw-bold text-white mb-1 fs-6">Hapus Posisi?</h6>
                    <p class="text-white small mb-2.5 text-xs">
                        Posisi yang sudah punya pendaftar tidak bisa dihapus (ON DELETE RESTRICT).
                    </p>
                    <button type="submit" form="delete-position-form" class="btn bg-white hover:bg-rose-50 text-rose-600 font-bold rounded-lg px-3 py-1.5 text-xs shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
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

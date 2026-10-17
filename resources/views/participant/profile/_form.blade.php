@php
    $typeInput = old('participant_type', $profile->participant_type->value ?? $participantType ?? 'university');
    $normalizedType = in_array(strtolower((string) $typeInput), ['student', 'siswa'], true) ? 'student' : 'university';
@endphp

<div x-data="{ type: '{{ $normalizedType }}' }">
    {{-- Participant Type Selector Pill --}}
    <div class="card border border-primary-subtle bg-primary bg-opacity-10 mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                <i class="bi bi-person-gear me-1"></i> Kategori Peserta Magang
            </label>
            <div class="d-flex flex-wrap gap-2">
                <div class="form-check form-check-inline bg-white px-2.5 py-1.5 rounded-2 border shadow-sm flex-grow-1">
                    <input class="form-check-input me-1.5" type="radio" name="participant_type" id="typeMahasiswa"
                           value="university" x-model="type" @checked($normalizedType === 'university')>
                    <label class="form-check-label text-xs font-bold text-slate-800 cursor-pointer" for="typeMahasiswa">
                        🎓 Mahasiswa (Perguruan Tinggi)
                    </label>
                </div>
                <div class="form-check form-check-inline bg-white px-2.5 py-1.5 rounded-2 border shadow-sm flex-grow-1">
                    <input class="form-check-input me-1.5" type="radio" name="participant_type" id="typeSiswa"
                           value="student" x-model="type" @checked($normalizedType === 'student')>
                    <label class="form-check-label text-xs font-bold text-slate-800 cursor-pointer" for="typeSiswa">
                        🏫 Siswa / SMK (Sekolah Menengah)
                    </label>
                </div>
            </div>
            @error('participant_type')
                <div class="text-danger text-xs mt-1 fw-bold">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Form Section 1: Identitas Diri --}}
    <div class="card border shadow-sm rounded-2 mb-3">
        <div class="card-header bg-light border-0 py-2 px-3">
            <h5 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                <i class="bi bi-person-vcard text-primary me-1.5"></i>Data Identitas Diri
            </h5>
        </div>
        <div class="card-body p-3 sm:p-4">
            <div class="row g-2.5">
                <div class="col-md-6">
                    <label for="nama_lengkap" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('nama_lengkap') is-invalid @enderror"
                           value="{{ old('nama_lengkap', $profile->nama_lengkap ?? $user->name ?? '') }}" required placeholder="Nama sesuai KTP/KTM/Kartu Pelajar">
                    @error('nama_lengkap') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="nik" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">NIK (16 Digit)</label>
                    <input type="text" name="nik" id="nik"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('nik') is-invalid @enderror"
                           value="{{ old('nik', $profile->nik ?? '') }}" maxlength="16" required placeholder="Nomor Induk Kependudukan (16 angka)">
                    @error('nik') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="tempat_lahir" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('tempat_lahir') is-invalid @enderror"
                           value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}" required placeholder="Contoh: Tuban">
                    @error('tempat_lahir') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir', optional($profile->tanggal_lahir ?? null)->format('Y-m-d')) }}" required>
                    @error('tanggal_lahir') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select rounded-2 py-1.5 px-2.5 text-xs @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        @foreach (\App\Enums\JenisKelamin::cases() as $jk)
                            <option value="{{ $jk->value }}" @selected(old('jenis_kelamin', $profile->jenis_kelamin->value ?? '') === $jk->value)>
                                {{ $jk->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section 2: Dynamic Data Pendidikan / Institusi --}}
    <div class="card border shadow-sm rounded-2 mb-3">
        <div class="card-header bg-light border-0 py-2 px-3 d-flex align-items-center justify-content-between">
            <h5 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                <i class="bi bi-mortarboard text-primary me-1.5"></i>
                <span x-text="(type === 'student' || type === 'siswa') ? 'Data Sekolah / Pendidikan' : 'Data Perguruan Tinggi'"></span>
            </h5>
            <span class="badge bg-primary rounded-pill px-2 py-0.5 text-xs" x-text="(type === 'student' || type === 'siswa') ? 'Kategori: Siswa / SMK' : 'Kategori: Mahasiswa'"></span>
        </div>
        <div class="card-body p-3 sm:p-4">
            <div class="row g-2.5">
                {{-- Nama Institusi / Sekolah --}}
                <div class="col-md-6">
                    <label for="institusi" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required"
                           x-text="(type === 'student' || type === 'siswa') ? 'Nama Sekolah (SMA / SMK / MA)' : 'Universitas / Perguruan Tinggi'"></label>
                    <input type="text" name="institusi" id="institusi"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('institusi') is-invalid @enderror"
                           value="{{ old('institusi', $profile->institusi ?? '') }}" required
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: SMKN 1 Tuban' : 'Contoh: Universitas Negeri Surabaya'">
                    @error('institusi') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                {{-- NIS / NIM Field --}}
                <div class="col-md-6">
                    <label for="nis_nim" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5"
                           :class="(type === 'student' || type === 'siswa') ? '' : 'required'"
                           x-text="(type === 'student' || type === 'siswa') ? 'NIS / NISN (Opsional)' : 'NIM (Nomor Induk Mahasiswa)'"></label>
                    <input type="text" name="nis_nim" id="nis_nim"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('nis_nim') is-invalid @enderror @error('nim') is-invalid @enderror"
                           value="{{ old('nis_nim', $profile->nis_nim ?? $profile->nim ?? '') }}"
                           :required="type === 'university' || type === 'mahasiswa'"
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: 22001231 (Opsional)' : 'Contoh: 240509074004'">
                    @error('nis_nim') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                    @error('nim') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                {{-- Jurusan / Program Studi --}}
                <div class="col-md-6">
                    <label for="jurusan" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required"
                           x-text="(type === 'student' || type === 'siswa') ? 'Jurusan / Program Keahlian' : 'Program Studi / Jurusan'"></label>
                    <input type="text" name="jurusan" id="jurusan"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('jurusan') is-invalid @enderror"
                           value="{{ old('jurusan', $profile->jurusan ?? '') }}" required
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: Teknik Komputer & Jaringan' : 'Contoh: Teknik Informatika'">
                    @error('jurusan') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                {{-- Semester (hanya Mahasiswa) --}}
                <div class="col-md-3" x-show="type === 'university' || type === 'mahasiswa'" x-transition>
                    <label for="semester" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Semester</label>
                    <input type="number" name="semester" id="semester"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('semester') is-invalid @enderror"
                           value="{{ old('semester', $profile->semester ?? '') }}" min="1" max="14"
                           :required="type === 'university' || type === 'mahasiswa'" placeholder="Contoh: 6">
                    @error('semester') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                {{-- Tahun Angkatan --}}
                <div class="col-md-3">
                    <label for="tahun_angkatan" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Tahun Angkatan</label>
                    <input type="text" name="tahun_angkatan" id="tahun_angkatan"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('tahun_angkatan') is-invalid @enderror"
                           value="{{ old('tahun_angkatan', $profile->tahun_angkatan ?? date('Y')) }}" required placeholder="Contoh: 2023">
                    @error('tahun_angkatan') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section 3: Kontak & Alamat --}}
    <div class="card border shadow-sm rounded-2 mb-3">
        <div class="card-header bg-light border-0 py-2 px-3">
            <h5 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                <i class="bi bi-geo-alt text-primary me-1.5"></i>Kontak & Alamat Tempat Tinggal
            </h5>
        </div>
        <div class="card-body p-3 sm:p-4">
            <div class="row g-2.5">
                <div class="col-md-6">
                    <label for="no_telepon" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Nomor HP / WhatsApp Active</label>
                    <input type="text" name="no_telepon" id="no_telepon"
                           class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('no_telepon') is-invalid @enderror"
                           value="{{ old('no_telepon', $profile->no_telepon ?? '') }}" required placeholder="Contoh: 081234567890">
                    @error('no_telepon') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6" x-data="{ fileName: null }">
                    <label for="foto" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Foto Profil (Opsional, max 2MB)</label>
                    <div class="relative border border-dashed rounded-lg p-2 transition-all duration-200"
                         :class="fileName ? 'border-emerald-500 bg-emerald-50/50 shadow-sm' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'">
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                                @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('foto') is-invalid @enderror">
                        <div x-show="!fileName" class="text-center py-0.5">
                            <i class="bi bi-image text-emerald-600 text-lg mb-0.5 block"></i>
                            <span class="font-bold text-gray-700 text-xs block">Klik atau seret foto ke sini</span>
                            <span class="text-muted text-[11px]">JPEG, PNG, JPG (Maks. 2MB)</span>
                        </div>
                        <div x-show="fileName" class="d-flex align-items-center gap-2 py-0.5" x-cloak>
                            <div class="rounded-circle bg-emerald-600 text-white p-1 d-flex align-items-center justify-content-center">
                                <i class="bi bi-check-lg text-xs"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="text-[11px] text-emerald-700 font-bold">Foto Terpilih:</div>
                                <div class="text-xs font-bold text-gray-900 text-truncate" x-text="fileName"></div>
                            </div>
                        </div>
                    </div>
                    @error('foto') <div class="invalid-feedback d-block text-xs mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="alamat" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 required">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="2.5"
                              class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('alamat') is-invalid @enderror"
                              required placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kabupaten/Kota">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    @error('alamat') <div class="invalid-feedback text-xs">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

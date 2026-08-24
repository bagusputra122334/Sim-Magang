@php
    $typeInput = old('participant_type', $profile->participant_type->value ?? $participantType ?? 'university');
    $normalizedType = in_array(strtolower((string) $typeInput), ['student', 'siswa'], true) ? 'student' : 'university';
@endphp

<div x-data="{ type: '{{ $normalizedType }}' }">
    {{-- Participant Type Selector Pill --}}
    <div class="card border border-primary-subtle bg-primary bg-opacity-10 mb-4 rounded-4">
        <div class="card-body p-4">
            <label class="form-label fw-bold text-primary mb-2">
                <i class="bi bi-person-gear me-1"></i> Kategori Peserta Magang
            </label>
            <div class="d-flex flex-wrap gap-3">
                <div class="form-check form-check-inline bg-white px-3 py-2 rounded-3 border shadow-sm flex-grow-1">
                    <input class="form-check-input me-2" type="radio" name="participant_type" id="typeMahasiswa"
                           value="university" x-model="type" @checked($normalizedType === 'university')>
                    <label class="form-check-label fw-semibold" for="typeMahasiswa">
                        🎓 Mahasiswa (Perguruan Tinggi)
                    </label>
                </div>
                <div class="form-check form-check-inline bg-white px-3 py-2 rounded-3 border shadow-sm flex-grow-1">
                    <input class="form-check-input me-2" type="radio" name="participant_type" id="typeSiswa"
                           value="student" x-model="type" @checked($normalizedType === 'student')>
                    <label class="form-check-label fw-semibold" for="typeSiswa">
                        🏫 Siswa / SMK (Sekolah Menengah)
                    </label>
                </div>
            </div>
            @error('participant_type')
                <div class="text-danger small mt-2 fw-semibold">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Form Section 1: Identitas Diri --}}
    <div class="card border shadow-sm rounded-4 mb-4">
        <div class="card-header bg-light border-0 py-3 px-4">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-person-vcard text-primary me-2"></i>Data Identitas Diri
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nama_lengkap" class="form-label fw-semibold required">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                           class="form-control @error('nama_lengkap') is-invalid @enderror"
                           value="{{ old('nama_lengkap', $profile->nama_lengkap ?? $user->name ?? '') }}" required placeholder="Nama sesuai KTP/KTM/Kartu Pelajar">
                    @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="nik" class="form-label fw-semibold required">NIK (16 Digit)</label>
                    <input type="text" name="nik" id="nik"
                           class="form-control @error('nik') is-invalid @enderror"
                           value="{{ old('nik', $profile->nik ?? '') }}" maxlength="16" required placeholder="Nomor Induk Kependudukan (16 angka)">
                    @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="tempat_lahir" class="form-label fw-semibold required">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                           class="form-control @error('tempat_lahir') is-invalid @enderror"
                           value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}" required placeholder="Contoh: Tuban">
                    @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label fw-semibold required">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                           class="form-control @error('tanggal_lahir') is-invalid @enderror"
                           value="{{ old('tanggal_lahir', optional($profile->tanggal_lahir ?? null)->format('Y-m-d')) }}" required>
                    @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label fw-semibold required">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        @foreach (\App\Enums\JenisKelamin::cases() as $jk)
                            <option value="{{ $jk->value }}" @selected(old('jenis_kelamin', $profile->jenis_kelamin->value ?? '') === $jk->value)>
                                {{ $jk->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section 2: Dynamic Data Pendidikan / Institusi --}}
    <div class="card border shadow-sm rounded-4 mb-4">
        <div class="card-header bg-light border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-mortarboard text-primary me-2"></i>
                <span x-text="(type === 'student' || type === 'siswa') ? 'Data Sekolah / Pendidikan' : 'Data Perguruan Tinggi'"></span>
            </h5>
            <span class="badge bg-primary rounded-pill px-3 py-1.5" x-text="(type === 'student' || type === 'siswa') ? 'Kategori: Siswa / SMK' : 'Kategori: Mahasiswa'"></span>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                {{-- Nama Institusi / Sekolah --}}
                <div class="col-md-6">
                    <label for="institusi" class="form-label fw-semibold required"
                           x-text="(type === 'student' || type === 'siswa') ? 'Nama Sekolah (SMA / SMK / MA)' : 'Universitas / Perguruan Tinggi'"></label>
                    <input type="text" name="institusi" id="institusi"
                           class="form-control @error('institusi') is-invalid @enderror"
                           value="{{ old('institusi', $profile->institusi ?? '') }}" required
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: SMKN 1 Tuban' : 'Contoh: Universitas Negeri Surabaya'">
                    @error('institusi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- NIS / NIM Field --}}
                <div class="col-md-6">
                    <label for="nis_nim" class="form-label fw-semibold"
                           :class="(type === 'student' || type === 'siswa') ? '' : 'required'"
                           x-text="(type === 'student' || type === 'siswa') ? 'NIS / NISN (Opsional)' : 'NIM (Nomor Induk Mahasiswa)'"></label>
                    <input type="text" name="nis_nim" id="nis_nim"
                           class="form-control @error('nis_nim') is-invalid @enderror @error('nim') is-invalid @enderror"
                           value="{{ old('nis_nim', $profile->nis_nim ?? $profile->nim ?? '') }}"
                           :required="type === 'university' || type === 'mahasiswa'"
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: 22001231 (Opsional)' : 'Contoh: 240509074004'">
                    @error('nis_nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Jurusan / Program Studi --}}
                <div class="col-md-6">
                    <label for="jurusan" class="form-label fw-semibold required"
                           x-text="(type === 'student' || type === 'siswa') ? 'Jurusan / Program Keahlian' : 'Program Studi / Jurusan'"></label>
                    <input type="text" name="jurusan" id="jurusan"
                           class="form-control @error('jurusan') is-invalid @enderror"
                           value="{{ old('jurusan', $profile->jurusan ?? '') }}" required
                           :placeholder="(type === 'student' || type === 'siswa') ? 'Contoh: Teknik Komputer & Jaringan' : 'Contoh: Teknik Informatika'">
                    @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Semester (hanya Mahasiswa) --}}
                <div class="col-md-3" x-show="type === 'university' || type === 'mahasiswa'" x-transition>
                    <label for="semester" class="form-label fw-semibold required">Semester</label>
                    <input type="number" name="semester" id="semester"
                           class="form-control @error('semester') is-invalid @enderror"
                           value="{{ old('semester', $profile->semester ?? '') }}" min="1" max="14"
                           :required="type === 'university' || type === 'mahasiswa'" placeholder="Contoh: 6">
                    @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Tahun Angkatan --}}
                <div class="col-md-3">
                    <label for="tahun_angkatan" class="form-label fw-semibold required">Tahun Angkatan</label>
                    <input type="text" name="tahun_angkatan" id="tahun_angkatan"
                           class="form-control @error('tahun_angkatan') is-invalid @enderror"
                           value="{{ old('tahun_angkatan', $profile->tahun_angkatan ?? date('Y')) }}" required placeholder="Contoh: 2023">
                    @error('tahun_angkatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section 3: Kontak & Alamat --}}
    <div class="card border shadow-sm rounded-4 mb-4">
        <div class="card-header bg-light border-0 py-3 px-4">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-geo-alt text-primary me-2"></i>Kontak & Alamat Tempat Tinggal
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="no_telepon" class="form-label fw-semibold required">Nomor HP / WhatsApp Active</label>
                    <input type="text" name="no_telepon" id="no_telepon"
                           class="form-control @error('no_telepon') is-invalid @enderror"
                           value="{{ old('no_telepon', $profile->no_telepon ?? '') }}" required placeholder="Contoh: 081234567890">
                    @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="foto" class="form-label fw-semibold">Foto Profil (Opsional, max 2MB)</label>
                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                           class="form-control @error('foto') is-invalid @enderror">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label for="alamat" class="form-label fw-semibold required">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3"
                              class="form-control @error('alamat') is-invalid @enderror"
                              required placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kabupaten/Kota">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

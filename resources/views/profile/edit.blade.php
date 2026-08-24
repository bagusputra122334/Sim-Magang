@extends(auth()->user()?->isAdmin() ? 'layouts.admin' : 'layouts.participant')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">Pengaturan Akun</h1>
                <p class="text-muted mb-0">Kelola informasi profil akun login dan kata sandi Anda.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Section 1: Information Account --}}
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header border-bottom pb-3 mb-3">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-person-gear me-1" aria-hidden="true"></i>
                        <span>Informasi Akun</span>
                    </h2>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-check-circle me-1"></i> Informasi akun berhasil diperbarui.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Nama Lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($user->isAdmin())
                        <div class="mb-3">
                            <label for="nip" class="form-label fw-semibold">
                                <i class="bi bi-person-vcard text-primary me-1"></i> NIP (Nomor Induk Pegawai)
                            </label>
                            <input type="text" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $user->nip) }}" placeholder="Contoh: 19850722 201001 1 004" maxlength="30">
                            <div class="form-text">NIP Aparatur Sipil Negara / Pengelola Admin Diskominfo Tuban.</div>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position_title" class="form-label fw-semibold">
                                <i class="bi bi-briefcase text-primary me-1"></i> Jabatan / Subtansi Pengelola
                            </label>
                            <input type="text" name="position_title" id="position_title" class="form-control @error('position_title') is-invalid @enderror" value="{{ old('position_title', $user->position_title) }}" placeholder="Contoh: Kepala Bidang Aplikasi & SPBE" maxlength="100">
                            <div class="form-text">Jabatan resmi di lingkungan Dinas Komunikasi dan Informatika Tuban.</div>
                            @error('position_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Section 2: Update Password --}}
        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <div class="panel-header border-bottom pb-3 mb-3">
                    <h2 class="h5 mb-0 section-title">
                        <i class="bi bi-key me-1" aria-hidden="true"></i>
                        <span>Ubah Kata Sandi</span>
                    </h2>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        <i class="bi bi-check-circle me-1"></i> Kata sandi berhasil diperbarui.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label fw-semibold">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="update_password_current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label fw-semibold">Kata Sandi Baru</label>
                        <input type="password" name="password" id="update_password_password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="update_password_password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

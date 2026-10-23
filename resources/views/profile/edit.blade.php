@extends(auth()->user()?->isAdmin() ? 'layouts.admin' : 'layouts.participant')

@section('title', 'Pengaturan Akun')

@section('content')
    <!-- Header Page: Title Tanpa Subtitle -->
    <div class="w-full mb-3">
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 tracking-tight mb-0">Pengaturan Akun</h1>
    </div>

    <div class="row g-2.5">
        {{-- Section 1: Information Account --}}
        <div class="col-12 col-lg-6">
            <div class="panel h-100 p-3 sm:p-4 rounded-2">
                <div class="panel-header border-bottom pb-2 mb-2.5">
                    <h2 class="h6 mb-0 section-title fw-bold">
                        <i class="bi bi-person-gear me-1" aria-hidden="true"></i>
                        <span>Informasi Akun</span>
                    </h2>
                </div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-2.5 py-1.5 px-3 text-xs" role="alert">
                        <i class="bi bi-check-circle me-1"></i> Informasi akun berhasil diperbarui.
                        <button type="button" class="btn-close py-2 px-3" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-2.5">
                        <label for="name" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name" placeholder="Nama Lengkap">
                        @error('name')
                            <div class="invalid-feedback text-xs">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($user->isAdmin())
                        <div class="mb-2.5">
                            <label for="nip" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                <i class="bi bi-person-vcard text-primary me-1"></i> NIP (Nomor Induk Pegawai)
                            </label>
                            <input type="text" name="nip" id="nip" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('nip') is-invalid @enderror" value="{{ old('nip', $user->nip) }}" placeholder="Contoh: 19850722 201001 1 004" maxlength="30">
                            <div class="form-text text-muted text-xs mt-0.5">NIP Aparatur Sipil Negara / Pengelola Admin Diskominfo Tuban.</div>
                            @error('nip')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2.5">
                            <label for="position_title" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                <i class="bi bi-briefcase text-primary me-1"></i> Jabatan / Subtansi Pengelola
                            </label>
                            <input type="text" name="position_title" id="position_title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('position_title') is-invalid @enderror" value="{{ old('position_title', $user->position_title) }}" placeholder="Contoh: Kepala Bidang Aplikasi & SPBE" maxlength="100">
                            <div class="form-text text-muted text-xs mt-0.5">Jabatan resmi di lingkungan Dinas Komunikasi dan Informatika Tuban.</div>
                            @error('position_title')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback text-xs">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold text-xs py-1.5 px-3.5 rounded-2 shadow-sm">
                        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Section 2: Update Password --}}
        <div class="col-12 col-lg-6">
            <div class="panel h-100 p-3 sm:p-4 rounded-2">
                <div class="panel-header border-bottom pb-2 mb-2.5">
                    <h2 class="h6 mb-0 section-title fw-bold">
                        <i class="bi bi-key me-1" aria-hidden="true"></i>
                        <span>Ubah Kata Sandi</span>
                    </h2>
                </div>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-2.5 py-1.5 px-3 text-xs" role="alert">
                        <i class="bi bi-check-circle me-1"></i> Kata sandi berhasil diperbarui.
                        <button type="button" class="btn-close py-2 px-3" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-2.5">
                        <label for="update_password_current_password" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Kata Sandi Saat Ini</label>
                        <div style="position: relative !important; width: 100% !important; display: block !important;" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="current_password" id="update_password_current_password" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('current_password', 'updatePassword') is-invalid @enderror" style="padding-right: 2.5rem !important; box-sizing: border-box !important;" autocomplete="current-password">
                            <button type="button"
                                    @click="show = !show"
                                    style="position: absolute !important; right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10; background: transparent; border: none; cursor: pointer; color: #6c757d; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">
                                <svg x-show="show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.027 10.027 0 014.132-5.411m3.635-1.14A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.027 10.027 0 01-1.413 3.334M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback text-xs">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2.5">
                        <label for="update_password_password" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Kata Sandi Baru</label>
                        <div style="position: relative !important; width: 100% !important; display: block !important;" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password" id="update_password_password" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('password', 'updatePassword') is-invalid @enderror" style="padding-right: 2.5rem !important; box-sizing: border-box !important;" autocomplete="new-password">
                            <button type="button"
                                    @click="show = !show"
                                    style="position: absolute !important; right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10; background: transparent; border: none; cursor: pointer; color: #6c757d; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">
                                <svg x-show="show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.027 10.027 0 014.132-5.411m3.635-1.14A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.027 10.027 0 01-1.413 3.334M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-muted mt-0.5 mb-0">Password minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback text-xs">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <div style="position: relative !important; width: 100% !important; display: block !important;" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" id="update_password_password_confirmation" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('password_confirmation', 'updatePassword') is-invalid @enderror" style="padding-right: 2.5rem !important; box-sizing: border-box !important;" autocomplete="new-password">
                            <button type="button"
                                    @click="show = !show"
                                    style="position: absolute !important; right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10; background: transparent; border: none; cursor: pointer; color: #6c757d; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">
                                <svg x-show="show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!show" style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.027 10.027 0 014.132-5.411m3.635-1.14A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.027 10.027 0 01-1.413 3.334M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback text-xs">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold text-xs py-1.5 px-3.5 rounded-2 shadow-sm">
                        <i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

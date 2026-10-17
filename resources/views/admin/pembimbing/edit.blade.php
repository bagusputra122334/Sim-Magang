@extends('layouts.app')
@section('title', 'Edit Pembimbing')
@section('content')
<div class="container-fluid">
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800 font-bold">Edit Pembimbing Magang</h1>
        <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="alert alert-info border-2 border-info rounded-xl mb-3 p-2.5 d-flex align-items-start" role="alert">
        <i class="bi bi-shield-check fs-4 text-info me-2.5 flex-shrink-0 mt-0.5"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-0.5 text-info text-xs">Data Tetap Aman Saat Diedit</h6>
            <p class="mb-0 text-xs lh-base">
                Mengubah <strong>Nama, NIP, Email, atau Divisi</strong> akun ini <strong>TIDAK AKAN MENGHAPUS / MEMUTUS</strong> hubungan dengan materi, tugas, dan riwayat submission yang pernah dibuat.
                Sistem menggunakan <code>ID angka (pembimbing_id = {{ $pembimbing->id }})</code> sebagai penghubung utama di database.
            </p>
        </div>
    </div>

    <div class="row g-2.5 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-xl">
                <div class="card-body text-center py-2.5 px-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1.5" style="width: 2.5rem; height: 2.5rem;">
                        <i class="bi bi-journal-text fs-5"></i>
                    </div>
                    <div class="h5 fw-bold mb-0 text-primary">{{ $materialsCount ?? 0 }}</div>
                    <div class="text-xs text-muted">Total Materi Dibuat</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-xl">
                <div class="card-body text-center py-2.5 px-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1.5" style="width: 2.5rem; height: 2.5rem;">
                        <i class="bi bi-list-task fs-5"></i>
                    </div>
                    <div class="h5 fw-bold mb-0 text-warning">{{ $tasksCount ?? 0 }}</div>
                    <div class="text-xs text-muted">Total Tugas Dibuat</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-xl">
                <div class="card-body text-center py-2.5 px-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1.5" style="width: 2.5rem; height: 2.5rem;">
                        <i class="bi bi-send-check fs-5"></i>
                    </div>
                    <div class="h5 fw-bold mb-0 text-success">{{ $submissionsCount ?? 0 }}</div>
                    <div class="text-xs text-muted">Total Submission Peserta</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-xl">
                <div class="card-body text-center py-2.5 px-3">
                    <div class="bg-indigo bg-opacity-10 text-indigo rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1.5" style="width: 2.5rem; height: 2.5rem;">
                        <i class="bi bi-key fs-5"></i>
                    </div>
                    <div class="h5 fw-bold mb-0 text-indigo">ID #{{ $pembimbing->id }}</div>
                    <div class="text-xs text-muted">Primary Key Tetap Sama</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.pembimbing.update', $pembimbing->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control py-1.5 px-2.5 text-xs @error('name') is-invalid @enderror" value="{{ old('name', $pembimbing->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control py-1.5 px-2.5 text-xs @error('nip') is-invalid @enderror" value="{{ old('nip', $pembimbing->nip) }}" required>
                    @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Email (Login Akun) <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control py-1.5 px-2.5 text-xs @error('email') is-invalid @enderror" value="{{ old('email', $pembimbing->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text small mt-1 !text-slate-600 dark:!text-slate-300">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Email bisa diganti kapan saja. Username/login otomatis menyesuaikan.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Password <small class="!text-slate-600 dark:!text-slate-300 font-normal">(Kosongkan jika tidak ingin mengubah password)</small></label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordField" class="form-control py-1.5 px-2.5 text-xs @error('password') is-invalid @enderror" placeholder="Masukkan password baru hanya jika ingin diganti..." autocomplete="new-password">
                        <button class="btn btn-outline-secondary py-1.5 px-2.5 text-xs" type="button" id="togglePasswordBtn">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-text small mt-1 !text-slate-600 dark:!text-slate-300">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Jika dibiarkan kosong, password lama <strong>tetap dipertahankan</strong> dan tidak diubah. Minimal 8 karakter jika ingin diganti.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Divisi Penugasan <span class="text-danger">*</span></label>
                    <select name="division_id" class="form-select py-1.5 px-2.5 text-xs @error('division_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('division_id', $pembimbing->division_id) == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm">
                        <i class="bi bi-pencil-square me-1.5"></i>Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordField = document.getElementById('passwordField');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (toggleBtn && passwordField) {
            toggleBtn.addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    passwordField.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            });
        }
    });
</script>
@endsection

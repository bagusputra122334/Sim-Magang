@extends('layouts.app')
@section('title', 'Edit Pembimbing')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Pembimbing Magang</h1>
        <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="alert alert-info border-2 border-info rounded-3 mb-4 d-flex align-items-start" role="alert">
        <i class="bi bi-shield-check fs-3 text-info me-3 flex-shrink-0"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-1 text-info">Data Tetap Aman Saat Diedit</h6>
            <p class="mb-0 small lh-base">
                Mengubah <strong>Nama, NIP, Email, atau Divisi</strong> akun ini <strong>TIDAK AKAN MENGHAPUS / MEMUTUS</strong> hubungan dengan materi, tugas, dan riwayat submission yang pernah dibuat.
                Sistem menggunakan <code>ID angka (pembimbing_id = {{ $pembimbing->id }})</code> sebagai penghubung utama di database — bukan nama atau email.
                Selama <code>ID = {{ $pembimbing->id }}</code> tidak berubah, semua data riwayat akan tetap utuh.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <div class="avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-journal-text fs-3"></i>
                    </div>
                    <div class="h4 fw-bold mb-0 text-primary">{{ $materialsCount ?? 0 }}</div>
                    <div class="small text-muted">Total Materi Dibuat</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <div class="avatar-lg bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-list-task fs-3"></i>
                    </div>
                    <div class="h4 fw-bold mb-0 text-warning">{{ $tasksCount ?? 0 }}</div>
                    <div class="small text-muted">Total Tugas Dibuat</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <div class="avatar-lg bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-send-check fs-3"></i>
                    </div>
                    <div class="h4 fw-bold mb-0 text-success">{{ $submissionsCount ?? 0 }}</div>
                    <div class="small text-muted">Total Submission Peserta</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-4">
                    <div class="avatar-lg bg-indigo bg-opacity-10 text-indigo rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                        <i class="bi bi-key fs-3"></i>
                    </div>
                    <div class="h4 fw-bold mb-0 text-indigo">ID #{{ $pembimbing->id }}</div>
                    <div class="small text-muted">Primary Key Tetap Sama</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.pembimbing.update', $pembimbing->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $pembimbing->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $pembimbing->nip) }}" required>
                    @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (Login Akun)</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $pembimbing->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text small mt-1">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Email bisa diganti kapan saja. Username/login otomatis menyesuaikan.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah password)</small></label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordField" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password baru hanya jika ingin diganti..." autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-text small mt-1">
                        <i class="bi bi-info-circle me-1 text-muted"></i>
                        Jika dibiarkan kosong, password lama <strong>tetap dipertahankan</strong> dan tidak diubah. Minimal 8 karakter jika ingin diganti.
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Divisi Penugasan</label>
                    <select name="division_id" class="form-select @error('division_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('division_id', $pembimbing->division_id) == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary fw-semibold px-5 py-2">
                        <i class="bi bi-pencil-square me-2"></i>Simpan Perubahan Data
                    </button>
                    <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary fw-semibold px-4 py-2">
                        Batal
                    </a>
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

@extends('layouts.app')
@section('title', 'Tambah Pembimbing')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800 font-bold">Tambah Pembimbing Magang</h1>
        <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.pembimbing.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control py-1.5 px-2.5 text-xs @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control py-1.5 px-2.5 text-xs @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required>
                    @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control py-1.5 px-2.5 text-xs @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordField" class="form-control py-1.5 px-2.5 text-xs @error('password') is-invalid @enderror" required>
                        <button class="btn btn-outline-secondary py-1.5 px-2.5 text-xs" type="button" id="togglePasswordBtn">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Divisi <span class="text-danger">*</span></label>
                    <select name="division_id" class="form-select py-1.5 px-2.5 text-xs @error('division_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisions as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('division_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <button type="submit" class="btn btn-primary py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Pembimbing
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

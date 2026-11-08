@extends('layouts.admin')

@section('title', 'Edit Akun Admin OPD')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0 text-gray-800 dark:text-gray-100 font-bold">Edit Akun Administrator</h1>
        </div>
        <a href="{{ route('admin.opd-admins.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3 bg-white dark:bg-slate-800">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.opd-admins.update', $opdAdmin->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control py-1.5 px-2.5 text-xs @error('name') is-invalid @enderror" value="{{ old('name', $opdAdmin->name) }}" placeholder="Contoh: Mochamad Bagus, S.Kom" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Alamat Email (Username Login) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control py-1.5 px-2.5 text-xs @error('email') is-invalid @enderror" value="{{ old('email', $opdAdmin->email) }}" placeholder="admin.dinkes@tubankab.go.id" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Kata Sandi Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control py-1.5 px-2.5 text-xs @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                        <small class="text-muted text-[11px]">Hanya isi jika ingin mengubah password akun admin ini (minimal 8 karakter).</small>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        @php
                            $currentRole = old('role', $opdAdmin->role instanceof \App\Enums\UserRole ? $opdAdmin->role->value : $opdAdmin->role);
                        @endphp
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Peran / Hak Akses <span class="text-danger">*</span></label>
                        <select name="role" id="roleSelect" class="form-select py-1.5 px-2.5 text-xs @error('role') is-invalid @enderror" required onchange="toggleOpdSelect()">
                            <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin OPD (Terbatas pada OPD yang ditugaskan)</option>
                            <option value="super_admin" {{ $currentRole === 'super_admin' ? 'selected' : '' }}>Super Admin (Pusat Tuban - Akses Seluruh OPD)</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3" id="opdSelectContainer">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                            Penugasan OPD <span class="text-danger" id="opdRequiredStar">*</span>
                        </label>
                        <select name="opd_id" id="opdSelect" class="form-select py-1.5 px-2.5 text-xs @error('opd_id') is-invalid @enderror">
                            <option value="">-- Pilih OPD Penugasan --</option>
                            @foreach($opds as $opd)
                                <option value="{{ $opd->id }}" {{ old('opd_id', $opdAdmin->opd_id) == $opd->id ? 'selected' : '' }}>
                                    {{ $opd->name }} ({{ $opd->code }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted text-[11px]" id="opdHelpText">Admin ini hanya dapat mengelola data magang di lingkungan OPD terkait.</small>
                        @error('opd_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">NIP Pegawai (Opsional)</label>
                        <input type="text" name="nip" class="form-control py-1.5 px-2.5 text-xs @error('nip') is-invalid @enderror" value="{{ old('nip', $opdAdmin->nip) }}" placeholder="Contoh: 198801232015021001">
                        @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Jabatan / Unit Kerja (Opsional)</label>
                        <input type="text" name="position_title" class="form-control py-1.5 px-2.5 text-xs @error('position_title') is-invalid @enderror" value="{{ old('position_title', $opdAdmin->position_title) }}" placeholder="Contoh: Pranata Komputer Ahli Pertama">
                        @error('position_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.opd-admins.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleOpdSelect() {
        const role = document.getElementById('roleSelect').value;
        const opdSelect = document.getElementById('opdSelect');
        const star = document.getElementById('opdRequiredStar');
        const help = document.getElementById('opdHelpText');
        
        if (role === 'super_admin') {
            opdSelect.disabled = true;
            opdSelect.value = '';
            star.style.display = 'none';
            help.textContent = 'Super Admin memiliki wewenang lintas seluruh OPD (tidak terikat satu OPD).';
        } else {
            opdSelect.disabled = false;
            star.style.display = 'inline';
            help.textContent = 'Admin ini hanya dapat mengelola data magang di lingkungan OPD terkait.';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleOpdSelect);
</script>
@endsection

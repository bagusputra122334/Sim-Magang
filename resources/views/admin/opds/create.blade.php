@extends('layouts.admin')

@section('title', 'Tambah Master OPD')

@section('content')
<div class="container-fluid max-w-4xl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0 text-gray-800 dark:text-gray-100 font-bold">Tambah Master OPD</h1>
        </div>
        <a href="{{ route('admin.opds.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-3 bg-white dark:bg-slate-800">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.opds.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama OPD <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control py-1.5 px-2.5 text-xs @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Dinas Kesehatan, Pengendalian Penduduk dan Keluarga Berencana" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Kode Singkatan OPD <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control py-1.5 px-2.5 text-xs @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="Contoh: dinkes-p2kb" required>
                        <small class="text-muted text-[11px]">Digunakan sebagai kode identitas unik sistem.</small>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Slug URL (Opsional)</label>
                        <input type="text" name="slug" class="form-control py-1.5 px-2.5 text-xs @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Otomatis digenerate jika kosong">
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nama Kepala Dinas / Instansi</label>
                        <input type="text" name="head_of_office_name" class="form-control py-1.5 px-2.5 text-xs @error('head_of_office_name') is-invalid @enderror" value="{{ old('head_of_office_name') }}" placeholder="Contoh: Dr. Bambang Sudarsono, M.Kes">
                        @error('head_of_office_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">NIP Kepala Dinas</label>
                        <input type="text" name="head_of_office_nip" class="form-control py-1.5 px-2.5 text-xs @error('head_of_office_nip') is-invalid @enderror" value="{{ old('head_of_office_nip') }}" placeholder="Contoh: 197508122000031002">
                        @error('head_of_office_nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Email Resmi</label>
                        <input type="email" name="email" class="form-control py-1.5 px-2.5 text-xs @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh: dinkes@tubankab.go.id">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Nomor Telepon / Kontak</label>
                        <input type="text" name="phone" class="form-control py-1.5 px-2.5 text-xs @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 0356-321012">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Alamat Kantor</label>
                    <textarea name="address" rows="2" class="form-control py-1.5 px-2.5 text-xs @error('address') is-invalid @enderror" placeholder="Alamat lengkap instansi/kantor">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActiveCheck" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label text-xs font-bold text-slate-800 dark:text-slate-200" for="isActiveCheck">
                        Status OPD Aktif (Dapat membuka formasi dan menerima peserta magang)
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.opds.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan OPD
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

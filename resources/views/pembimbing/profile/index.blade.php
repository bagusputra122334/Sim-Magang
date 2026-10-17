@extends('layouts.app')
@section('title', 'Akun Saya - Pembimbing')
@section('content')
@php
    $defaultAvatar = asset('assets/images/avatar/avatar.jpg');
    $avatarUrl = $user->foto_url ?? $defaultAvatar;
    $initials = \Illuminate\Support\Str::limit(mb_substr($user->name ?? '?', 0, 1), 1, '');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('pembimbing.dashboard') }}"
               class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 fw-semibold"
               style="border-radius: 0.5rem; font-size: 0.78rem;">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>
        <h1 class="h4 mb-0 fw-bold" style="line-height: 1.25; color: #0f172a;">Akun Saya</h1>
    </div>
</div>

<div class="card shadow-sm rounded-2">
    <div class="card-body p-3 sm:p-4">
        <div class="row g-3 align-items-start">

            {{-- Kolom Kiri: Foto Profil + Upload --}}
            <div class="col-lg-4">
                <div class="text-center">
                    <div class="mx-auto mb-3 position-relative d-inline-block">
                        @if($user->foto_url)
                            <img src="{{ $avatarUrl }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle border-2 border-white shadow-sm object-fit-cover"
                                 style="width: 100px; height: 100px;"
                                 onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
                        @else
                            <div class="rounded-circle border-2 border-white shadow-sm d-flex align-items-center justify-content-center mx-auto fw-bold text-white"
                                 style="width: 100px; height: 100px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-size: 2.2rem;">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('pembimbing.profile.avatar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2.5 text-start">
                            <label for="avatarInput" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Unggah Foto Baru</label>
                            <input type="file"
                                   name="avatar"
                                   id="avatarInput"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback text-start text-xs mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text text-muted mt-1 text-xs">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: JPG/PNG &bull; Maks: 2 MB
                            </div>
                        </div>
                        <button type="submit"
                                class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5 py-1.5 px-3 text-xs font-bold rounded-2">
                            <i class="bi bi-cloud-arrow-up"></i>
                            Ubah Foto Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Informasi Biodata (Read-Only) --}}
            <div class="col-lg-8">
                <div class="mb-2.5 pb-1.5 border-bottom border-gray-200">
                    <h6 class="m-0 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-person-vcard text-primary"></i>
                        Informasi Biodata
                    </h6>
                    <small class="text-muted d-block mt-0.5 text-xs">
                        Data ini hanya dapat diubah oleh Administrator. Hubungi Admin untuk pembaruan data.
                    </small>
                </div>

                <div class="row g-2.5">
                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-person-badge me-1 text-slate-500"></i>Nama Lengkap
                        </label>
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs !text-slate-900">
                            {{ $user->name ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-envelope me-1 text-slate-500"></i>Email
                        </label>
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs !text-slate-900">
                            {{ $user->email ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-credit-card-2-front me-1 text-slate-500"></i>NIP / NIK
                        </label>
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs font-monospace !text-slate-900">
                            {{ $user->nip ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-briefcase me-1 text-slate-500"></i>Jabatan / Posisi
                        </label>
                        @php
                            $jabatanDisplay = 'Pembimbing';
                        @endphp
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs !text-slate-900">
                            {{ $jabatanDisplay }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-diagram-3 me-1 text-slate-500"></i>Divisi / Bidang
                        </label>
                        @php
                            $divisiDisplay = trim((string) ($user->division?->nama_divisi ?? ''));
                            if ($divisiDisplay === '') {
                                $divisiDisplay = '-';
                            }
                        @endphp
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs !text-slate-900">
                            {{ $divisiDisplay }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1">
                            <i class="bi bi-calendar3 me-1 text-slate-500"></i>Terdaftar Sejak
                        </label>
                        <div class="fw-semibold py-1.5 px-2.5 bg-slate-50 rounded-2 border border-slate-200 text-xs !text-slate-900">
                            {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

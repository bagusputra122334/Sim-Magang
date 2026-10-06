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

<div class="card shadow-sm rounded-xl" style="border-radius: 0.75rem;">
    <div class="card-body p-3 p-lg-4">
        <div class="row g-4 align-items-start">

            {{-- Kolom Kiri: Foto Profil + Upload --}}
            <div class="col-lg-4">
                <div class="text-center">
                    <div class="mx-auto mb-4 position-relative d-inline-block">
                        @if($user->foto_url)
                            <img src="{{ $avatarUrl }}"
                                 alt="{{ $user->name }}"
                                 class="rounded-circle border-4 border-white shadow-lg object-fit-cover"
                                 style="width: 180px; height: 180px;"
                                 onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';">
                        @else
                            <div class="rounded-circle border-4 border-white shadow-lg d-flex align-items-center justify-content-center mx-auto fw-bold text-white"
                                 style="width: 180px; height: 180px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-size: 4rem;">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('pembimbing.profile.avatar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="avatarInput" class="visually-hidden">Unggah Foto</label>
                            <input type="file"
                                   name="avatar"
                                   id="avatarInput"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="form-control form-control-sm @error('avatar') is-invalid @enderror"
                                   style="font-size: 0.8rem;">
                            @error('avatar')
                                <div class="invalid-feedback text-start mt-1" style="font-size: 0.75rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text text-muted mt-2 text-start" style="font-size: 0.72rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: JPG/PNG &bull; Maks: 2 MB
                            </div>
                        </div>
                        <button type="submit"
                                class="btn btn-primary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5 fw-semibold"
                                style="border-radius: 0.5rem; font-size: 0.8rem;">
                            <i class="bi bi-cloud-arrow-up"></i>
                            Ubah Foto Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Informasi Biodata (Read-Only) --}}
            <div class="col-lg-8">
                <div class="mb-3 pb-2 border-bottom border-gray-100">
                    <h6 class="m-0 fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.95rem; color: #0f172a;">
                        <i class="bi bi-person-vcard" style="color: #2563eb;"></i>
                        Informasi Biodata
                    </h6>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                        Data ini hanya dapat diubah oleh Administrator. Hubungi Admin untuk pembaruan data.
                    </small>
                </div>

                <div class="row g-3 g-lg-4">
                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-person-badge me-1" style="color: #64748b;"></i>Nama Lengkap
                        </label>
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a;">
                            {{ $user->name ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-envelope me-1" style="color: #64748b;"></i>Email
                        </label>
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a;">
                            {{ $user->email ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-credit-card-2-front me-1" style="color: #64748b;"></i>NIP / NIK
                        </label>
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a;">
                            {{ $user->nip ?? '-' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-briefcase me-1" style="color: #64748b;"></i>Jabatan / Posisi
                        </label>
                        @php
                            $jabatanDisplay = 'Pembimbing';
                        @endphp
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a; min-height: 42px;">
                            {{ $jabatanDisplay }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-diagram-3 me-1" style="color: #64748b;"></i>Divisi / Bidang
                        </label>
                        @php
                            $divisiDisplay = trim((string) ($user->division?->nama_divisi ?? ''));
                            if ($divisiDisplay === '') {
                                $divisiDisplay = '-';
                            }
                        @endphp
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a; min-height: 42px;">
                            {{ $divisiDisplay }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted mb-1 d-block" style="font-size: 0.72rem; letter-spacing: 0.02em;">
                            <i class="bi bi-calendar3 me-1" style="color: #64748b;"></i>Terdaftar Sejak
                        </label>
                        <div class="fw-semibold text-gray-900 py-2 px-3 bg-gray-50 rounded-md border border-gray-100"
                             style="font-size: 0.92rem; color: #0f172a;">
                            {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

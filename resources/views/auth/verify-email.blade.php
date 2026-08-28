@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Verifikasi Email</h1>
    <p class="text-muted mb-0">Verifikasi alamat email Anda untuk mengakses layanan penuh SIM-MAGANG.</p>
</div>

<div class="alert alert-info mb-4" role="alert">
    <div class="d-flex align-items-start">
        <i class="bi bi-info-circle-fill fs-5 me-2 mt-1"></i>
        <div>
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika tidak menerima email, kami dapat mengirimkannya kembali.
        </div>
    </div>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
            <div class="flex-grow-1">Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda gunakan saat pendaftaran.</div>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}" class="mb-3">
    @csrf
    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2.5 px-4" type="submit">
        <i class="bi bi-envelope-check me-1" aria-hidden="true"></i> Resend Verification Email
    </button>
</form>

<form method="POST" action="{{ route('logout') }}" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-outline-secondary bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md w-100 py-2.5 px-4">
        <i class="bi bi-box-arrow-right me-1"></i> Log Out
    </button>
</form>

@endsection

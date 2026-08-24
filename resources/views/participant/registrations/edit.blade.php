@extends('layouts.participant')
@section('title', 'Ubah Pendaftaran — ' . ($reg->nomor_pendaftaran ?? ''))

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-11">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Ubah Data Pendaftaran</h1>
                <p class="text-muted mb-0">Nomor Pendaftaran: <strong class="font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</strong></p>
            </div>
            <a href="{{ route('participant.registrations.show', $reg->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>

        <div class="alert alert-warning border-2 rounded-4 mb-5 py-4 px-5 d-flex align-items-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill display-6 text-warning me-4 flex-shrink-0"></i>
            <div class="flex-grow-1">
                <h4 class="alert-heading fw-bold mb-2">Mode Ubah / Perbarui Data Pendaftaran</h4>
                <p class="mb-0">Anda dapat mengubah data pendaftaran dengan status <strong class="text-primary-emphasis">{{ $reg->status->label() }}</strong>. Jika terdapat file dokumen (CV / Surat Pengantar) yang tidak Anda upload ulang, maka file lama akan tetap digunakan.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white py-4 px-5 d-md-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Ubah Data Pendaftaran
                    </h3>
                    <p class="mb-0 text-white-50 mt-1 small font-monospace">Nomor Pendaftaran: {{ $reg->nomor_pendaftaran }}</p>
                </div>
                <span class="badge border border-2 rounded-pill px-3 py-2 fs-6 bg-white text-primary border-white mt-2 mt-md-0">
                    <i class="bi bi-patch-check-fill me-1"></i>Status: {{ $reg->status->label() }}
                </span>
            </div>
            <form method="POST" action="{{ route('participant.registrations.update', $reg->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-5">
                    @include('participant.registrations._form', [
                        'positions' => $positions,
                        'reg'       => $reg,
                    ])
                </div>
                <div class="card-footer bg-white border-top py-4 px-5 text-end">
                    <a href="{{ route('participant.registrations.show', $reg->id) }}" class="btn btn-outline-secondary btn-lg fw-semibold me-2">
                        <i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Detail
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm">
                        <i class="bi bi-save2-fill me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

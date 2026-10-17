@extends('layouts.participant')
@section('title', 'Ubah Pendaftaran — ' . ($reg->nomor_pendaftaran ?? ''))

@section('content')
<div class="row justify-content-center mb-3">
    <div class="col-lg-11">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h4 fw-bold mb-0.5">Ubah Data Pendaftaran</h1>
                <p class="text-muted small mb-0">Nomor Pendaftaran: <strong class="font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</strong></p>
            </div>
            <a href="{{ route('participant.registrations.show', $reg->id) }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2 font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>

        <div class="alert alert-warning border rounded-2 mb-3 py-2 px-3 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning me-2.5 flex-shrink-0"></i>
            <div class="flex-grow-1 text-xs">
                <strong class="fw-bold d-block mb-0.5">Mode Ubah / Perbarui Data Pendaftaran</strong>
                <p class="mb-0 text-muted">Anda dapat mengubah data pendaftaran dengan status <strong class="text-primary-emphasis">{{ $reg->status->label() }}</strong>. Jika terdapat file dokumen (CV / Surat Pengantar) yang tidak Anda upload ulang, maka file lama akan tetap digunakan.</p>
            </div>
        </div>

        <div class="card shadow-sm border rounded-2 overflow-hidden mb-3">
            <div class="card-header bg-gradient bg-primary text-white py-2 px-3 d-md-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-pencil-square me-1.5"></i>Ubah Data Pendaftaran
                    </h6>
                    <p class="mb-0 text-white-50 mt-0.5 text-xs font-monospace">Nomor Pendaftaran: {{ $reg->nomor_pendaftaran }}</p>
                </div>
                <span class="badge border rounded-pill px-2.5 py-1 text-xs bg-white text-primary border-white mt-1 mt-md-0 fw-bold">
                    <i class="bi bi-patch-check-fill me-1"></i>Status: {{ $reg->status->label() }}
                </span>
            </div>
            <form method="POST" action="{{ route('participant.registrations.update', $reg->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-3 sm:p-4">
                    @include('participant.registrations._form', [
                        'positions' => $positions,
                        'reg'       => $reg,
                    ])
                </div>
                <div class="card-footer bg-white border-top py-2 px-3 text-end">
                    <a href="{{ route('participant.registrations.show', $reg->id) }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2 font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs me-2">
                        <i class="bi bi-arrow-left-circle me-1.5"></i>Kembali ke Detail
                    </a>
                    <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-2 font-bold text-xs shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3.5 py-1.5 d-inline-flex align-items-center justify-content-center" style="background-color: #4f46e5 !important; color: #ffffff !important; border-color: #4f46e5 !important;">
                        <i class="bi bi-save2-fill me-1.5" style="color: #ffffff !important;"></i>Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

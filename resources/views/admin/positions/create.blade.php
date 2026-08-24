@extends('layouts.admin')

@section('title', 'Tambah Posisi Magang Baru')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Tambah Posisi Magang Baru</h1>
        </div>
        <a href="{{ route('admin.positions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-lg-5">
            <form action="{{ route('admin.positions.store') }}" method="POST" novalidate>
                @csrf

                @include('admin.positions._form', [
                    'position' => $position,
                    'statusOptions' => $statusOptions,
                    'formMode' => 'create',
                ])

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4 pt-4 border-top">
                    <a href="{{ route('admin.positions.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                    </a>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="reset" class="btn btn-outline-warning px-4 py-2">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-save2 me-1" aria-hidden="true"></i> Simpan Posisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

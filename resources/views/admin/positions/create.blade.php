@extends('layouts.admin')

@section('title', 'Tambah Posisi Magang Baru')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Tambah Posisi Magang Baru</h1>
        </div>
        <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2">
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
                    <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                    </a>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="reset" class="btn bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i> Reset
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-bold rounded-lg shadow-md transition-colors">
                            Simpan Posisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

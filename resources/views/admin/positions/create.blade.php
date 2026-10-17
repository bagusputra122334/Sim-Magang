@extends('layouts.admin')

@section('title', 'Tambah Posisi Magang Baru')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 fw-bold mb-0">Tambah Posisi Magang Baru</h1>
        </div>
        <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.positions.store') }}" method="POST" novalidate>
                @csrf

                @include('admin.positions._form', [
                    'position' => $position,
                    'statusOptions' => $statusOptions,
                    'formMode' => 'create',
                ])

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                    </a>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="reset" class="btn bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
                            <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i> Reset
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                            Simpan Posisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

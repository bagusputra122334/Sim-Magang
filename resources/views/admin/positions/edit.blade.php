@extends('layouts.admin')

@section('title', 'Edit Posisi Magang: '.$position->nama_posisi)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Edit Posisi Magang</h1>
            <small class="text-muted">Terakhir diperbarui: {{ $position->updated_at?->format('d F Y H:i') ?? '-' }}</small>
        </div>
        <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-lg-5">
            <form action="{{ route('admin.positions.update', $position) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                @include('admin.positions._form', [
                    'position' => $position,
                    'statusOptions' => $statusOptions,
                    'formMode' => 'edit',
                ])

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4 pt-4 border-top">
                    <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                    </a>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.positions.create') }}" class="btn bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Lainnya
                        </a>
                        <button type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                            <i class="bi bi-check2-circle me-1" aria-hidden="true"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($position) && $position->exists)
        <form id="delete-position-form" action="{{ route('admin.positions.destroy', $position) }}" method="POST" class="d-none"
              onsubmit="return confirm('YAKIN HAPUS POSISI INI?\n\nNama: {{ $position->nama_posisi }}\n\nTindakan ini tidak bisa dibatalkan.');">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection

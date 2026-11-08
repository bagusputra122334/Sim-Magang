@extends('layouts.admin')

@section('title', 'Edit Posisi Magang: '.$position->nama_posisi)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="h3 fw-bold mb-0">Edit Posisi Magang</h1>
            <small class="text-muted">Terakhir diperbarui: {{ $position->updated_at?->format('d F Y H:i') ?? '-' }}</small>
        </div>
        <a href="{{ route('admin.positions.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('admin.positions.update', $position) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                @include('admin.positions._form', [
                    'position' => $position,
                    'statusOptions' => $statusOptions,
                    'formMode' => 'edit',
                ])

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('admin.positions.index') }}" class="btn btn-outline-secondary rounded-lg font-bold shadow-xs px-3 py-1.5 text-xs d-inline-flex align-items-center gap-1.5">
                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                    </a>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.positions.create') }}" class="btn btn-outline-primary rounded-lg font-bold shadow-xs px-3 py-1.5 text-xs d-inline-flex align-items-center gap-1.5">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Lainnya
                        </a>
                        <button type="submit" class="btn btn-primary rounded-lg font-bold shadow-sm px-4 py-1.5 text-xs d-inline-flex align-items-center gap-1.5 text-white !text-white" style="background-color: #2563eb !important; border-color: #2563eb !important; color: #ffffff !important;">
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

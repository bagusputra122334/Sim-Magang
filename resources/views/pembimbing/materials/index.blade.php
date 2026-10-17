@extends('layouts.app')
@section('title', 'Kelola Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">Daftar Materi</h1>
        <a href="{{ route('pembimbing.materials.create') }}" class="btn btn-primary fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Materi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 text-xs rounded-2 mb-3">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
        <div class="card-body p-3 sm:p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-xs">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 48px;">No</th>
                            <th>Judul Materi</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 120px;">Link YouTube</th>
                            <th class="text-center" style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration + $materials->firstItem() - 1 }}</td>
                                <td class="fw-semibold text-slate-900 dark:text-slate-100">{{ $material->title }}</td>
                                <td class="text-slate-600 dark:text-slate-300">{{ Str::limit($material->description, 50) }}</td>
                                <td class="text-center">
                                    @if($material->youtube_url)
                                        <a href="{{ $material->youtube_url }}" target="_blank" class="text-danger fw-semibold"><i class="bi bi-youtube me-1"></i> Tonton</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1.5 flex-wrap">
                                        <!-- Tombol Lihat Pengumpulan -->
                                        <a href="{{ route('pembimbing.materials.submissions', $material->id) }}" class="btn btn-sm text-white fw-semibold shadow-sm rounded-1 px-2 py-0.5 d-inline-flex align-items-center gap-1 text-xs" style="background-color: #4f46e5;">
                                            <i class="bi bi-eye"></i>
                                            <span>Pengumpulan</span>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('pembimbing.materials.edit', $material->id) }}" class="btn btn-sm text-white fw-semibold shadow-sm rounded-1 px-2 py-0.5 d-inline-flex align-items-center gap-1 text-xs" style="background-color: #06b6d4;">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('pembimbing.materials.destroy', $material->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-white fw-semibold shadow-sm rounded-1 px-2 py-0.5 d-inline-flex align-items-center gap-1 text-xs" style="background-color: #dc2626;">
                                                <i class="bi bi-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Belum ada materi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($materials->hasPages())
                <div class="mt-2.5">
                    {{ $materials->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

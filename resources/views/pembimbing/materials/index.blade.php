@extends('layouts.app')
@section('title', 'Kelola Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Materi</h1>
        <a href="{{ route('pembimbing.materials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Materi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Materi</th>
                            <th>Deskripsi</th>
                            <th>Link YouTube</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr>
                                <td>{{ $loop->iteration + $materials->firstItem() - 1 }}</td>
                                <td>{{ $material->title }}</td>
                                <td>{{ Str::limit($material->description, 50) }}</td>
                                <td>
                                    @if($material->youtube_url)
                                        <a href="{{ $material->youtube_url }}" target="_blank" class="text-danger"><i class="bi bi-youtube"></i> Tonton</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <!-- Tombol Lihat Pengumpulan -->
                                        <a href="{{ route('pembimbing.materials.submissions', $material->id) }}" class="btn btn-sm text-white fw-semibold shadow-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="background-color: #4f46e5;" onmouseover="this.style.backgroundColor='#4338ca'" onmouseout="this.style.backgroundColor='#4f46e5'">
                                            <i class="bi bi-eye"></i>
                                            <span>Pengumpulan</span>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('pembimbing.materials.edit', $material->id) }}" class="btn btn-sm text-white fw-semibold shadow-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="background-color: #06b6d4;" onmouseover="this.style.backgroundColor='#0891b2'" onmouseout="this.style.backgroundColor='#06b6d4'">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Edit</span>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('pembimbing.materials.destroy', $material->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-white fw-semibold shadow-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1" style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">
                                                <i class="bi bi-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada materi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $materials->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

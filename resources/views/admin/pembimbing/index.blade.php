@extends('layouts.app')
@section('title', 'Kelola Pembimbing Magang')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembimbing Magang</h1>
        <a href="{{ route('admin.pembimbing.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pembimbing
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
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Divisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembimbings as $pembimbing)
                            <tr>
                                <td>{{ $loop->iteration + $pembimbings->firstItem() - 1 }}</td>
                                <td>{{ $pembimbing->name }}</td>
                                <td>{{ $pembimbing->nip ?? '-' }}</td>
                                <td>{{ $pembimbing->email }}</td>
                                <td>{{ $pembimbing->division->nama_divisi ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.pembimbing.edit', $pembimbing->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pembimbing.destroy', $pembimbing->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pembimbing ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data pembimbing.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $pembimbings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

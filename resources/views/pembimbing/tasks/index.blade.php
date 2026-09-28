@extends('layouts.app')
@section('title', 'Kelola Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Tugas</h1>
        <a href="{{ route('pembimbing.tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Tugas
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
                            <th>Judul Tugas</th>
                            <th>Batas Waktu</th>
                            <th>Format</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $loop->iteration + $tasks->firstItem() - 1 }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->deadline->format('d M Y H:i') }}</td>
                                <td>
                                    @foreach($task->allowed_format as $format)
                                        <span class="badge bg-secondary">{{ strtoupper($format) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pembimbing.tasks.submissions', $task->id) }}" class="btn btn-sm btn-success text-white">
                                            <i class="bi bi-card-checklist"></i> Submissions
                                        </a>
                                        <a href="{{ route('pembimbing.tasks.edit', $task->id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('pembimbing.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
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
                                <td colspan="5" class="text-center">Belum ada tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

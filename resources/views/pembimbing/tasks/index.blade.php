@extends('layouts.app')
@section('title', 'Kelola Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">Daftar Tugas</h1>
        <a href="{{ route('pembimbing.tasks.create') }}" class="btn btn-primary fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Tugas
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
                            <th>Judul Tugas</th>
                            <th style="width: 150px;">Batas Waktu</th>
                            <th style="width: 120px;">Format</th>
                            <th class="text-center" style="width: 210px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration + $tasks->firstItem() - 1 }}</td>
                                <td class="fw-semibold text-slate-900 dark:text-slate-100">{{ $task->title }}</td>
                                <td>{{ $task->deadline->format('d M Y H:i') }}</td>
                                <td>
                                    @foreach($task->allowed_format as $format)
                                        <span class="badge bg-secondary text-xs px-2 py-0.5 rounded-pill">{{ strtoupper($format) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                                        <a href="{{ route('pembimbing.tasks.submissions', $task->id) }}" class="btn btn-sm btn-success text-white fw-semibold py-0.5 px-2 text-xs rounded-1 shadow-sm">
                                            <i class="bi bi-card-checklist me-1"></i> Submissions
                                        </a>
                                        <a href="{{ route('pembimbing.tasks.edit', $task->id) }}" class="btn btn-sm btn-info text-white fw-semibold py-0.5 px-2 text-xs rounded-1 shadow-sm">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('pembimbing.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-semibold py-0.5 px-2 text-xs rounded-1 shadow-sm">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Belum ada tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tasks->hasPages())
                <div class="mt-2.5">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

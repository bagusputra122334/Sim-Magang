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

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden mb-4">
        <!-- Toolbar Bar (Ultra-Compact) -->
        <form method="GET" action="{{ route('pembimbing.tasks.index') }}" class="px-3 py-2.5 bg-slate-50/60 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2" role="search">
            <div class="flex items-center gap-2 text-xs text-slate-700 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                <span>Tampilkan</span>
                <select name="perPage" class="h-8 px-2.5 pr-7 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
                    <option value="10" @selected((string)request('perPage', 10) === '10')>10</option>
                    <option value="25" @selected((string)request('perPage') === '25')>25</option>
                    <option value="50" @selected((string)request('perPage') === '50')>50</option>
                </select>
                <span>entri</span>

                <!-- Filter Tahun (Dinamis) -->
                <select name="year" class="h-8 px-2.5 pr-7 ml-1 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" @selected((string)request('year') === (string)$y)>Tahun {{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative w-full sm:w-60">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari tugas..."
                       class="h-8 w-full pl-8 pr-7 bg-white border border-slate-300 rounded text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 shadow-xs">
                @if(request('search') || request('year') || (request('perPage') && request('perPage') != 10))
                    <a href="{{ route('pembimbing.tasks.index') }}" title="Reset Filter" class="absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-circle-fill text-xs"></i>
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-bordered table-hover align-middle mb-0 text-xs table-sm">
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
                                        <span class="badge bg-slate-200 text-slate-900 border border-slate-400 text-xs px-2.5 py-0.5 rounded-pill font-bold shadow-xs">{{ strtoupper($format) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center gap-1.5">
                                        <a href="{{ route('pembimbing.tasks.submissions', $task->id) }}" class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-1 px-2.5 text-xs rounded-lg shadow-sm text-decoration-none">
                                            <i class="bi bi-card-checklist me-1"></i> Submissions
                                        </a>
                                        <a href="{{ route('pembimbing.tasks.edit', $task->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white font-semibold py-1 px-2.5 text-xs rounded-lg shadow-sm text-decoration-none">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('pembimbing.tasks.destroy', $task->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm bg-rose-600 hover:bg-rose-700 text-white font-semibold py-1 px-2.5 text-xs rounded-lg shadow-sm border-0 cursor-pointer"
                                                    data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Judul Tugas</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($task->title) }}</div></div>"
                                                    data-simang-title="Hapus Penugasan"
                                                    data-simang-icon="bi-trash-fill"
                                                    data-simang-theme="rose"
                                                    data-simang-alert="Penugasan ini beserta seluruh riwayat penilaian yang terkait akan dihapus secara permanen."
                                                    data-simang-btn-text="Ya, Hapus Tugas"
                                                    data-simang-btn-icon="bi-trash-fill">
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
                <div class="p-3 border-t border-slate-200">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

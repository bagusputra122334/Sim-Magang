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

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden mb-4">
        <!-- Toolbar Bar (Sangat rapat, py-2.5 px-3, tanpa jarak ke bawah) -->
        <form method="GET" action="{{ route('pembimbing.materials.index') }}" class="px-3 py-2.5 bg-slate-50/60 border-b border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2" role="search">
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

                <!-- Filter Bulan -->
                <select name="month" class="h-8 px-2.5 pr-7 ml-1 bg-white border border-slate-300 rounded text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
                    <option value="">Semua Bulan</option>
                    @php
                        $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                    @endphp
                    @foreach($months as $num => $name)
                        <option value="{{ (int)$num }}" @selected((int)request('month') === (int)$num)>{{ $name }}</option>
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
                       placeholder="Cari materi..."
                       class="h-8 w-full pl-8 pr-7 bg-white border border-slate-300 rounded text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 shadow-xs">
                @if(request('search') || request('year') || request('month') || (request('perPage') && request('perPage') != 10))
                    <a href="{{ route('pembimbing.materials.index') }}" title="Reset Filter" class="absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400 hover:text-slate-600">
                        <i class="bi bi-x-circle-fill text-xs"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="table table-bordered table-hover align-middle mb-0 text-xs table-sm">
                <thead class="table-light bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="text-center py-2 px-3" style="width: 48px;">No</th>
                        <th class="py-2 px-3">Judul Materi</th>
                        <th class="py-2 px-3">Deskripsi</th>
                        <th class="text-center py-2 px-3" style="width: 130px;">Link YouTube</th>
                        <th class="text-center py-2 px-3" style="width: 250px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td class="text-center text-muted fw-semibold py-2 px-3">{{ $loop->iteration + $materials->firstItem() - 1 }}</td>
                            <td class="py-2 px-3">
                                <div class="fw-semibold text-slate-900 dark:text-slate-100">{{ $material->title }}</div>
                                <div class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                                    <i class="bi bi-clock"></i>
                                    <span>Waktu Dibuat: {{ $material->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="text-slate-600 dark:text-slate-300 py-2 px-3">{{ Str::limit($material->description, 50) }}</td>
                            <td class="text-center py-2 px-3">
                                @if($material->youtube_url)
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-2 py-1 rounded border border-red-200 transition-colors text-decoration-none">
                                        <i class="bi bi-youtube"></i>
                                        <span>Tonton</span>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                    <!-- Tombol Lihat Pengumpulan -->
                                    <a href="{{ route('pembimbing.materials.submissions', $material->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                        <i class="bi bi-eye"></i>
                                        <span>Pengumpulan</span>
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('pembimbing.materials.edit', $material->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Tombol Hapus dengan Standard Modal -->
                                    <form action="{{ route('pembimbing.materials.destroy', $material->id) }}" method="POST" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors cursor-pointer whitespace-nowrap"
                                                data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Judul Materi</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($material->title) }}</div><div class='col-4 text-slate-500 font-semibold'>Dibuat Pada</div><div class='col-8'>{{ $material->created_at?->translatedFormat('d M Y') }}</div></div>"
                                                data-simang-title="Hapus Materi &amp; Penugasan"
                                                data-simang-icon="bi-trash-fill"
                                                data-simang-theme="rose"
                                                data-simang-alert="Materi ini beserta seluruh riwayat pengumpulan tugas anak bimbing yang terkait akan dihapus secara permanen dari sistem."
                                                data-simang-btn-text="Ya, Hapus Materi"
                                                data-simang-btn-icon="bi-trash-fill">
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
            <div class="p-3 border-t border-slate-200">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

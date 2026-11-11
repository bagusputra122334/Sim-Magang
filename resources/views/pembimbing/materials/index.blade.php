@extends('layouts.app')
@section('title', 'Kelola Materi & Penugasan')
@section('content')
<div class="container-fluid">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2" style="margin-bottom: 0.5rem !important;">
        <div>
            <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">Kelola Materi &amp; Penugasan</h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($tab === 'mandiri')
                <a href="{{ route('pembimbing.materials.create', ['target' => 'custom']) }}" class="btn btn-primary fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm d-inline-flex align-items-center">
                    <i class="bi bi-person-plus-fill me-1.5"></i> Tambah Penugasan Mandiri
                </a>
            @else
                <a href="{{ route('pembimbing.materials.create', ['target' => 'all']) }}" class="btn btn-primary fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm d-inline-flex align-items-center">
                    <i class="bi bi-plus-lg me-1.5"></i> Tambah Materi Divisi
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 text-xs rounded-2 mb-2 shadow-xs d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-6"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Segmented Mode Switcher (Divisi vs Mandiri) -->
    <div class="p-1 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 inline-flex items-center gap-1.5 mb-2 flex-wrap shadow-inner" style="background-color: #f1f5f9; border: 1px solid #e2e8f0; margin-bottom: 0.625rem !important;">
        <!-- Opsi 1: Penugasan Divisi (Bersama) -->
        <a href="{{ route('pembimbing.materials.index', ['tab' => 'divisi']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 text-decoration-none transition-all cursor-pointer shadow-xs"
           @if($tab === 'divisi')
               style="background-color: #2563eb !important; color: #ffffff !important; box-shadow: 0 1px 3px rgba(37,99,235,0.3) !important;"
           @else
               style="background-color: transparent; color: #475569;"
               onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#0f172a';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#475569';"
           @endif>
            <i class="bi bi-people-fill text-sm"></i>
            <span>Penugasan Divisi (Bersama)</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                  @if($tab === 'divisi')
                      style="background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;"
                  @else
                      style="background-color: #e2e8f0 !important; color: #334155 !important;"
                  @endif>
                {{ $divisionMaterialsCount }}
            </span>
        </a>

        <!-- Opsi 2: Penugasan Mandiri (Perorangan / Spesialisasi) -->
        <a href="{{ route('pembimbing.materials.index', ['tab' => 'mandiri']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 text-decoration-none transition-all cursor-pointer shadow-xs"
           @if($tab === 'mandiri')
               style="background-color: #4f46e5 !important; color: #ffffff !important; box-shadow: 0 1px 3px rgba(79,70,229,0.3) !important;"
           @else
               style="background-color: transparent; color: #475569;"
               onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#0f172a';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#475569';"
           @endif>
            <i class="bi bi-person-badge-fill text-sm"></i>
            <span>Penugasan Mandiri (Perorangan)</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                  @if($tab === 'mandiri')
                      style="background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;"
                  @else
                      style="background-color: #e2e8f0 !important; color: #334155 !important;"
                  @endif>
                {{ $activeInternsTotalCount }} Peserta
            </span>
        </a>
    </div>

    @if($tab === 'divisi')
        {{-- =========================================================================
             TAB 1: PENUGASAN DIVISI (BERSAMA)
             ========================================================================= --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden mb-4">
            <!-- Toolbar Bar -->
            <form method="GET" action="{{ route('pembimbing.materials.index') }}" class="px-3 py-2.5 bg-slate-50/60 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2" role="search">
                <input type="hidden" name="tab" value="divisi">
                <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                    <span>Tampilkan</span>
                    <select name="perPage" class="h-8 px-2.5 pr-7 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
                        <option value="5" @selected((string)request('perPage', request('per_page', 10)) === '5')>5</option>
                        <option value="10" @selected((string)request('perPage', request('per_page', 10)) === '10')>10</option>
                        <option value="15" @selected((string)request('perPage', request('per_page', 10)) === '15')>15</option>
                        <option value="20" @selected((string)request('perPage', request('per_page', 10)) === '20')>20</option>
                        <option value="25" @selected((string)request('perPage', request('per_page', 10)) === '25')>25</option>
                        <option value="50" @selected((string)request('perPage', request('per_page', 10)) === '50')>50</option>
                        <option value="100" @selected((string)request('perPage', request('per_page', 10)) === '100')>100</option>
                        <option value="all" @selected((string)request('perPage', request('per_page')) === 'all' || (int)request('perPage', request('per_page')) >= 1000)>Semua (All)</option>
                    </select>
                    <span>entri</span>

                    <!-- Filter Tahun (Dinamis) -->
                    <select name="year" class="h-8 px-2.5 pr-7 ml-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" @selected((string)request('year') === (string)$y)>Tahun {{ $y }}</option>
                        @endforeach
                    </select>

                    <!-- Filter Bulan -->
                    <select name="month" class="h-8 px-2.5 pr-7 ml-1 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs" onchange="this.form.submit()">
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
                           class="h-8 w-full pl-8 pr-7 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 shadow-xs">
                    @if(request('search') || request('year') || request('month') || (request('perPage') && request('perPage') != 10))
                        <a href="{{ route('pembimbing.materials.index', ['tab' => 'divisi']) }}" title="Reset Filter" class="absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-circle-fill text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabel Data Materi Divisi -->
            <div class="overflow-x-auto">
                <table class="table table-bordered table-hover align-middle mb-0 text-xs table-sm">
                    <thead class="table-light bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th class="text-center py-2 px-3" style="width: 48px;">No</th>
                            <th class="py-2 px-3">Judul Materi &amp; Penugasan</th>
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
                                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                        <span class="fw-bold text-slate-900 dark:text-slate-100">{{ $material->title }}</span>
                                        @if($material->is_task)
                                            <span style="background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important; padding: 0.15rem 0.45rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;" class="d-inline-flex align-items-center">
                                                <i class="bi bi-clipboard-check me-0.5"></i> Tugas
                                            </span>
                                        @endif
                                        @php $filesCount = count($material->all_files); @endphp
                                        @if($filesCount > 0)
                                            <span style="background-color: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; padding: 0.15rem 0.45rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;" class="d-inline-flex align-items-center">
                                                <i class="bi bi-paperclip me-0.5"></i> {{ $filesCount }} Berkas
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5 flex-wrap">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="bi bi-clock"></i>
                                            Dibuat: {{ $material->created_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                                        </span>
                                        @if($material->is_task && $material->deadline)
                                            <span class="text-amber-600 font-semibold ms-1">
                                                &bull; Deadline: {{ \Carbon\Carbon::parse($material->deadline)->translatedFormat('d M Y, H:i') }} WIB
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-slate-600 dark:text-slate-300 py-2 px-3">{{ Str::limit($material->description, 60) }}</td>
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
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <div class="py-3">
                                        <i class="bi bi-folder-x fs-2 text-slate-300 dark:text-slate-600 mb-1 d-block"></i>
                                        <span class="text-xs">Belum ada materi atau tugas divisi yang dibuat.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($materials->hasPages())
                <div class="p-3 border-t border-slate-200 dark:border-slate-800">
                    {{ $materials->links() }}
                </div>
            @endif
        </div>

    @else
        {{-- =========================================================================
             TAB 2: PENUGASAN MANDIRI (DIREKTORI PESERTA AKTIF & DOSSIER)
             ========================================================================= --}}

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden mb-4">
            <!-- Search Toolbar Penugasan Mandiri -->
            <form method="GET" action="{{ route('pembimbing.materials.index') }}" class="px-3 py-2.5 bg-slate-50/60 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2" role="search">
                <input type="hidden" name="tab" value="mandiri">
                <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 w-full sm:w-auto">
                    <span class="fw-bold text-slate-800 dark:text-slate-200">
                        <i class="bi bi-people me-1 text-indigo-600"></i>
                        {{ $activeInterns->count() }} Peserta Magang Aktif
                    </span>
                    @if($internSearch)
                        <span class="text-slate-400">|</span>
                        <span class="text-slate-500">Hasil filter pencarian: "<strong>{{ $internSearch }}</strong>"</span>
                    @endif
                </div>

                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                        <i class="bi bi-search text-xs"></i>
                    </span>
                    <input type="text"
                           name="intern_search"
                           value="{{ $internSearch }}"
                           placeholder="Cari nama anak magang, kampus, jurusan..."
                           class="h-8 w-full pl-8 pr-7 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-indigo-500 placeholder:text-slate-400 shadow-xs">
                    @if($internSearch)
                        <a href="{{ route('pembimbing.materials.index', ['tab' => 'mandiri']) }}" title="Reset Pencarian" class="absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400 hover:text-slate-600">
                            <i class="bi bi-x-circle-fill text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabel Direktori Anak Magang Aktif -->
            <div class="overflow-x-auto">
                <table class="table table-bordered table-hover align-middle mb-0 text-xs table-sm">
                    <thead class="table-light bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th class="text-center py-2 px-2" style="width: 40px;">No</th>
                            <th class="py-2 px-2.5">Nama Anak Magang</th>
                            <th class="py-2 px-2.5">Institusi &amp; Jurusan</th>
                            <th class="py-2 px-2.5">Posisi</th>
                            <th class="text-center py-2 px-2.5">Masa Magang</th>
                            <th class="text-center py-2 px-2.5">Status Tugas</th>
                            <th class="text-center py-2 px-2.5" style="width: 1%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeInterns as $internData)
                            @php
                                $u = $internData->user;
                                $reg = $internData->registration;
                                $initials = strtoupper(substr($u->name ?? 'A', 0, 2));
                            @endphp
                            <tr>
                                <td class="text-center text-muted fw-semibold py-2 px-2">{{ $loop->iteration }}</td>
                                <td class="py-2 px-2.5">
                                    <div class="d-flex align-items-center gap-2">
                                        @if(!empty($u->avatar))
                                            <img src="{{ Storage::url($u->avatar) }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shrink-0 border border-indigo-200">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('pembimbing.materials.personal', $u->id) }}" class="fw-bold text-slate-900 dark:text-slate-100 hover:text-indigo-600 transition-colors text-decoration-none block truncate" title="Klik untuk membuka riwayat penugasan">
                                                {{ $u->name }}
                                            </a>
                                            <div class="text-[11px] text-slate-500 font-medium truncate">
                                                {{ $u->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-2.5">
                                    <div class="fw-semibold text-slate-800 dark:text-slate-200">
                                        {{ $u->profile->institusi ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $u->profile->jurusan ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-2 px-2.5">
                                    @if($reg->position)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            {{ $reg->position->nama_posisi }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                    @if($reg->periode_selesai)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Aktif
                                        </span>
                                        <div class="text-[10px] text-slate-500 mt-0.5 font-medium">
                                            s.d {{ \Carbon\Carbon::parse($reg->periode_selesai)->translatedFormat('d M Y') }}
                                            @if($internData->remaining_days !== null)
                                                ({{ $internData->remaining_days }} hari lagi)
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                    @if($internData->personal_tasks_count > 0)
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span style="background-color: #ede9fe !important; color: #5b21b6 !important; border: 1px solid #ddd6fe !important; padding: 0.15rem 0.45rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;" class="d-inline-flex align-items-center">
                                                <i class="bi bi-folder-check me-1"></i> {{ $internData->personal_tasks_count }} Tugas Mandiri
                                            </span>
                                            @if($internData->pending_reviews_count > 0)
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    <i class="bi bi-clock-history me-1"></i> {{ $internData->pending_reviews_count }} Perlu Review
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Belum ada tugas</span>
                                    @endif
                                </td>
                                <td class="py-2 px-2.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                        <!-- Buka Riwayat Penugasan Mandiri -->
                                        <a href="{{ route('pembimbing.materials.personal', $u->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                            <i class="bi bi-folder2-open"></i>
                                            <span>Riwayat Tugas</span>
                                        </a>

                                        <!-- Berikan Tugas Baru Khusus Anak Ini -->
                                        <a href="{{ route('pembimbing.materials.create', ['target' => 'custom', 'user_id' => $u->id]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                            <i class="bi bi-plus-circle"></i>
                                            <span>Beri Tugas</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-3">
                                        <i class="bi bi-person-x fs-2 text-slate-300 dark:text-slate-600 mb-1 d-block"></i>
                                        <span class="text-xs font-semibold">
                                            @if($internSearch)
                                                Tidak ditemukan peserta magang aktif dengan kata kunci "{{ $internSearch }}".
                                            @else
                                                Saat ini belum ada peserta magang aktif dalam divisi bimbingan Anda.
                                            @endif
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

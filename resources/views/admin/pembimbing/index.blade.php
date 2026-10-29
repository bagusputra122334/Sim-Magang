@extends('layouts.admin')

@section('title', 'Daftar Pembimbing Magang')

@section('content')
<div class="w-full space-y-4">
    <!-- Header Page: Title & Action Button Segaris Tanpa Subtitle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 w-full">
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Daftar Pembimbing Magang</h1>
        <div>
            <a href="{{ route('admin.pembimbing.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-all text-decoration-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pembimbing
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-lg text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Card Container (Full Width) -->
    <div class="w-full bg-white dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 shadow-sm overflow-hidden">
        <!-- Toolbar Ultra-Compact Single-Line -->
        <form method="GET" action="{{ route('admin.pembimbing.index') }}" class="px-4 py-3 bg-slate-50/70 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 w-full" role="search">
            <div class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300">
                <span>Tampilkan</span>
                <select name="perPage" class="h-8 px-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer" onchange="this.form.submit()">
                    <option value="10" {{ (string)request('perPage', request('per_page', $perPage ?? 10)) === '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ (string)request('perPage', request('per_page', $perPage ?? 10)) === '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ (string)request('perPage', request('per_page', $perPage ?? 10)) === '50' ? 'selected' : '' }}>50</option>
                </select>
                @if(request('search') || (request('perPage') && request('perPage') != 10) || (request('per_page') && request('per_page') != 10))
                    <a href="{{ route('admin.pembimbing.index') }}" title="Reset Filter" class="text-xs text-rose-600 hover:text-rose-700 dark:text-rose-400 font-medium ml-1">
                        <i class="bi bi-x-circle-fill"></i> Reset
                    </a>
                @endif
            </div>

            <!-- Kanan: Search Bar -->
            <div class="w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pembimbing (nama, NIP, email)..." class="h-8 w-full px-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 dark:placeholder:text-slate-500">
            </div>
        </form>

        <!-- Table Wrapper Full Width -->
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-100/70 dark:bg-slate-700/60 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-3 py-3 w-12 text-center">NO</th>
                        <th class="px-4 py-3">NAMA PEMBIMBING</th>
                        <th class="px-4 py-3">NIP</th>
                        <th class="px-4 py-3">EMAIL</th>
                        <th class="px-4 py-3">DIVISI / BIDANG</th>
                        <th class="px-4 py-3 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($pembimbings as $pembimbing)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="px-3 py-3 text-center font-medium text-slate-500 dark:text-slate-400">{{ $loop->iteration + $pembimbings->firstItem() - 1 }}</td>
                            <td class="px-4 py-3">
                                <span class="max-w-[220px] truncate block font-semibold text-slate-800 dark:text-slate-100" title="{{ $pembimbing->name }}">
                                    {{ $pembimbing->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="whitespace-nowrap font-mono text-slate-600 dark:text-slate-300" title="{{ $pembimbing->nip ?? '-' }}">
                                    {{ $pembimbing->nip ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-300">{{ $pembimbing->email }}</td>
                            <td class="px-4 py-3 text-slate-700 dark:text-slate-200">
                                <span class="px-2.5 py-1 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-md inline-block">
                                    {{ $pembimbing->division->nama_divisi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.pembimbing.edit', $pembimbing->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-800 rounded-lg transition-colors text-decoration-none shadow-2xs" title="Edit Data Pembimbing">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.pembimbing.destroy', $pembimbing->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 border border-rose-200 dark:border-rose-800 rounded-lg transition-colors shadow-2xs"
                                                data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Nama</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($pembimbing->name) }}</div><div class='col-4 text-slate-500 font-semibold'>NIP</div><div class='col-8 font-monospace'>{{ $pembimbing->nip ?? '—' }}</div><div class='col-4 text-slate-500 font-semibold'>Divisi</div><div class='col-8'>{{ $pembimbing->division->nama_divisi ?? '—' }}</div></div>"
                                                data-simang-title="Hapus Akun Pembimbing"
                                                data-simang-icon="bi-trash-fill"
                                                data-simang-theme="rose"
                                                data-simang-alert="Akun pembimbing ini akan dihapus. Hak akses login pembimbing ke portal SIMAGANG akan dinonaktifkan."
                                                data-simang-btn-text="Ya, Hapus Pembimbing"
                                                data-simang-btn-icon="bi-trash-fill"
                                                title="Hapus Akun Pembimbing">
                                            <i class="bi bi-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                <i class="bi bi-person-x fs-2 d-block mb-1 opacity-50"></i>
                                <span class="text-xs">Belum ada data pembimbing yang cocok dengan kriteria pencarian.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembimbings->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="text-xs text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $pembimbings->firstItem() ?? 0 }} - {{ $pembimbings->lastItem() ?? 0 }} dari {{ $pembimbings->total() }} data pembimbing
                </div>
                <div>
                    {{ $pembimbings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

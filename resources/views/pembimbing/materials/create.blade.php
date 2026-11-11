@extends('layouts.app')
@section('title', 'Tambah Materi & Penugasan')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0 font-bold text-slate-800 dark:text-slate-100">Tambah Materi &amp; Penugasan</h1>
            <p class="text-xs text-slate-500 mb-0 mt-0.5">
                @if($specificUser)
                    Berikan penugasan mandiri terarah khusus untuk <strong>{{ $specificUser->name }}</strong>.
                @else
                    Terbitkan materi bersama satu divisi atau pilih beberapa anak magang untuk tugas mandiri.
                @endif
            </p>
        </div>
        @if($specificUser)
            <a href="{{ route('pembimbing.materials.personal', $specificUser->id) }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Peserta
            </a>
        @else
            <a href="{{ route('pembimbing.materials.index', ['tab' => $targetType === 'custom' ? 'mandiri' : 'divisi']) }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        @endif
    </div>

    <div class="card shadow-sm mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('pembimbing.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($specificUser)
                    {{-- =========================================================================
                         KONDISI 1: DIBUKA DARI BARIS TABEL / DOSSIER PESERTA
                         TIDAK MENAMPILKAN PILIHAN RADIO / CHECKBOX ANAK LAIN KARENA SUDAH TERPILIH
                         ========================================================================= --}}
                    <div class="mb-3.5 p-3 sm:p-3.5 rounded-xl border border-indigo-200 dark:border-indigo-800/80 bg-indigo-50/70 dark:bg-indigo-950/40 shadow-xs">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-700 dark:text-indigo-300 d-inline-flex align-items-center">
                                <i class="bi bi-person-check-fill me-1.5 text-indigo-600"></i> Penugasan Mandiri Terarah (Perorangan)
                            </span>
                            <a href="{{ route('pembimbing.materials.create', ['target' => 'custom']) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-decoration-none d-inline-flex align-items-center">
                                <i class="bi bi-arrow-left-right me-1"></i> Ganti ke Penugasan Umum / Pilih Peserta Lain
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-3 p-2 bg-white dark:bg-slate-900 rounded-lg border border-indigo-100 dark:border-indigo-900/50">
                            @if(!empty($specificUser->avatar))
                                <img src="{{ Storage::url($specificUser->avatar) }}" alt="{{ $specificUser->name }}" class="w-11 h-11 rounded-full object-cover border border-indigo-200 shrink-0">
                            @else
                                <div class="w-11 h-11 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm shrink-0 border border-indigo-200">
                                    {{ strtoupper(substr($specificUser->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-0">{{ $specificUser->name }}</h2>
                                    <span class="badge bg-indigo-100 text-indigo-700 border border-indigo-200 text-[10px]">Penerima Tugas</span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0 mt-0.5 truncate">
                                    {{ $specificUser->profile->institusi ?? '-' }} &bull; {{ $specificUser->position->nama_posisi ?? 'Peserta Magang' }} ({{ $specificUser->profile->jurusan ?? '-' }})
                                </p>
                            </div>
                        </div>

                        <!-- Parameter Hidden Direct Assignment -->
                        <input type="hidden" name="target_type" value="custom">
                        <input type="hidden" name="assigned_users[]" value="{{ $specificUser->id }}">
                        <input type="hidden" name="specific_user_name" value="{{ $specificUser->name }}">
                        <input type="hidden" name="specific_user_id" value="{{ $specificUser->id }}">
                    </div>

                @else
                    {{-- =========================================================================
                         KONDISI 2: DITAMBAHKAN MELALUI TOMBOL SUDUT KANAN ATAS
                         MENAMPILKAN PILIHAN DIVISI VS MANDIRI & DAFTAR CHECKBOX PESERTA
                         ========================================================================= --}}
                    <div class="mb-3.5 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/40">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-2">
                            <i class="bi bi-bullseye me-1 text-primary"></i> Target Penerima Penugasan
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2.5">
                            <!-- Opsi 1: Seluruh Divisi -->
                            <label id="labelTargetAll" class="border rounded-lg p-2.5 cursor-pointer transition-all flex items-start gap-2.5 {{ (old('target_type', $targetType) === 'all') ? 'border-blue-500 bg-blue-50/60 dark:bg-blue-950/40' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-100' }}">
                                <input type="radio" name="target_type" value="all" class="mt-0.5 text-blue-600 focus:ring-blue-500" {{ old('target_type', $targetType) === 'all' ? 'checked' : '' }} onchange="toggleTargetPicker('all')">
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                        <i class="bi bi-people-fill text-blue-600"></i> Seluruh Peserta Divisi (Bersama)
                                    </div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        Materi pembelajaran atau penugasan umum untuk seluruh anak magang di divisi Anda.
                                    </div>
                                </div>
                            </label>

                            <!-- Opsi 2: Peserta Tertentu (Mandiri) -->
                            <label id="labelTargetCustom" class="border rounded-lg p-2.5 cursor-pointer transition-all flex items-start gap-2.5 {{ (old('target_type', $targetType) === 'custom') ? 'border-indigo-500 bg-indigo-50/60 dark:bg-indigo-950/40' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-100' }}">
                                <input type="radio" name="target_type" value="custom" class="mt-0.5 text-indigo-600 focus:ring-indigo-500" {{ old('target_type', $targetType) === 'custom' ? 'checked' : '' }} onchange="toggleTargetPicker('custom')">
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                        <i class="bi bi-person-check-fill text-indigo-600"></i> Penugasan Mandiri (Pilih Peserta)
                                    </div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        Khusus untuk satu atau beberapa anak magang sesuai keahliannya.
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Interactive Intern Picker (Muncul jika target_type == custom) -->
                        <div id="customInternPicker" style="{{ old('target_type', $targetType) === 'custom' ? '' : 'display: none;' }}" class="pt-2.5 border-t border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-2 flex-wrap gap-1.5">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1">
                                    <i class="bi bi-check2-square text-indigo-600"></i>
                                    Pilih Anak Magang yang Ditugaskan:
                                    <span id="selectedInternCountBadge" class="ms-1 px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-700">0 dipilih</span>
                                </span>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="btn btn-outline-secondary btn-xs py-0.5 px-2 text-[10px]" onclick="selectAllInterns(true)">Pilih Semua</button>
                                    <button type="button" class="btn btn-outline-secondary btn-xs py-0.5 px-2 text-[10px]" onclick="selectAllInterns(false)">Batal Pilih</button>
                                </div>
                            </div>

                            <!-- Filter Pencarian Peserta didalam Picker -->
                            <div class="relative mb-2">
                                <input type="text" id="internFilterInput" placeholder="Ketik nama atau kampus untuk mencari..." onkeyup="filterInternsList(this.value)" class="h-7 w-full pl-7 pr-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded text-xs text-slate-700 dark:text-slate-200 placeholder:text-slate-400 shadow-xs">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-slate-400">
                                    <i class="bi bi-search text-[10px]"></i>
                                </span>
                            </div>

                            <div id="internCheckboxContainer" class="max-h-56 overflow-y-auto space-y-1 p-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg">
                                @forelse($activeInterns as $intern)
                                    @php
                                        $isPreselected = old('assigned_users')
                                            ? in_array($intern->id, (array) old('assigned_users'))
                                            : ((string) $preselectedUserId === (string) $intern->id);
                                    @endphp
                                    <label class="intern-item-row flex items-center justify-between p-2 rounded hover:bg-indigo-50/50 dark:hover:bg-slate-800/80 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors" data-name="{{ strtolower($intern->name) }}" data-campus="{{ strtolower($intern->profile->institusi ?? '') }}">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <input type="checkbox" name="assigned_users[]" value="{{ $intern->id }}" class="intern-checkbox rounded text-indigo-600 focus:ring-indigo-500" {{ $isPreselected ? 'checked' : '' }} onchange="updateSelectedInternsCount()">
                                            <div class="min-w-0">
                                                <span class="text-xs font-bold text-slate-900 dark:text-slate-100 block truncate">{{ $intern->name }}</span>
                                                <span class="text-[11px] text-slate-500 block truncate">
                                                    {{ $intern->profile->institusi ?? '-' }} &bull; {{ $intern->position->nama_posisi ?? 'Peserta Magang' }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="p-3 text-center text-xs text-slate-500 italic">
                                        Tidak ada anak magang aktif di divisi Anda saat ini.
                                    </div>
                                @endforelse
                            </div>
                            @error('assigned_users')
                                <div class="text-danger text-xs mt-1.5 font-semibold d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul Materi / Tugas</label>
                    <input type="text" name="title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Pembuatan Modul Integrasi API Database atau Liputan Berita" required>
                    @error('title')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi &amp; Instruksi Pengerjaan</label>
                    <textarea name="description" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan detail materi, petunjuk pengerjaan, atau kriteria hasil yang diharapkan..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 d-flex align-items-center justify-content-between flex-wrap gap-1">
                        <span><i class="bi bi-folder-fill me-1 text-primary"></i>Berkas / Dokumen Lampiran (Opsional)</span>
                        <span style="background-color: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; padding: 0.2rem 0.55rem; border-radius: 9999px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.02em;">
                            <i class="bi bi-files me-1"></i>Bisa Banyak File &bull; Maks. 100 MB/File
                        </span>
                    </label>
                    <input type="file" name="files[]" id="materiFileInput" multiple class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('files') is-invalid @enderror @error('files.*') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.rtf,.odt,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.webp,.gif,.svg,.mp4,.mkv,.avi,.mov,.webm,.mp3,.wav">
                    <div class="form-text text-muted text-xs mt-1">
                        Format didukung: <strong>Dokumen (PDF, Word, Excel, PPT), Arsip (ZIP, RAR, 7Z), Gambar, Video (MP4, MKV), & Audio</strong>. Maksimal 100 MB per file.
                    </div>
                    <div id="filePreviewList" class="mt-2 d-none">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Daftar Berkas Terpilih:</div>
                        <div id="filePreviewItems" class="d-flex flex-column gap-1"></div>
                    </div>
                    @error('files')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                    @error('files.*')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                    @error('file_materi')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Link YouTube Video Pembelajaran (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                    @error('youtube_url')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <hr class="my-3 border-slate-200">

                <div class="mb-2.5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task', ($specificUser || $targetType === 'custom') ? '1' : '') ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label text-xs font-bold !text-slate-800 dark:!text-slate-200" for="isTask">
                            Tandai sebagai Tugas yang Harus Dikumpulkan Peserta
                        </label>
                    </div>
                    <div class="form-text text-muted text-xs mt-0.5">
                        Jika dicentang, peserta dapat mengunggah file hasil kerja / tautan laporan dan pembimbing dapat menilai hasilnya.
                    </div>
                    @error('is_task')<div class="text-danger text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div id="taskFieldsContainer" style="{{ old('is_task', ($specificUser || $targetType === 'custom') ? '1' : '') ? '' : 'display: none;' }}">
                    <div class="mb-2.5">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan Hasil Kerja
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select rounded-2 py-1.5 px-2.5 text-xs @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan: File / Link Bebas) —</option>
                            @foreach($submissionTypes as $type)
                                <option value="{{ $type->value }}" {{ old('submission_type') === $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted text-xs mt-0.5">
                            Tentukan format khusus yang harus dikumpulkan peserta. Jika dikosongkan, peserta bebas mengumpulkan berkas atau link.
                        </div>
                        @error('submission_type')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-2.5">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Batas Waktu Pengumpulan (Deadline) - Opsional</label>
                        <input type="datetime-local" name="deadline" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                        @error('deadline')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="pt-2 border-t mt-3 d-flex justify-content-between align-items-center">
                    @if($specificUser)
                        <a href="{{ route('pembimbing.materials.personal', $specificUser->id) }}" class="btn btn-outline-secondary btn-sm py-1.5 px-3 text-xs font-semibold">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('pembimbing.materials.index', ['tab' => $targetType === 'custom' ? 'mandiri' : 'divisi']) }}" class="btn btn-outline-secondary btn-sm py-1.5 px-3 text-xs font-semibold">
                            Batal
                        </a>
                    @endif
                    <button type="button" id="btnConfirmSubmitMateri" class="btn btn-primary btn-sm py-1.5 px-4 text-xs font-bold" style="background-color: #2563eb !important;">
                        <i class="bi bi-save me-1"></i> Terbitkan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleTargetPicker(target) {
        var picker = document.getElementById('customInternPicker');
        var labelAll = document.getElementById('labelTargetAll');
        var labelCustom = document.getElementById('labelTargetCustom');
        var isTaskCheckbox = document.getElementById('isTask');

        if (!picker || !labelAll || !labelCustom) return;

        if (target === 'custom') {
            picker.style.display = '';
            labelCustom.classList.add('border-indigo-500', 'bg-indigo-50/60');
            labelCustom.classList.remove('border-slate-200');
            labelAll.classList.remove('border-blue-500', 'bg-blue-50/60');
            labelAll.classList.add('border-slate-200');
            if (!isTaskCheckbox.checked) {
                isTaskCheckbox.checked = true;
                toggleTaskFields(true);
            }
        } else {
            picker.style.display = 'none';
            labelAll.classList.add('border-blue-500', 'bg-blue-50/60');
            labelAll.classList.remove('border-slate-200');
            labelCustom.classList.remove('border-indigo-500', 'bg-indigo-50/60');
            labelCustom.classList.add('border-slate-200');
        }
        updateSelectedInternsCount();
    }

    function toggleTaskFields(isChecked) {
        var container = document.getElementById('taskFieldsContainer');
        if (isChecked) {
            container.style.display = '';
        } else {
            container.style.display = 'none';
            document.getElementById('submissionType').value = '';
        }
    }

    function selectAllInterns(select) {
        var checkboxes = document.querySelectorAll('.intern-checkbox');
        checkboxes.forEach(function (cb) {
            var row = cb.closest('.intern-item-row');
            if (row && row.style.display !== 'none') {
                cb.checked = select;
            }
        });
        updateSelectedInternsCount();
    }

    function updateSelectedInternsCount() {
        var checked = document.querySelectorAll('.intern-checkbox:checked').length;
        var badge = document.getElementById('selectedInternCountBadge');
        if (badge) {
            badge.innerText = checked + ' dipilih';
        }
    }

    function filterInternsList(term) {
        var lower = term.toLowerCase().trim();
        var rows = document.querySelectorAll('.intern-item-row');
        rows.forEach(function (row) {
            var name = row.getAttribute('data-name') || '';
            var campus = row.getAttribute('data-campus') || '';
            if (name.indexOf(lower) !== -1 || campus.indexOf(lower) !== -1) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        var k = 1024, dm = 1, sizes = ['B', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    var fileInput = document.getElementById('materiFileInput');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            var previewList = document.getElementById('filePreviewList');
            var previewItems = document.getElementById('filePreviewItems');
            previewItems.innerHTML = '';
            if (this.files && this.files.length > 0) {
                previewList.classList.remove('d-none');
                Array.from(this.files).forEach(function (f) {
                    var div = document.createElement('div');
                    div.className = 'p-1.5 px-2.5 rounded bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs d-flex align-items-center justify-content-between gap-2';
                    div.innerHTML = '<span class="text-truncate fw-semibold text-slate-800 dark:text-slate-200" style="max-width: 78%;">' +
                        '<i class="bi bi-file-earmark-check-fill text-primary me-1.5"></i>' + f.name.replace(/</g, '&lt;') +
                        '</span>' +
                        '<span style="background-color: #e2e8f0 !important; color: #0f172a !important; font-weight: 700; font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 6px; border: 1px solid #cbd5e1; white-space: nowrap; flex-shrink: 0;">' +
                        '<i class="bi bi-hdd-fill text-slate-500 me-1" style="font-size: 0.65rem;"></i>' + formatBytes(f.size) +
                        '</span>';
                    previewItems.appendChild(div);
                });
            } else {
                previewList.classList.add('d-none');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateSelectedInternsCount();

        var btn = document.getElementById('btnConfirmSubmitMateri');
        if (btn) {
            btn.addEventListener('click', function () {
                var form = btn.closest('form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                var specificUserEl = form.querySelector('input[name="specific_user_id"]');
                var specificUserName = form.querySelector('input[name="specific_user_name"]')?.value;

                var targetType = 'all';
                if (specificUserEl) {
                    targetType = 'custom';
                } else {
                    targetType = form.querySelector('[name="target_type"]:checked')?.value || 'all';
                    if (targetType === 'custom') {
                        var checkedCount = form.querySelectorAll('.intern-checkbox:checked').length;
                        if (checkedCount === 0) {
                            alert('Silakan pilih minimal 1 peserta untuk penugasan mandiri.');
                            return;
                        }
                    }
                }

                var title = form.querySelector('[name="title"]').value || 'Materi Baru';
                var isTask = form.querySelector('[name="is_task"]').checked;
                
                var targetText = '';
                var alertMsg = '';

                if (specificUserName) {
                    targetText = 'Khusus: ' + specificUserName;
                    alertMsg = 'Penugasan mandiri ini akan langsung dikirimkan ke akun peserta ' + specificUserName + ' dan masuk ke riwayat tugas pribadinya.';
                } else if (targetType === 'custom') {
                    targetText = 'Penugasan Mandiri (Peserta Terpilih)';
                    alertMsg = 'Penugasan ini hanya akan dikirimkan kepada peserta terpilih dan masuk ke riwayat penugasan pribadi mereka.';
                } else {
                    targetText = 'Seluruh Divisi (Bersama)';
                    alertMsg = 'Materi ini akan langsung dipublikasikan dan dapat diakses oleh seluruh peserta bimbingan divisi Anda.';
                }

                if (window.SimangModal && typeof window.SimangModal.confirm === 'function') {
                    window.SimangModal.confirm({
                        title: 'Konfirmasi Terbitkan Penugasan',
                        icon: (targetType === 'custom' || specificUserEl) ? 'bi-person-check-fill' : 'bi-cloud-arrow-up-fill',
                        theme: (targetType === 'custom' || specificUserEl) ? 'indigo' : 'blue',
                        summaryHtml: '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Judul</div><div class="col-8 fw-bold text-slate-900">' + title.replace(/</g, '&lt;') + '</div><div class="col-4 text-slate-500 font-semibold">Sasaran</div><div class="col-8 fw-bold text-indigo-700">' + targetText + '</div></div>',
                        alertText: alertMsg,
                        confirmText: 'Ya, Terbitkan Sekarang',
                        confirmIcon: 'bi-send-check',
                        form: form
                    });
                } else {
                    form.submit();
                }
            });
        }
    });
</script>
@endsection

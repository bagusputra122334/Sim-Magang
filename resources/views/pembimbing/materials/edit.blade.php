@extends('layouts.app')
@section('title', 'Edit Materi & Penugasan')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0 font-bold text-slate-800 dark:text-slate-100">Edit Materi &amp; Penugasan</h1>
            <p class="text-xs text-slate-500 mb-0 mt-0.5">Perbarui rincian materi, sasaran peserta, atau batas waktu penugasan.</p>
        </div>
        <a href="{{ route('pembimbing.materials.index', ['tab' => $material->target_type === 'custom' ? 'mandiri' : 'divisi']) }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('pembimbing.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @php
                    $currentTargetType = old('target_type', $material->target_type ?? 'all');
                @endphp

                <!-- Target Penugasan (Divisi vs Mandiri) -->
                <div class="mb-3.5 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/40">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-2">
                        <i class="bi bi-bullseye me-1 text-primary"></i> Target Penerima Penugasan
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2.5">
                        <!-- Opsi 1: Seluruh Divisi -->
                        <label id="labelTargetAll" class="border rounded-lg p-2.5 cursor-pointer transition-all flex items-start gap-2.5 {{ $currentTargetType === 'all' ? 'border-blue-500 bg-blue-50/60 dark:bg-blue-950/40' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-100' }}">
                            <input type="radio" name="target_type" value="all" class="mt-0.5 text-blue-600 focus:ring-blue-500" {{ $currentTargetType === 'all' ? 'checked' : '' }} onchange="toggleTargetPicker('all')">
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
                        <label id="labelTargetCustom" class="border rounded-lg p-2.5 cursor-pointer transition-all flex items-start gap-2.5 {{ $currentTargetType === 'custom' ? 'border-indigo-500 bg-indigo-50/60 dark:bg-indigo-950/40' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-100' }}">
                            <input type="radio" name="target_type" value="custom" class="mt-0.5 text-indigo-600 focus:ring-indigo-500" {{ $currentTargetType === 'custom' ? 'checked' : '' }} onchange="toggleTargetPicker('custom')">
                            <div>
                                <div class="text-xs font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <i class="bi bi-person-check-fill text-indigo-600"></i> Penugasan Mandiri (Peserta Tertentu)
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    Khusus untuk anak magang spesifik sesuai keahlian (Programmer, Jaringan, Desain, dll).
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Interactive Intern Picker (Muncul jika target_type == custom) -->
                    <div id="customInternPicker" style="{{ $currentTargetType === 'custom' ? '' : 'display: none;' }}" class="pt-2.5 border-t border-slate-200 dark:border-slate-700">
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
                                    $checked = old('assigned_users')
                                        ? in_array($intern->id, (array) old('assigned_users'))
                                        : in_array($intern->id, $assignedUserIds);
                                @endphp
                                <label class="intern-item-row flex items-center justify-between p-2 rounded hover:bg-indigo-50/50 dark:hover:bg-slate-800/80 cursor-pointer border border-transparent hover:border-indigo-100 transition-colors" data-name="{{ strtolower($intern->name) }}" data-campus="{{ strtolower($intern->profile->institusi ?? '') }}">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <input type="checkbox" name="assigned_users[]" value="{{ $intern->id }}" class="intern-checkbox rounded text-indigo-600 focus:ring-indigo-500" {{ $checked ? 'checked' : '' }} onchange="updateSelectedInternsCount()">
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-900 dark:text-slate-100 block truncate">{{ $intern->name }}</span>
                                            <span class="text-[11px] text-slate-500 block truncate">
                                                {{ $intern->profile->institusi ?? '-' }} &bull; {{ $intern->position->nama_posisi ?? 'Peserta Magang' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if(in_array($intern->id, $assignedUserIds))
                                        <span class="badge bg-indigo-100 text-indigo-700 border border-indigo-200 text-[10px]">Saat Ini Ditugaskan</span>
                                    @endif
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

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul Materi / Tugas</label>
                    <input type="text" name="title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title', $material->title) }}" required>
                    @error('title')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi &amp; Instruksi Pengerjaan</label>
                    <textarea name="description" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $material->description) }}</textarea>
                    @error('description')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 d-flex align-items-center justify-content-between flex-wrap gap-1">
                        <span><i class="bi bi-folder-fill me-1 text-primary"></i>Berkas / Dokumen Lampiran (Opsional)</span>
                        <span style="background-color: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; padding: 0.2rem 0.55rem; border-radius: 9999px; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.02em;">
                            <i class="bi bi-files me-1"></i>Bisa Banyak File &bull; Maks. 100 MB/File
                        </span>
                    </label>

                    @php
                        $attachedFiles = $material->all_files;
                    @endphp

                    @if(!empty($attachedFiles) && count($attachedFiles) > 0)
                        <div class="mb-2">
                            <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Berkas Tersimpan (Centang jika ingin menghapus):</div>
                            <div class="d-flex flex-column gap-1.5">
                                @foreach($attachedFiles as $f)
                                    <div class="p-2 border rounded-2 bg-light dark:bg-slate-800 d-flex align-items-center justify-content-between gap-2.5 text-xs">
                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                            <i class="bi bi-file-earmark-check-fill fs-5 text-primary flex-shrink-0"></i>
                                            <div class="min-w-0">
                                                <div class="fw-bold text-truncate text-xs">
                                                    {{ $f['name'] }}
                                                </div>
                                                @if(!empty($f['size']))
                                                    <span class="d-inline-flex align-items-center mt-0.5" style="background-color: #f1f5f9; color: #0f172a !important; font-weight: 700; font-size: 0.68rem; padding: 0.1rem 0.45rem; border-radius: 4px; border: 1px solid #cbd5e1;">
                                                        <i class="bi bi-hdd-fill text-slate-400 me-1" style="font-size: 0.6rem;"></i>
                                                        {{ $f['size'] >= 1048576 ? number_format($f['size'] / 1048576, 1) . ' MB' : number_format($f['size'] / 1024, 0) . ' KB' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2.5 flex-shrink-0">
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($f['path']) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0.5 px-2 text-xs rounded-pill">
                                                <i class="bi bi-download me-1"></i>Unduh
                                            </a>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" name="remove_files[]" value="{{ $f['path'] }}" id="removeFile{{ $loop->index }}">
                                                <label class="form-check-label text-xs text-danger fw-bold cursor-pointer" for="removeFile{{ $loop->index }}">
                                                    Hapus
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-2">
                        <label class="form-label text-xs text-slate-600 dark:text-slate-400 font-semibold mb-1">Tambah / Unggah Berkas Baru:</label>
                        <input type="file" name="files[]" id="materiFileInput" multiple class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('files') is-invalid @enderror @error('files.*') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv,.txt,.rtf,.odt,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.webp,.gif,.svg,.mp4,.mkv,.avi,.mov,.webm,.mp3,.wav">
                        <div class="form-text text-muted text-xs mt-1">
                            Format didukung: <strong>Dokumen, Arsip (ZIP, RAR, 7Z), Gambar, Video (MP4, MKV), & Audio</strong>. Maksimal 100 MB per file. Bisa pilih lebih dari satu.
                        </div>
                        <div id="filePreviewList" class="mt-2 d-none">
                            <div class="text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Daftar Berkas Baru yang Akan Diunggah:</div>
                            <div id="filePreviewItems" class="d-flex flex-column gap-1"></div>
                        </div>
                    </div>
                    @error('files')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                    @error('files.*')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                    @error('file_materi')<div class="invalid-feedback text-xs d-block">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Link YouTube Video Pembelajaran (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $material->youtube_url) }}">
                    @error('youtube_url')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <hr class="my-3 border-slate-200">

                <div class="mb-2.5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task', $material->is_task) ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label text-xs font-bold !text-slate-800 dark:!text-slate-200" for="isTask">
                            Tandai sebagai Tugas yang Harus Dikumpulkan Peserta
                        </label>
                    </div>
                    <div class="form-text text-muted text-xs mt-0.5">
                        Jika dicentang, materi ini akan dilengkapi dengan instruksi tugas dan batas waktu pengumpulan.
                    </div>
                    @error('is_task')<div class="text-danger text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                @php
                    $showTaskFields = old('is_task', $material->is_task) ? true : false;
                    $currentSubmissionType = old('submission_type', $material->submission_type?->value ?? '');
                @endphp

                <div id="taskFieldsContainer" style="{{ $showTaskFields ? '' : 'display: none;' }}">
                    <div class="mb-2.5">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan Hasil Kerja
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select rounded-2 py-1.5 px-2.5 text-xs @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan: File / Link Bebas) —</option>
                            @foreach($submissionTypes as $type)
                                <option value="{{ $type->value }}" {{ $currentSubmissionType === $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted text-xs mt-0.5">
                            Tentukan format khusus yang harus dikumpulkan peserta. Jika kosong, peserta dapat mengumpulkan file atau link.
                        </div>
                        @error('submission_type')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-2.5">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Batas Waktu Pengumpulan (Deadline) - Opsional</label>
                        <input type="datetime-local" name="deadline" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('deadline') is-invalid @enderror" value="{{ old('deadline', $material->deadline ? $material->deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('deadline')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="pt-2 border-t mt-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('pembimbing.materials.index', ['tab' => $currentTargetType === 'custom' ? 'mandiri' : 'divisi']) }}" class="btn btn-outline-secondary btn-sm py-1.5 px-3 text-xs font-semibold">
                        Batal
                    </a>
                    <button type="button" id="btnConfirmUpdateMateri" class="btn btn-primary btn-sm py-1.5 px-4 text-xs font-bold">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
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

        if (target === 'custom') {
            picker.style.display = '';
            labelCustom.classList.add('border-indigo-500', 'bg-indigo-50/60');
            labelCustom.classList.remove('border-slate-200');
            labelAll.classList.remove('border-blue-500', 'bg-blue-50/60');
            labelAll.classList.add('border-slate-200');
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
                        '<i class="bi bi-file-earmark-plus-fill text-primary me-1.5"></i>' + f.name.replace(/</g, '&lt;') +
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

        var btn = document.getElementById('btnConfirmUpdateMateri');
        if (btn) {
            btn.addEventListener('click', function () {
                var form = btn.closest('form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                var targetType = form.querySelector('[name="target_type"]:checked')?.value || 'all';
                if (targetType === 'custom') {
                    var checkedCount = form.querySelectorAll('.intern-checkbox:checked').length;
                    if (checkedCount === 0) {
                        alert('Silakan pilih minimal 1 peserta untuk penugasan mandiri.');
                        return;
                    }
                }

                var title = form.querySelector('[name="title"]').value || 'Materi';

                if (window.SimangModal && typeof window.SimangModal.confirm === 'function') {
                    window.SimangModal.confirm({
                        title: 'Konfirmasi Perbarui Penugasan',
                        icon: 'bi-pencil-square',
                        theme: 'amber',
                        summaryHtml: '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Judul Materi</div><div class="col-8 fw-bold text-slate-900">' + title.replace(/</g, '&lt;') + '</div></div>',
                        alertText: 'Perubahan materi dan sasaran penugasan akan langsung disinkronkan ke seluruh peserta.',
                        confirmText: 'Ya, Simpan Perubahan',
                        confirmIcon: 'bi-check-lg',
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

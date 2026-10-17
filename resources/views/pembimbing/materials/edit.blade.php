@extends('layouts.app')
@section('title', 'Edit Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 font-bold text-slate-800 dark:text-slate-100">Edit Materi</h1>
        <a href="{{ route('pembimbing.materials.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('pembimbing.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul Materi</label>
                    <input type="text" name="title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title', $material->title) }}" required>
                    @error('title')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $material->description) }}</textarea>
                    @error('description')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                        <i class="bi bi-file-earmark-text me-1 text-gray-500"></i>Dokumen Materi (Opsional)
                    </label>
                    @if(!empty($material->file_materi))
                        <div class="mb-2 p-2 border rounded-2 bg-light d-flex align-items-center justify-content-between gap-2.5 text-xs">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                <i class="bi bi-file-earmark-text fs-4 text-primary flex-shrink-0"></i>
                                <div class="min-w-0">
                                    <div class="fw-bold text-truncate text-xs">
                                        {{ basename($material->file_materi) }}
                                    </div>
                                    <small class="text-muted text-xs">
                                        {{ \Illuminate\Support\Str::of($material->file_materi)->after('materials/documents/')->limit(32) }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_materi) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0.5 px-2 text-xs rounded-pill">
                                    <i class="bi bi-download me-1"></i>Unduh
                                </a>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="remove_file_materi" id="removeFileMateri" value="1" {{ old('remove_file_materi') ? 'checked' : '' }}>
                                    <label class="form-check-label text-xs text-danger fw-bold" for="removeFileMateri">
                                        Hapus
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="file_materi" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('file_materi') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                    <div class="form-text text-muted text-xs mt-1">
                        Format: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</strong> (Maks. 10 MB). Jika file di-upload ulang, file lama akan otomatis dihapus.
                    </div>
                    @error('file_materi')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Link YouTube (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $material->youtube_url) }}">
                    @error('youtube_url')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <hr class="my-3 border-slate-200">

                <div class="mb-2.5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task', $material->is_task) ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label text-xs font-bold !text-slate-800 dark:!text-slate-200" for="isTask">
                            Tandai sebagai Tugas
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
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select rounded-2 py-1.5 px-2.5 text-xs @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan) —</option>
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
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Batas Waktu (Deadline) - Opsional</label>
                        <input type="datetime-local" name="deadline" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('deadline') is-invalid @enderror" value="{{ old('deadline', $material->deadline ? $material->deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('deadline')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="pt-2 border-t mt-3">
                    <button type="submit" class="btn btn-primary btn-sm py-1.5 px-3.5 text-xs font-bold">Update Materi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function toggleTaskFields(isChecked) {
        var container = document.getElementById('taskFieldsContainer');
        if (isChecked) {
            container.style.display = '';
        } else {
            container.style.display = 'none';
            document.getElementById('submissionType').value = '';
        }
    }
</script>
@endsection

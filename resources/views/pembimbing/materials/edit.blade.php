@extends('layouts.app')
@section('title', 'Edit Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Materi</h1>
        <a href="{{ route('pembimbing.materials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pembimbing.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $material->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $material->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-text me-1 text-gray-500"></i>Dokumen Materi (Opsional)
                    </label>
                    @if(!empty($material->file_materi))
                        <div class="mb-2 p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                <i class="bi bi-file-earmark-text fs-2 text-primary flex-shrink-0"></i>
                                <div class="min-w-0">
                                    <div class="fw-semibold small text-truncate">
                                        {{ basename($material->file_materi) }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::of($material->file_materi)->after('materials/documents/')->limit(32) }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_materi) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-download me-1"></i>Unduh
                                </a>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="remove_file_materi" id="removeFileMateri" value="1" {{ old('remove_file_materi') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-danger fw-semibold" for="removeFileMateri">
                                        Hapus
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="file_materi" class="form-control @error('file_materi') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                    <div class="form-text text-muted small mt-1">
                        Format: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</strong> (Maks. 10 MB). Jika file di-upload ulang, file lama akan otomatis dihapus.
                    </div>
                    @error('file_materi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Link YouTube (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $material->youtube_url) }}">
                    @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task', $material->is_task) ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label" for="isTask">
                            <strong>Tandai sebagai Tugas</strong>
                        </label>
                    </div>
                    <div class="form-text text-muted small mt-1">
                        Jika dicentang, materi ini akan dilengkapi dengan instruksi tugas dan batas waktu pengumpulan.
                    </div>
                    @error('is_task')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                @php
                    $showTaskFields = old('is_task', $material->is_task) ? true : false;
                    $currentSubmissionType = old('submission_type', $material->submission_type?->value ?? '');
                @endphp

                <div id="taskFieldsContainer" style="{{ $showTaskFields ? '' : 'display: none;' }}">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan) —</option>
                            @foreach($submissionTypes as $type)
                                <option value="{{ $type->value }}" {{ $currentSubmissionType === $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted small mt-1">
                            Tentukan format khusus yang harus dikumpulkan peserta. Jika kosong, peserta dapat mengumpulkan file atau link.
                        </div>
                        @error('submission_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Batas Waktu (Deadline) - Opsional</label>
                        <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline', $material->deadline ? $material->deadline->format('Y-m-d\TH:i') : '') }}">
                        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Materi</button>
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

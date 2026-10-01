@extends('layouts.app')
@section('title', 'Tambah Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Materi Baru</h1>
        <a href="{{ route('pembimbing.materials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pembimbing.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Judul Materi</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-text me-1 text-gray-500"></i>Dokumen Materi (Opsional)
                    </label>
                    <input type="file" name="file_materi" class="form-control @error('file_materi') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                    <div class="form-text text-muted small mt-1">
                        Format: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</strong> (Maks. 10 MB). Boleh dikosongkan.
                    </div>
                    @error('file_materi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Link YouTube (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url') }}">
                    @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task') ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label" for="isTask">
                            <strong>Tandai sebagai Tugas</strong>
                        </label>
                    </div>
                    <div class="form-text text-muted small mt-1">
                        Jika dicentang, materi ini akan dilengkapi dengan instruksi tugas dan batas waktu pengumpulan.
                    </div>
                    @error('is_task')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div id="taskFieldsContainer" style="{{ old('is_task') ? '' : 'display: none;' }}">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan) —</option>
                            @foreach($submissionTypes as $type)
                                <option value="{{ $type->value }}" {{ old('submission_type') === $type->value ? 'selected' : '' }}>
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
                        <label class="form-label">Instruksi Tugas (Opsional)</label>
                        <textarea name="task_instruction" class="form-control @error('task_instruction') is-invalid @enderror" rows="4" placeholder="Masukkan instruksi detail untuk tugas yang harus dikerjakan peserta...">{{ old('task_instruction') }}</textarea>
                        @error('task_instruction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batas Waktu (Deadline) - Opsional</label>
                        <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Materi</button>
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

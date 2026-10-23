@extends('layouts.app')
@section('title', 'Tambah Materi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 font-bold text-slate-800 dark:text-slate-100">Tambah Materi Baru</h1>
        <a href="{{ route('pembimbing.materials.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('pembimbing.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul Materi</label>
                    <input type="text" name="title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                        <i class="bi bi-file-earmark-text me-1 text-gray-500"></i>Dokumen Materi (Opsional)
                    </label>
                    <input type="file" name="file_materi" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('file_materi') is-invalid @enderror" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                    <div class="form-text text-muted text-xs mt-1">
                        Format: <strong>PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</strong> (Maks. 10 MB). Boleh dikosongkan.
                    </div>
                    @error('file_materi')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Link YouTube (Opsional)</label>
                    <input type="url" name="youtube_url" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url') }}">
                    @error('youtube_url')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <hr class="my-3 border-slate-200">

                <div class="mb-2.5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_task" id="isTask" {{ old('is_task') ? 'checked' : '' }} onchange="toggleTaskFields(this.checked)">
                        <label class="form-check-label text-xs font-bold !text-slate-800 dark:!text-slate-200" for="isTask">
                            Tandai sebagai Tugas
                        </label>
                    </div>
                    <div class="form-text text-muted text-xs mt-0.5">
                        Jika dicentang, materi ini akan dilengkapi dengan instruksi tugas dan batas waktu pengumpulan.
                    </div>
                    @error('is_task')<div class="text-danger text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div id="taskFieldsContainer" style="{{ old('is_task') ? '' : 'display: none;' }}">
                    <div class="mb-2.5">
                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                            <i class="bi bi-paperclip me-1 text-gray-500"></i>Format Pengumpulan
                        </label>
                        <select name="submission_type" id="submissionType" class="form-select rounded-2 py-1.5 px-2.5 text-xs @error('submission_type') is-invalid @enderror">
                            <option value="">— Umum (Semua Format Diizinkan) —</option>
                            @foreach($submissionTypes as $type)
                                <option value="{{ $type->value }}" {{ old('submission_type') === $type->value ? 'selected' : '' }}>
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
                        <input type="datetime-local" name="deadline" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                        @error('deadline')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="pt-2 border-t mt-3">
                    <button type="button" id="btnConfirmSubmitMateri" class="btn btn-primary btn-sm py-1.5 px-3.5 text-xs font-bold">
                        <i class="bi bi-save me-1"></i> Simpan Materi
                    </button>
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

    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('btnConfirmSubmitMateri');
        if (btn) {
            btn.addEventListener('click', function () {
                var form = btn.closest('form');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                var title = form.querySelector('[name="title"]').value || 'Materi Baru';
                var isTask = form.querySelector('[name="is_task"]').checked;

                window.SimangModal.confirm({
                    title: 'Konfirmasi Terbitkan Materi',
                    icon: 'bi-cloud-arrow-up-fill',
                    theme: 'blue',
                    summaryHtml: '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Judul Materi</div><div class="col-8 fw-bold text-slate-900">' + title.replace(/</g, '&lt;') + '</div><div class="col-4 text-slate-500 font-semibold">Tipe Konten</div><div class="col-8 fw-bold ' + (isTask ? 'text-amber-600' : 'text-blue-600') + '">' + (isTask ? 'Materi &amp; Penugasan' : 'Materi Pembelajaran') + '</div></div>',
                    alertText: 'Materi ini akan langsung dipublikasikan dan dapat diakses oleh seluruh peserta bimbingan divisi Anda.',
                    confirmText: 'Ya, Terbitkan Materi',
                    confirmIcon: 'bi-send-check',
                    form: form
                });
            });
        }
    });
</script>
@endsection

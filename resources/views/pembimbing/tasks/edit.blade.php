@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0 font-bold text-slate-800 dark:text-slate-100">Edit Tugas</h1>
        <a href="{{ route('pembimbing.tasks.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-3 rounded-2">
        <div class="card-body p-3 sm:p-4">
            <form action="{{ route('pembimbing.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul Tugas</label>
                    <input type="text" name="title" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}" required>
                    @error('title')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi & Instruksi</label>
                    <textarea name="description" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $task->description) }}</textarea>
                    @error('description')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Format Pengumpulan</label>
                    <div class="pt-0.5">
                        @php
                            $formats = old('allowed_format', $task->allowed_format ?? []);
                        @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="pdf" id="formatPdf" {{ in_array('pdf', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label text-xs font-semibold text-slate-700" for="formatPdf">PDF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="zip" id="formatZip" {{ in_array('zip', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label text-xs font-semibold text-slate-700" for="formatZip">ZIP</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="link" id="formatLink" {{ in_array('link', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label text-xs font-semibold text-slate-700" for="formatLink">Link (URL)</label>
                        </div>
                    </div>
                    @error('allowed_format')<div class="text-danger text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-2.5">
                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="deadline" class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('deadline') is-invalid @enderror" value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required>
                    @error('deadline')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                </div>

                <div class="pt-2 border-t mt-3">
                    <button type="submit" class="btn btn-primary btn-sm py-1.5 px-3.5 text-xs font-bold">Update Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

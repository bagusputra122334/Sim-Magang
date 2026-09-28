@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Tugas</h1>
        <a href="{{ route('pembimbing.tasks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pembimbing.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Judul Tugas</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi & Instruksi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $task->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Format Pengumpulan</label>
                    <div>
                        @php
                            $formats = old('allowed_format', $task->allowed_format ?? []);
                        @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="pdf" id="formatPdf" {{ in_array('pdf', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label" for="formatPdf">PDF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="zip" id="formatZip" {{ in_array('zip', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label" for="formatZip">ZIP</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_format[]" value="link" id="formatLink" {{ in_array('link', $formats) ? 'checked' : '' }}>
                            <label class="form-check-label" for="formatLink">Link (URL)</label>
                        </div>
                    </div>
                    @error('allowed_format')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required>
                    @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Tugas</button>
            </form>
        </div>
    </div>
</div>
@endsection

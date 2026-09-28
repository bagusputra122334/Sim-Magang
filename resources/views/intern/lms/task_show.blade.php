@extends('layouts.app')
@section('title', 'Tugas: ' . $task->title)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $task->title }}</h1>
        <a href="{{ route('intern.lms.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Detail Tugas -->
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Instruksi Tugas</h6>
                    <span class="badge bg-danger">Deadline: {{ $task->deadline->format('d M Y H:i') }}</span>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(e($task->description)) !!}</p>
                    <hr>
                    <p class="mb-0"><strong>Format yang diizinkan:</strong> 
                        @foreach($task->allowed_format as $format)
                            <span class="badge bg-secondary">{{ strtoupper($format) }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Submission -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Pengumpulan Tugas</h6>
                </div>
                <div class="card-body">
                    @if($submission)
                        <div class="alert alert-info">
                            <strong>Status:</strong> {{ ucfirst($submission->status) }}<br>
                            <strong>Dikumpulkan pada:</strong> {{ $submission->updated_at->format('d M Y H:i') }}
                        </div>

                        @if($submission->grade_note)
                            <div class="alert alert-warning">
                                <strong>Catatan Reviewer:</strong><br>
                                {{ $submission->grade_note }}
                            </div>
                        @endif
                    @endif

                    @if(!$submission || in_array($submission->status, ['pending', 'submitted', 'revision']))
                        <form action="{{ route('intern.lms.task.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if(in_array('pdf', $task->allowed_format) || in_array('zip', $task->allowed_format))
                                <div class="mb-3">
                                    <label class="form-label">Upload File ({{ implode(', ', array_intersect(['pdf', 'zip'], $task->allowed_format)) }})</label>
                                    <input type="file" name="submission_file" class="form-control @error('submission_file') is-invalid @enderror" accept=".pdf,.zip">
                                    @error('submission_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($submission && $submission->file_path)
                                        <small class="text-success d-block mt-1">File saat ini: <a href="{{ Storage::url($submission->file_path) }}" target="_blank">Download</a></small>
                                    @endif
                                </div>
                            @endif

                            @if(in_array('link', $task->allowed_format))
                                <div class="mb-3">
                                    <label class="form-label">Link (URL Google Drive / Github / dll)</label>
                                    <input type="url" name="submission_link" class="form-control @error('submission_link') is-invalid @enderror" value="{{ old('submission_link', $submission->submission_link ?? '') }}">
                                    @error('submission_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success w-100">
                                {{ $submission ? 'Perbarui Pengumpulan' : 'Kumpulkan Tugas' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

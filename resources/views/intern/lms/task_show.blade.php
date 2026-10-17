@extends('layouts.app')
@section('title', 'Tugas: ' . $task->title)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">{{ $task->title }}</h1>
        <a href="{{ route('intern.lms.dashboard') }}" class="btn btn-secondary fw-semibold py-1.5 px-3 text-xs rounded-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 text-xs rounded-2 mb-3">{{ session('success') }}</div>
    @endif

    <div class="row g-2.5">
        <!-- Detail Tugas -->
        <div class="col-lg-7">
            <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
                <div class="card-header py-2 px-3 sm:px-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-xs uppercase tracking-wider !text-slate-800 dark:!text-slate-200">Instruksi Tugas</h6>
                    <span class="badge bg-danger py-1 px-2.5 text-xs rounded-pill">Deadline: {{ $task->deadline->format('d M Y H:i') }}</span>
                </div>
                <div class="card-body p-3 sm:p-4">
                    <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed mb-3">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                    <hr class="my-2.5 border-slate-200">
                    <p class="mb-0 text-xs">
                        <strong class="text-xs font-bold !text-slate-800 dark:!text-slate-200">Format yang diizinkan:</strong> 
                        @foreach($task->allowed_format as $format)
                            <span class="badge bg-secondary text-xs px-2 py-0.5 rounded-pill">{{ strtoupper($format) }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Submission -->
        <div class="col-lg-5">
            <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
                <div class="card-header py-2 px-3 sm:px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-xs uppercase tracking-wider !text-slate-800 dark:!text-slate-200">Pengumpulan Tugas</h6>
                </div>
                <div class="card-body p-3 sm:p-4">
                    @if($submission)
                        <div class="alert alert-info py-2 px-3 text-xs rounded-2 mb-2.5">
                            <strong>Status:</strong> {{ ucfirst($submission->status) }}<br>
                            <strong>Dikumpulkan pada:</strong> {{ $submission->updated_at->format('d M Y H:i') }}
                        </div>

                        @if($submission->grade_note)
                            <div class="alert alert-warning py-2 px-3 text-xs rounded-2 mb-2.5">
                                <strong>Catatan Reviewer:</strong><br>
                                {{ $submission->grade_note }}
                            </div>
                        @endif
                    @endif

                    @if(!$submission || in_array($submission->status, ['pending', 'submitted', 'revision']))
                        <form action="{{ route('intern.lms.task.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2.5">
                            @csrf
                            
                            @if(in_array('pdf', $task->allowed_format) || in_array('zip', $task->allowed_format))
                                <div>
                                    <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                        Upload File ({{ implode(', ', array_intersect(['pdf', 'zip'], $task->allowed_format)) }})
                                    </label>
                                    <input type="file" name="submission_file" class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('submission_file') is-invalid @enderror" accept=".pdf,.zip">
                                    @error('submission_file')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                    @if($submission && $submission->file_path)
                                        <small class="text-success d-block mt-1 text-xs">File saat ini: <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="underline fw-semibold">Download</a></small>
                                    @endif
                                </div>
                            @endif

                            @if(in_array('link', $task->allowed_format))
                                <div>
                                    <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                        Link (URL Google Drive / Github / dll)
                                    </label>
                                    <input type="url" name="submission_link" class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('submission_link') is-invalid @enderror" value="{{ old('submission_link', $submission->submission_link ?? '') }}">
                                    @error('submission_link')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="pt-1">
                                <button type="submit" class="btn btn-success fw-bold py-1.5 px-3.5 text-xs rounded-2 w-100 shadow-sm">
                                    {{ $submission ? 'Perbarui Pengumpulan' : 'Kumpulkan Tugas' }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

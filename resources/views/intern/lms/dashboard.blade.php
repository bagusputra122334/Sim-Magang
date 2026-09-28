@extends('layouts.app')
@section('title', 'LMS Dashboard')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daily Job Checklist</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Materi Belum Selesai -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Materi Pembelajaran</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($materials as $material)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $material->title }}</h6>
                                </div>
                                <a href="{{ route('intern.lms.material', $material->id) }}" class="btn btn-sm btn-primary">
                                    Mulai <i class="bi bi-arrow-right"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Semua materi telah diselesaikan!</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tugas Mendatang -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Tugas Mendatang</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($tasks as $task)
                            @php
                                // Check if submitted
                                $isSubmitted = \App\Models\Submission::where('intern_id', auth()->id())->where('task_id', $task->id)->exists();
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $task->title }}</h6>
                                    <small class="text-danger"><i class="bi bi-clock"></i> {{ $task->deadline->format('d M Y H:i') }}</small>
                                </div>
                                @if($isSubmitted)
                                    <a href="{{ route('intern.lms.task', $task->id) }}" class="btn btn-sm btn-outline-success">
                                        Lihat Status
                                    </a>
                                @else
                                    <a href="{{ route('intern.lms.task', $task->id) }}" class="btn btn-sm btn-success">
                                        Kerjakan
                                    </a>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Tidak ada tugas mendatang.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

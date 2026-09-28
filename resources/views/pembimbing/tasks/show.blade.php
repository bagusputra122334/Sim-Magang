@extends('layouts.app')
@section('title', 'Review Pengumpulan Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Submissions: {{ $task->title }}</h1>
        <a href="{{ route('pembimbing.tasks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Anak Bimbing</th>
                            <th>Waktu Pengumpulan</th>
                            <th>Status</th>
                            <th>File / Link</th>
                            <th>Catatan Nilai</th>
                            <th>Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $loop->iteration + $submissions->firstItem() - 1 }}</td>
                                <td>{{ $submission->intern->name ?? '-' }}</td>
                                <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($submission->status === 'reviewed')
                                        <span class="badge bg-success">Reviewed</span>
                                    @elseif($submission->status === 'revision')
                                        <span class="badge bg-warning text-dark">Revision</span>
                                    @elseif($submission->status === 'submitted')
                                        <span class="badge bg-info">Submitted</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($submission->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->file_path)
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> File</a>
                                    @endif
                                    @if($submission->submission_link)
                                        <a href="{{ $submission->submission_link }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="bi bi-link-45deg"></i> Link</a>
                                    @endif
                                    @if(!$submission->file_path && !$submission->submission_link)
                                        -
                                    @endif
                                </td>
                                <td>{{ $submission->grade_note ?? '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                        Review
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Review -->
                            <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $submission->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('pembimbing.submissions.review', $submission->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reviewModalLabel{{ $submission->id }}">Review Tugas: {{ $submission->intern->name ?? '-' }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="reviewed" {{ $submission->status === 'reviewed' ? 'selected' : '' }}>Reviewed (Diterima/Dinilai)</option>
                                                        <option value="revision" {{ $submission->status === 'revision' ? 'selected' : '' }}>Revision (Perlu Perbaikan)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan / Nilai</label>
                                                    <textarea name="grade_note" class="form-control" rows="3">{{ $submission->grade_note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Review</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pengumpulan tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

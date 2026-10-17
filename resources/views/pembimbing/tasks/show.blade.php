@extends('layouts.app')
@section('title', 'Review Pengumpulan Tugas')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">Submissions: {{ $task->title }}</h1>
        <a href="{{ route('pembimbing.tasks.index') }}" class="btn btn-secondary fw-semibold py-1.5 px-3 text-xs rounded-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 text-xs rounded-2 mb-3">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
        <div class="card-body p-3 sm:p-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 text-xs">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 48px;">No</th>
                            <th>Anak Bimbing</th>
                            <th style="width: 140px;">Waktu Pengumpulan</th>
                            <th style="width: 100px;">Status</th>
                            <th>File / Link</th>
                            <th>Catatan Nilai</th>
                            <th class="text-center" style="width: 90px;">Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration + $submissions->firstItem() - 1 }}</td>
                                <td class="fw-semibold text-slate-900 dark:text-slate-100">{{ $submission->intern->name ?? '-' }}</td>
                                <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($submission->status === 'reviewed')
                                        <span class="badge bg-success py-1 px-2 text-xs rounded-pill">Reviewed</span>
                                    @elseif($submission->status === 'revision')
                                        <span class="badge bg-warning text-dark py-1 px-2 text-xs rounded-pill">Revision</span>
                                    @elseif($submission->status === 'submitted')
                                        <span class="badge bg-info py-1 px-2 text-xs rounded-pill">Submitted</span>
                                    @else
                                        <span class="badge bg-secondary py-1 px-2 text-xs rounded-pill">{{ ucfirst($submission->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->file_path)
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0.5 px-2 text-xs rounded-1"><i class="bi bi-download me-1"></i> File</a>
                                    @endif
                                    @if($submission->submission_link)
                                        <a href="{{ $submission->submission_link }}" target="_blank" class="btn btn-sm btn-outline-info py-0.5 px-2 text-xs rounded-1"><i class="bi bi-link-45deg me-1"></i> Link</a>
                                    @endif
                                    @if(!$submission->file_path && !$submission->submission_link)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{!! $submission->grade_note ? nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-semibold hover:text-blue-800 transition-colors" rel="noopener noreferrer">$1</a>', e($submission->grade_note))) : '<span class="text-muted">-</span>' !!}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary fw-bold py-1 px-2.5 text-xs rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $submission->id }}">
                                        Review
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Review -->
                            <div class="modal fade" id="reviewModal{{ $submission->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $submission->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-2 overflow-hidden shadow">
                                        <form action="{{ route('pembimbing.submissions.review', $submission->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header py-2 px-3 sm:px-4 bg-light border-bottom">
                                                <h6 class="modal-title font-weight-bold text-xs uppercase tracking-wider !text-slate-800 dark:!text-slate-200" id="reviewModalLabel{{ $submission->id }}">Review: {{ $submission->intern->name ?? '-' }}</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3 sm:p-4 space-y-2.5">
                                                <div>
                                                    <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Status</label>
                                                    <select name="status" class="form-select form-select-sm py-1.5 px-2.5 text-xs rounded-2" required>
                                                        <option value="reviewed" {{ $submission->status === 'reviewed' ? 'selected' : '' }}>Reviewed (Diterima/Dinilai)</option>
                                                        <option value="revision" {{ $submission->status === 'revision' ? 'selected' : '' }}>Revision (Perlu Perbaikan)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Catatan / Nilai</label>
                                                    <textarea name="grade_note" class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2" rows="3" placeholder="Tuliskan catatan evaluasi...">{{ $submission->grade_note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2 px-3 sm:px-4 bg-light border-top">
                                                <button type="button" class="btn btn-secondary py-1.5 px-3 text-xs rounded-2" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm">Simpan Review</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Belum ada pengumpulan tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
                <div class="mt-2.5">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

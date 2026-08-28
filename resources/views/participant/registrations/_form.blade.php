<?php
/**
 * @var \Illuminate\Support\Collection<int, \App\Models\Position> $positions
 * @var \App\Models\Registration|null $reg
 */
$isEdit = isset($reg) && $reg !== null;
$selectedPosId = old('position_id', $isEdit ? $reg?->position_id ?? '' : '');
?>

<div class="row g-5 mb-5">
    <div class="col-lg-8">
        <div class="mb-5">
            <div class="d-flex align-items-center gap-2.5 mb-3 pb-2 border-bottom">
                <div class="p-2 rounded-3 bg-success-subtle text-success">
                    <i class="bi bi-briefcase-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Pilih Posisi Magang</h5>
                    <small class="text-muted">Pilih salah satu posisi magang aktif yang tersedia di Diskominfo SP</small>
                </div>
            </div>

            @if($positions->count() === 0)
                <div class="alert alert-warning border border-warning-subtle rounded-4 p-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                        <div>
                            <strong class="d-block text-dark">Belum Ada Posisi Dibuka</strong>
                            <span class="small text-muted">Saat ini belum ada posisi magang yang dibuka. Silakan pantau kembali secara berkala.</span>
                        </div>
                    </div>
                </div>
            @else
            <div class="row g-3">
                @foreach($positions as $pos)
                    @php
                        $active = (string) $selectedPosId === (string) $pos->id;
                    @endphp
                <label class="col-md-6 position-select-card-label">
                    <input type="radio" name="position_id" value="{{ $pos->id }}" class="d-none position-select-radio"
                           @required(true)
                           @checked($active)>
                    <div class="card border-2 rounded-4 h-100 transition-all position-select-card {{ $active ? 'border-success shadow-sm bg-success-subtle bg-opacity-25' : 'border-light-subtle bg-white' }}">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <div class="fw-bold fs-5 text-dark mb-1">{{ $pos->nama_posisi }}</div>
                                    <div class="small text-muted font-monospace">
                                        <i class="bi bi-hash me-0.5"></i>{{ $pos->slug ?? 'slug' }}
                                    </div>
                                </div>
                                <div class="ms-2">
                                    <span class="badge rounded-pill fs-7 bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-semibold">
                                        <i class="bi bi-check-circle-fill me-1"></i>Terbuka
                                    </span>
                                </div>
                            </div>
                            @if(!empty(trim($pos->deskripsi ?? '')))
                            <p class="small text-secondary mt-3 mb-0 lh-lg line-clamp-3 text-truncate-3">{{ \Illuminate\Support\Str::limit($pos->deskripsi, 150) }}</p>
                            @endif
                            <div class="mt-3 small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-clock-history text-secondary"></i>
                                Terakhir Diperbarui: {{ $pos->updated_at?->locale('id')->translatedFormat('d M Y') ?? '-' }}
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('position_id')
                <div class="text-danger small mt-2 fw-medium">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
                </div>
            @enderror
            @endif
        </div>

        <div class="mb-5">
            <div class="d-flex align-items-center gap-2.5 mb-3 pb-2 border-bottom">
                <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                    <i class="bi bi-calendar-range-fill fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Periode Pelaksanaan Magang</h5>
                    <small class="text-muted">Tentukan tanggal mulai dan selesai rencana kegiatan magang</small>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="periode_mulai" class="form-label fw-semibold text-dark">
                        Periode Mulai <span class="text-danger">*</span>
                        <small class="text-muted fw-normal fst-italic ms-2">(minimal H+1 hari ini)</small>
                    </label>
                    <div class="input-group input-group-lg @error('periode_mulai') has-validation @enderror">
                        <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-calendar-plus-fill"></i></span>
                        <input type="date" name="periode_mulai" id="periode_mulai"
                               value="{{ old('periode_mulai', $isEdit && $reg?->periode_mulai ? $reg->periode_mulai->format('Y-m-d') : '') }}"
                               min="{{ $isEdit ? '2020-01-01' : now()->addDay()->format('Y-m-d') }}"
                               class="form-control form-control-lg border-start-0 @error('periode_mulai') is-invalid @enderror" required>
                        @error('periode_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="periode_selesai" class="form-label fw-semibold text-dark">
                        Periode Selesai <span class="text-danger">*</span>
                        <small class="text-muted fw-normal fst-italic ms-2">(setelah periode mulai)</small>
                    </label>
                    <div class="input-group input-group-lg @error('periode_selesai') has-validation @enderror">
                        <span class="input-group-text bg-light border-end-0 text-primary"><i class="bi bi-calendar2-check-fill"></i></span>
                        <input type="date" name="periode_selesai" id="periode_selesai"
                               value="{{ old('periode_selesai', $isEdit && $reg?->periode_selesai ? $reg->periode_selesai->format('Y-m-d') : '') }}"
                               min="{{ old('periode_mulai', $isEdit && $reg?->periode_mulai ? $reg->periode_mulai->format('Y-m-d') : now()->addDay()->format('Y-m-d')) }}"
                               class="form-control form-control-lg border-start-0 @error('periode_selesai') is-invalid @enderror" required>
                        @error('periode_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-primary-subtle bg-opacity-25 border border-primary-subtle rounded-3 p-3.5 small text-dark d-flex align-items-start gap-2.5">
                        <i class="bi bi-info-circle-fill text-primary fs-5 mt-0.5"></i>
                        <div>
                            <strong>Informasi Durasi:</strong> Durasi magang umumnya disarankan antara 1 hingga 3 bulan (minimal 30 hari untuk semester pendek, atau 90 hari untuk magang penuh).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light border rounded-4 shadow-sm sticky-top" style="top: 1rem;">
            <div class="card-header bg-primary-subtle border-0 py-4 px-4 rounded-top-4">
                <h5 class="fw-bold mb-0 text-primary-emphasis">
                    <i class="bi bi-cloud-upload-fill me-2"></i>Upload Dokumen Persyaratan
                </h5>
            </div>
            <div class="card-body p-4">
                <div x-data="{ fileName: null }" class="mb-4">
                    <label for="cv" class="form-label fw-semibold">
                        <i class="bi bi-filetype-pdf text-danger me-1"></i>Curriculum Vitae (CV)
                        <span class="text-danger">*</span>
                        @if($isEdit)
                            <small class="text-success fw-normal fst-italic ms-1">(opsional, kosongkan = tidak diubah)</small>
                        @endif
                    </label>
                    @if($isEdit && !empty($reg?->cv_path))
                        @php
                            $cvExists = true;
                            try {
                                $cvUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($reg->cv_path);
                            } catch (\Throwable $e) { $cvExists = false; $cvUrl = '#'; }
                        @endphp
                        <div class="mb-2 bg-white border rounded-3 p-3 d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger flex-shrink-0"></i>
                            <div class="flex-grow-1 small">
                                <div class="fw-semibold text-truncate">Lampiran CV saat ini</div>
                                <div class="text-muted fst-italic">{{ basename($reg->cv_path) }}</div>
                            </div>
                            <a href="{{ $cvUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary fw-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex-shrink-0">
                                <i class="bi bi-eye-fill me-1"></i>Lihat
                            </a>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed rounded-xl p-4 transition-all duration-200"
                         :class="fileName ? 'border-emerald-500 bg-emerald-50 shadow-sm' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                         :style="fileName ? 'background-color: #ecfdf5 !important; border-color: #10b981 !important;' : ''">
                        <input type="file" name="cv" id="cv"
                               accept="application/pdf,.pdf"
                               @if(!$isEdit) required @endif
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('cv') is-invalid @enderror">
                        <div x-show="!fileName" class="text-center py-2">
                            <i class="bi bi-cloud-arrow-up text-emerald-600 text-3xl mb-1 block"></i>
                            <span class="fw-semibold text-gray-700 block">Klik atau seret file ke sini</span>
                            <span class="text-muted small">Format hanya PDF, Maksimal 2 MB (2048 KB).</span>
                        </div>
                        <div x-show="fileName" :title="fileName" class="flex items-center justify-center w-full p-3 bg-emerald-50 border-2 border-dashed border-emerald-500 rounded-xl cursor-pointer hover:bg-emerald-100 transition-colors" style="background-color: #ecfdf5 !important; border: 2px dashed #10b981 !important;" x-cloak>
                            <svg class="w-5 h-5 text-emerald-600 mr-2 flex-shrink-0" style="color: #059669 !important; width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-emerald-700" style="color: #047857 !important; font-weight: 700 !important;">Dokumen Terupload</span>
                        </div>
                    </div>

                    @error('cv')<div class="invalid-feedback d-block small mt-1">{{ $message }}</div>@enderror
                </div>

                <div x-data="{ fileName: null }" class="mb-4">
                    <label for="surat_pengantar" class="form-label fw-semibold">
                        <i class="bi bi-filetype-pdf text-danger me-1"></i>Surat Pengantar Institusi
                        <span class="text-danger">*</span>
                        @if($isEdit)
                            <small class="text-success fw-normal fst-italic ms-1">(opsional, kosongkan = tidak diubah)</small>
                        @endif
                    </label>
                    @if($isEdit && !empty($reg?->surat_pengantar_path))
                        @php
                            try {
                                $spUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($reg->surat_pengantar_path);
                            } catch (\Throwable $e) { $spUrl = '#'; }
                        @endphp
                        <div class="mb-2 border rounded-3 p-3 d-flex align-items-center gap-2 bg-light bg-opacity-50">
                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger flex-shrink-0"></i>
                            <div class="flex-grow-1 small">
                                <div class="fw-semibold text-truncate">Surat Pengantar Sekarang</div>
                                <div class="text-muted fst-italic">{{ basename($reg->surat_pengantar_path) }}</div>
                            </div>
                            <a href="{{ $spUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary fw-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex-shrink-0">
                                <i class="bi bi-eye-fill me-1"></i>Lihat
                            </a>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed rounded-xl p-4 transition-all duration-200"
                         :class="fileName ? 'border-emerald-500 bg-emerald-50 shadow-sm' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                         :style="fileName ? 'background-color: #ecfdf5 !important; border-color: #10b981 !important;' : ''">
                        <input type="file" name="surat_pengantar" id="surat_pengantar"
                               accept="application/pdf,.pdf"
                               @if(!$isEdit) required @endif
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('surat_pengantar') is-invalid @enderror">
                        <div x-show="!fileName" class="text-center py-2">
                            <i class="bi bi-cloud-arrow-up text-emerald-600 text-3xl mb-1 block"></i>
                            <span class="fw-semibold text-gray-700 block">Klik atau seret file ke sini</span>
                            <span class="text-muted small">Format hanya PDF, Maksimal 3 MB. Surat resmi dari sekolah/universitas.</span>
                        </div>
                        <div x-show="fileName" :title="fileName" class="flex items-center justify-center w-full p-3 bg-emerald-50 border-2 border-dashed border-emerald-500 rounded-xl cursor-pointer hover:bg-emerald-100 transition-colors" style="background-color: #ecfdf5 !important; border: 2px dashed #10b981 !important;" x-cloak>
                            <svg class="w-5 h-5 text-emerald-600 mr-2 flex-shrink-0" style="color: #059669 !important; width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-emerald-700" style="color: #047857 !important; font-weight: 700 !important;">Dokumen Terupload</span>
                        </div>
                    </div>

                    @error('surat_pengantar')<div class="invalid-feedback d-block small mt-1">{{ $message }}</div>@enderror
                </div>

                <div x-data="{ fileName: null }" class="mb-4">
                    <label for="proposal_magang" class="form-label fw-semibold">
                        <i class="bi bi-filetype-pdf text-danger me-1"></i>Proposal Magang
                        <span class="text-danger">*</span>
                        @if($isEdit)
                            <small class="text-success fw-normal fst-italic ms-1">(opsional, kosongkan = tidak diubah)</small>
                        @endif
                    </label>
                    @if($isEdit && !empty($reg?->proposal_magang_path))
                        @php
                            try {
                                $pmUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($reg->proposal_magang_path);
                            } catch (\Throwable $e) { $pmUrl = '#'; }
                        @endphp
                        <div class="mb-2 border rounded-3 p-3 d-flex align-items-center gap-2 bg-light bg-opacity-50">
                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger flex-shrink-0"></i>
                            <div class="flex-grow-1 small">
                                <div class="fw-semibold text-truncate">Proposal Magang Sekarang</div>
                                <div class="text-muted fst-italic">{{ basename($reg->proposal_magang_path) }}</div>
                            </div>
                            <a href="{{ $pmUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary fw-semibold rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex-shrink-0">
                                <i class="bi bi-eye-fill me-1"></i>Lihat
                            </a>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed rounded-xl p-4 transition-all duration-200"
                         :class="fileName ? 'border-emerald-500 bg-emerald-50 shadow-sm' : 'border-gray-300 bg-gray-50/50 hover:border-emerald-400 hover:bg-emerald-50/20'"
                         :style="fileName ? 'background-color: #ecfdf5 !important; border-color: #10b981 !important;' : ''">
                        <input type="file" name="proposal_magang" id="proposal_magang"
                               accept="application/pdf,.pdf"
                               @if(!$isEdit) required @endif
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('proposal_magang') is-invalid @enderror">
                        <div x-show="!fileName" class="text-center py-2">
                            <i class="bi bi-cloud-arrow-up text-emerald-600 text-3xl mb-1 block"></i>
                            <span class="fw-semibold text-gray-700 block">Klik atau seret file ke sini</span>
                            <span class="text-muted small">Unggah proposal magang sesuai ketentuan. Format hanya PDF, Maksimal 5 MB.</span>
                        </div>
                        <div x-show="fileName" :title="fileName" class="flex items-center justify-center w-full p-3 bg-emerald-50 border-2 border-dashed border-emerald-500 rounded-xl cursor-pointer hover:bg-emerald-100 transition-colors" style="background-color: #ecfdf5 !important; border: 2px dashed #10b981 !important;" x-cloak>
                            <svg class="w-5 h-5 text-emerald-600 mr-2 flex-shrink-0" style="color: #059669 !important; width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-emerald-700" style="color: #047857 !important; font-weight: 700 !important;">Dokumen Terupload</span>
                        </div>
                    </div>

                    @error('proposal_magang')<div class="invalid-feedback d-block small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-3 p-3 small">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>PERINGATAN PENGAJUAN</div>
                    <ul class="mb-0 ps-3 lh-lg">
                        <li>Pastikan nama di CV sesuai NIK & KTM/Kartu pelajar</li>
                        <li>Surat pengantar harus <strong>berstempel resmi</strong> dan ditandatangani pihak institusi</li>
                        <li>Proposal magang memuat rencana kegiatan, tujuan, serta jadwal pelaksanaan magang</li>
                        <li>Setelah submit otomatis status jadi <strong>Submitted</strong>, Admin Diskominfo akan memverifikasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .position-select-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.25rem rgba(25, 135, 84, 0.12) !important;
    }
    .position-select-card-label {
        cursor: pointer;
    }
    .text-truncate-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .fs-7 { font-size: 0.82rem !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mulai = document.getElementById('periode_mulai');
    const selesai = document.getElementById('periode_selesai');
    if (mulai && selesai) {
        mulai.addEventListener('change', function () {
            if (mulai.value) {
                selesai.min = mulai.value;
                if (selesai.value && selesai.value < mulai.value) {
                    selesai.value = mulai.value;
                }
            }
        });
    }

    document.querySelectorAll('.position-select-radio').forEach(function (radio) {
        const apply = () => {
            document.querySelectorAll('.position-select-card').forEach(function (card) {
                card.classList.remove('border-success', 'shadow', 'bg-success-subtle');
                card.classList.add('border-light', 'bg-white');
            });
            const active = document.querySelector('.position-select-radio:checked');
            if (active && active.parentElement) {
                const activeCard = active.parentElement.querySelector('.position-select-card');
                if (activeCard) {
                    activeCard.classList.remove('border-light', 'bg-white');
                    activeCard.classList.add('border-success', 'shadow', 'bg-success-subtle');
                }
            }
        };
        radio.addEventListener('change', apply);
        apply();
    });

    [document.getElementById('cv'), document.getElementById('surat_pengantar'), document.getElementById('proposal_magang')].forEach(function (inp) {
        if (!inp) return;
        inp.addEventListener('change', function () {
            const f = this.files[0];
            if (!f) return;
            if (f.type !== 'application/pdf') {
                alert('Hanya file PDF yang diizinkan! Pilih file PDF lain.');
                this.value = '';
                return;
            }
            let maxSize = 2 * 1024 * 1024;
            let labelMb = '2 MB';
            if (this.id === 'surat_pengantar') {
                maxSize = 3 * 1024 * 1024;
                labelMb = '3 MB';
            } else if (this.id === 'proposal_magang') {
                maxSize = 5 * 1024 * 1024;
                labelMb = '5 MB';
            }
            if (f.size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal ' + labelMb + '. Kompres PDF Anda.');
                this.value = '';
                return;
            }
        });
    });
});
</script>
@endpush

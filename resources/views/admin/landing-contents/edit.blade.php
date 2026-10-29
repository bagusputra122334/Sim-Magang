@extends('layouts.admin')

@section('title', 'Edit Konten Landing Page')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 fw-bold text-slate-800 dark:text-slate-100 mb-0.5">Edit Konten Landing Page</h1>
            <p class="text-muted dark:text-slate-400 small mb-0">Perbarui item konten landing page #{{ $landingContent->id }} ({{ $landingContent->title }}).</p>
        </div>
        <a href="{{ route('admin.landing-contents.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-xl shadow-sm mb-3 py-2 px-3 text-xs" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1.5"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-xl overflow-hidden mb-3 bg-white dark:bg-slate-800">
        <div class="card-header bg-slate-900 text-white py-2.5 px-3.5">
            <h5 class="mb-0 fw-bold fs-6 text-white"><i class="bi bi-pencil-square me-1.5"></i> Form Edit Item Landing Page</h5>
        </div>
        <div class="card-body p-3 sm:p-4 bg-white dark:bg-slate-800">
            <form action="{{ route('admin.landing-contents.update', $landingContent->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-2.5">
                    <div class="col-md-6">
                        <label for="section" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Kategori <span class="text-danger">*</span></label>
                        <select name="section" id="section" class="form-select py-1.5 px-2.5 text-xs @error('section') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section', $landingContent->section) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size: 0.72rem;">Pilih bagian landing page tempat item ini ditampilkan.</small>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Urutan <span class="text-danger">*</span></label>
                        <input type="number" name="order" id="order" class="form-control py-1.5 px-2.5 text-xs @error('order') is-invalid @enderror" value="{{ old('order', $landingContent->order) }}" min="0" required>
                        <small class="text-muted" style="font-size: 0.72rem;">Angka urutan posisi item (semakin kecil, semakin awal).</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control py-1.5 px-2.5 text-xs @error('title') is-invalid @enderror" value="{{ old('title', $landingContent->title) }}" placeholder="Contoh: 100% Digital & Paperless" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control py-1.5 px-2.5 text-xs @error('description') is-invalid @enderror" placeholder="Tuliskan penjabaran detail atau jawaban pertanyaan di sini...">{{ old('description', $landingContent->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="icon" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">Ikon <span class="text-danger">*</span></label>
                        <select name="icon" id="icon" class="form-select py-1.5 px-2.5 text-xs @error('icon') is-invalid @enderror" required>
                            <option value="" disabled {{ old('icon', $landingContent->icon) ? '' : 'selected' }}>-- Pilih Ikon --</option>
                            @if($landingContent->icon && !in_array($landingContent->icon, ['bi-laptop', 'bi-people', 'bi-shield-check', 'bi-briefcase', 'bi-award', 'bi-file-earmark-text', 'bi-building', 'bi-check-circle', 'bi-calendar-check', 'bi-clock', 'bi-question-circle', 'bi-info-circle', 'bi-person-plus', 'bi-file-earmark-arrow-up', 'bi-clipboard-check', 'bi-cash-stack']))
                                <option value="{{ $landingContent->icon }}" {{ old('icon', $landingContent->icon) == $landingContent->icon ? 'selected' : '' }}>🔹 {{ $landingContent->icon }} (Saat ini)</option>
                            @endif
                            <option value="bi-laptop" {{ old('icon', $landingContent->icon) == 'bi-laptop' ? 'selected' : '' }}>💻 Laptop / Teknologi</option>
                            <option value="bi-people" {{ old('icon', $landingContent->icon) == 'bi-people' ? 'selected' : '' }}>👥 Orang / Tim / Komunitas</option>
                            <option value="bi-shield-check" {{ old('icon', $landingContent->icon) == 'bi-shield-check' ? 'selected' : '' }}>🛡️ Keamanan / Validasi</option>
                            <option value="bi-briefcase" {{ old('icon', $landingContent->icon) == 'bi-briefcase' ? 'selected' : '' }}>💼 Pekerjaan / Magang</option>
                            <option value="bi-award" {{ old('icon', $landingContent->icon) == 'bi-award' ? 'selected' : '' }}>🏆 Penghargaan / Keunggulan</option>
                            <option value="bi-file-earmark-text" {{ old('icon', $landingContent->icon) == 'bi-file-earmark-text' ? 'selected' : '' }}>📄 Dokumen / Surat Resmi</option>
                            <option value="bi-building" {{ old('icon', $landingContent->icon) == 'bi-building' ? 'selected' : '' }}>🏢 Instansi / Perusahaan</option>
                            <option value="bi-check-circle" {{ old('icon', $landingContent->icon) == 'bi-check-circle' ? 'selected' : '' }}>✅ Sukses / Selesai</option>
                            <option value="bi-calendar-check" {{ old('icon', $landingContent->icon) == 'bi-calendar-check' ? 'selected' : '' }}>📅 Kalender / Jadwal</option>
                            <option value="bi-clock" {{ old('icon', $landingContent->icon) == 'bi-clock' ? 'selected' : '' }}>⏰ Waktu / Durasi</option>
                            <option value="bi-question-circle" {{ old('icon', $landingContent->icon) == 'bi-question-circle' ? 'selected' : '' }}>❓ Pertanyaan / FAQ</option>
                            <option value="bi-info-circle" {{ old('icon', $landingContent->icon) == 'bi-info-circle' ? 'selected' : '' }}>ℹ️ Informasi</option>
                        </select>
                        <small class="text-muted" style="font-size: 0.72rem;">Pilih ikon visual yang paling mewakili poin konten ini.</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $landingContent->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-xs font-bold !text-slate-800 dark:!text-slate-200" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('admin.landing-contents.index') }}" class="btn btn-outline-secondary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm">Batal</a>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm">
                        <i class="bi bi-save"></i> Perbarui Konten
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

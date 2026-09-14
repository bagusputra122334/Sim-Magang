@extends('layouts.admin')

@section('title', 'Edit Konten Landing Page')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-slate-800 mb-1">Edit Konten Landing Page</h1>
            <p class="text-muted mb-0">Perbarui item konten landing page #{{ $landingContent->id }} ({{ $landingContent->title }}).</p>
        </div>
        <a href="{{ route('admin.landing-contents.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 rounded-lg">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-xl shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
        <div class="card-header bg-slate-900 text-white py-3 px-4">
            <h5 class="mb-0 fw-bold fs-6 text-white"><i class="bi bi-pencil-square me-2"></i> Form Edit Item Landing Page</h5>
        </div>
        <div class="card-body p-4 bg-white">
            <form action="{{ route('admin.landing-contents.update', $landingContent->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="section" class="form-label font-semibold text-slate-700">Kategori <span class="text-danger">*</span></label>
                        <select name="section" id="section" class="form-select @error('section') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($sections as $key => $label)
                                <option value="{{ $key }}" {{ old('section', $landingContent->section) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih bagian landing page tempat item ini ditampilkan.</small>
                        @error('section')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="order" class="form-label font-semibold text-slate-700">Urutan <span class="text-danger">*</span></label>
                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $landingContent->order) }}" min="0" required>
                        <small class="text-muted">Angka urutan posisi item (semakin kecil, semakin awal).</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label font-semibold text-slate-700">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $landingContent->title) }}" placeholder="Contoh: 100% Digital & Paperless" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label font-semibold text-slate-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan penjabaran detail atau jawaban pertanyaan di sini...">{{ old('description', $landingContent->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="icon" class="form-label font-semibold text-slate-700">Ikon <span class="text-danger">*</span></label>
                        <select name="icon" id="icon" class="form-select @error('icon') is-invalid @enderror" required>
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
                        <small class="text-muted">Pilih ikon visual yang paling mewakili poin konten ini.</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $landingContent->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label font-semibold text-slate-700" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.landing-contents.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4">
                        <i class="bi bi-save"></i> Perbarui Konten
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

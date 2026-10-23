@extends('layouts.admin')

@section('title', 'Pengaturan')

@php
    $groupLabels = [
        'global'       => ['title' => 'Pengaturan Global', 'icon' => 'bi-globe-americas', 'desc' => 'Identitas utama aplikasi, judul website, dan logo resmi.'],
        'landing_page' => ['title' => 'Landing Page Portal', 'icon' => 'bi-window-sidebar', 'desc' => 'Teks banner hero, judul tentang program, dan gambar utama.'],
        'contact'      => ['title' => 'Informasi Kontak & Sosial Media', 'icon' => 'bi-geo-alt-fill', 'desc' => 'Alamat kantor, email, nomor telepon, dan tautan sosial media.'],
    ];

    $multilineKeys = [
        'hero_description', 'about_description', 'contact_address', 'meta_description', 'maps_embed_url'
    ];
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 fw-bold mb-0.5 text-slate-800">Pengaturan System</h1>
            <p class="text-muted small mb-0">Kelola konfigurasi global aplikasi, teks landing page, dan kontak Diskominfo Tuban.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm mb-3 py-2 px-3 text-xs" role="alert">
            <i class="bi bi-check-circle-fill me-1.5"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @foreach ($settings as $group => $items)
            @php
                $meta = $groupLabels[$group] ?? [
                    'title' => ucwords(str_replace('_', ' ', $group)),
                    'icon'  => 'bi-gear-fill',
                    'desc'  => 'Pengaturan kategori ' . $group
                ];

                $sectionId = match($group) {
                    'global'       => 'pengaturan-global',
                    'landing_page' => 'landing-page-portal',
                    'contact'      => 'informasi-kontak',
                    default        => 'pengaturan-' . str_replace('_', '-', $group),
                };
            @endphp

            <div class="card border-0 shadow-sm rounded-xl mb-3 overflow-hidden" id="{{ $sectionId }}" style="scroll-margin-top: 80px;">
                <div class="card-header bg-light border-bottom py-2 px-3.5 d-flex align-items-center gap-2.5">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-1.5 d-flex align-items-center justify-content-center">
                        <i class="bi {{ $meta['icon'] }} fs-6 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark text-slate-800 fs-6">{{ $meta['title'] }}</h5>
                        <small class="text-muted text-slate-600" style="font-size: 0.72rem;">{{ $meta['desc'] }}</small>
                    </div>
                </div>

                <div class="card-body p-3 sm:p-4 bg-white">
                    <div class="row g-2.5">
                        @foreach ($items as $setting)
                            @php
                                $label = ucwords(str_replace('_', ' ', $setting->key));
                                $isMultiline = in_array($setting->key, $multilineKeys) || strlen($setting->value) > 70;
                            @endphp

                            <div class="col-12 {{ $setting->type === 'image' || $isMultiline ? 'col-12' : 'col-md-6' }}">
                                <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50/50 hover:bg-white transition-all">
                                    <div class="mb-1.5">
                                        <label for="setting_{{ $setting->key }}" class="form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-0">
                                            {{ $label }}
                                        </label>
                                    </div>

                                    @if ($setting->type === 'image')
                                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2.5 mt-1.5">
                                            @if ($setting->value)
                                                <div class="position-relative border rounded p-1 bg-white shadow-sm flex-shrink-0">
                                                    <img src="{{ asset($setting->value) }}" alt="{{ $label }}" class="rounded" style="max-height: 70px; max-width: 120px; object-fit: contain;">
                                                </div>
                                            @endif
                                            <div class="flex-grow-1 w-100">
                                                <input type="file" 
                                                       name="{{ $setting->key }}" 
                                                       id="setting_{{ $setting->key }}" 
                                                       class="form-control py-1 px-2 text-xs @error($setting->key) is-invalid @enderror" 
                                                       accept="image/*">
                                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Unggah berkas gambar baru (PNG, JPG, SVG, WebP, Maks. 5MB).</small>
                                            </div>
                                        </div>
                                    @elseif ($isMultiline)
                                        <textarea name="{{ $setting->key }}" 
                                                  id="setting_{{ $setting->key }}" 
                                                  class="form-control py-1.5 px-2.5 text-xs @error($setting->key) is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Masukkan {{ strtolower($label) }}...">{{ old($setting->key, $setting->value) }}</textarea>
                                    @elseif ($setting->type === 'url')
                                        <input type="url" 
                                               name="{{ $setting->key }}" 
                                               id="setting_{{ $setting->key }}" 
                                               value="{{ old($setting->key, $setting->value) }}" 
                                               class="form-control py-1.5 px-2.5 text-xs @error($setting->key) is-invalid @enderror" 
                                               placeholder="https://...">
                                    @else
                                        <input type="text" 
                                               name="{{ $setting->key }}" 
                                               id="setting_{{ $setting->key }}" 
                                               value="{{ old($setting->key, $setting->value) }}" 
                                               class="form-control py-1.5 px-2.5 text-xs @error($setting->key) is-invalid @enderror" 
                                               placeholder="Masukkan {{ strtolower($label) }}...">
                                    @endif

                                    @error($setting->key)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card border-0 shadow-sm rounded-xl p-2.5 bg-white d-flex flex-row justify-content-end align-items-center gap-2">
            <button type="reset" class="btn btn-outline-secondary text-slate-600 px-3 py-1.5 text-xs font-bold rounded-lg">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Form
            </button>
            <button type="submit" class="btn btn-primary px-4 py-1.5 text-xs font-bold rounded-lg shadow-sm">
                <i class="bi bi-save2-fill me-1.5"></i> Simpan Semua Perubahan
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function renderActiveSection() {
            // Define the exact IDs of the 3 sections on this page
            const sections = ['pengaturan-global', 'landing-page-portal', 'informasi-kontak'];
            
            // Get current hash from URL, remove the '#' symbol. 
            // Default to 'pengaturan-global' if hash is empty or invalid.
            let currentHash = window.location.hash.substring(1);
            if (!sections.includes(currentHash)) {
                currentHash = 'pengaturan-global';
            }

            // Loop through all sections: show the active one, hide the rest
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    // Using fadeIn effect equivalent by removing inline display limitations
                    el.style.display = (id === currentHash) ? 'block' : 'none';
                }
            });
            
            // Smoothly scroll back to top to simulate a fresh page load
            window.scrollTo(0, 0);
        }

        // 1. Run immediately on first page load
        renderActiveSection();

        // 2. Run every time a sidebar hash link is clicked
        window.addEventListener('hashchange', renderActiveSection);
    });
</script>
@endpush

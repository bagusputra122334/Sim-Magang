@extends('layouts.admin')

@php
    $statusBadgeMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
    ];
@endphp

@section('title', 'Dashboard')

@section('content')
    {{-- Header --}}
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <h1 class="h3 mb-1">Dashboard Administrator</h1>
            <p class="text-muted mb-0">
                Selamat datang, <strong>{{ auth()->user()->name }}</strong> — Monitoring pendaftaran magang Diskominfo SP Kabupaten Tuban.
            </p>
        </div>
    </div>

    <!-- TOP ROW: Stat Cards & Chart -->
    <div class="row g-3 mb-4 align-items-stretch">
        <!-- TOP ROW LEFT: 6 Stat Cards (col-lg-8) -->
        <div class="col-lg-8">
            {{-- Statistics Cards (6 Metrics Grid - Compact Layout) --}}
            <section class="row g-2" aria-label="Ringkasan Statistik Magang">
                {{-- Card 1: Total Posisi Magang --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL POSISI MAGANG</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['total_positions'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-primary bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-briefcase text-primary fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                <span class="text-success fw-semibold">{{ $stats['total_positions_aktif'] }} aktif</span> dari {{ $stats['total_positions'] }} posisi
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Pelamar / Peserta --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOTAL PESERTA TERDAFTAR</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['total_peserta'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-success bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-people text-success fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                <span class="text-success fw-semibold">{{ $stats['total_peserta_verified'] }} terverifikasi</span> akun email
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Pending Applications --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">PERLU VERIFIKASI</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['verifikasi_pending'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-warning bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-clock-history text-warning fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                <span class="text-warning fw-semibold">{{ $stats['status_submitted'] }} diajukan</span> perlu ditinjau
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Under Review Applications --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">UNDER REVIEW</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['status_under_review'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-warning bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-search text-warning fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                Sedang diproses tim verifikator
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 5: Accepted Applications --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">PENDAFTARAN DITERIMA</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['status_accepted'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-success bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-check-circle text-success fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                {{ $stats['percent_accepted'] }}% dari total pendaftaran
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 6: Rejected Applications --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 rounded-lg h-100">
                        <div class="card-body p-2 px-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">PENDAFTARAN DITOLAK</h6>
                                    <h3 class="fw-bold mb-0 text-dark mt-1 fs-4">{{ number_format($stats['status_rejected'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="rounded bg-danger bg-opacity-10 p-1 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                    <i class="bi bi-x-circle text-danger fs-6"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.65rem;">
                                {{ $stats['total_registrations'] > 0 ? round(($stats['status_rejected'] / $stats['total_registrations']) * 100, 1) : 0 }}% dari total pendaftaran
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN: Chart Card Matched Height -->
        <div class="col-lg-4 d-flex">
            <div class="card shadow-sm border-0 w-100 d-flex flex-column justify-content-between mb-0">
                <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Rekap Pendaftaran
                    </h6>
                </div>
                <!-- Padding dikurangi, flex-grow diatur -->
                <div class="card-body p-2 text-center d-flex flex-column justify-content-center align-items-center flex-grow-1" style="min-height: 0;">
                    <!-- KUNCI MATI TINGGI CANVAS DI SINI -->
                    <div style="position: relative; height: 160px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-2">
                        <p class="text-muted mb-0" style="font-size: 0.65rem;">Distribusi status pendaftaran magang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM ROW: Full-Width Recent Registrations Table -->
    <div class="row">
        <div class="col-12">
            <section class="panel">
                <div class="panel-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                    <div>
                        <h2 class="h5 mb-1 section-title text-lg md:text-xl font-bold">
                            <i class="bi bi-journal-text me-1" aria-hidden="true"></i>
                            <span>Pendaftaran Magang Terbaru</span>
                        </h2>
                        <p class="text-muted mb-0 small">10 pendaftaran magang paling akhir diajukan oleh peserta.</p>
                    </div>
                    <a class="btn btn-outline-secondary bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg px-3 py-1.5 font-medium text-xs shadow-sm hover:shadow-md transition-all duration-200 w-100 sm:w-auto text-center" href="{{ route('admin.applications.index') }}">
                        <i class="bi bi-eye me-1" aria-hidden="true"></i> Lihat Semua Pendaftaran
                    </a>
                </div>

                <div class="w-full overflow-x-auto overflow-y-hidden border border-slate-200 rounded-xl table-responsive">
                    <table class="table align-middle mb-0 w-full">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th scope="col" class="whitespace-nowrap w-[1%] ps-3 py-2.5">Kode Pendaftaran</th>
                                <th scope="col" class="w-1/3 py-2.5">Nama Pemohon</th>
                                <th scope="col" class="w-1/3 py-2.5">Posisi Magang</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] py-2.5">Tanggal Daftar</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] text-center py-2.5">Status</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] text-end pe-3 py-2.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentApplications as $reg)
                                @php
                                    $sv = $reg->status->value;
                                    $badgeClass = $statusBadgeMap[$sv] ?? 'bg-secondary';
                                @endphp
                                <tr>
                                    <td class="whitespace-nowrap ps-3 font-monospace small text-primary fw-semibold">
                                        {{ $reg->nomor_pendaftaran }}
                                    </td>
                                    <td class="whitespace-normal break-words">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold rounded-2 flex-shrink-0">
                                                {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="fw-semibold text-slate-900 mb-0">{{ $reg->user?->name ?? 'N/A' }}</p>
                                                <p class="text-slate-500 small mb-0"><i class="bi bi-envelope me-1"></i>{{ $reg->user?->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-normal break-words">
                                        <span class="fw-semibold text-slate-900">{{ $reg->position?->nama_posisi ?? '-' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="fw-medium text-slate-800 small">{{ $reg->tanggal_submit?->translatedFormat('d M Y') ?? '-' }}</div>
                                        <small class="text-slate-500">{{ $reg->tanggal_submit?->translatedFormat('H:i') ?? '' }} WIB</small>
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1.5">
                                            {{ $reg->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 whitespace-nowrap">
                                        <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-light bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg px-2.5 py-1 font-medium text-xs shadow-sm transition-all duration-200 me-1">
                                            <i class="bi bi-eye me-1" aria-hidden="true"></i> Detail
                                        </a>
                                        @if ($reg->isAccepted())
                                            <a href="{{ route('admin.applications.reply-letter', $reg->id) }}" class="btn btn-outline-danger bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg px-2.5 py-1 font-medium text-xs shadow-sm transition-all duration-200">
                                                <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Surat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted opacity-50 mb-2">
                                            <i class="bi bi-journal-x fs-1"></i>
                                        </div>
                                        <p class="fw-semibold text-muted mb-1">Belum ada data pendaftaran magang</p>
                                        <small class="text-muted">Data pendaftaran terbaru dari peserta akan muncul di sini.</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statusChart').getContext('2d');
            
            // Fetch dynamic data from Laravel
            const underReview = {{ $stats['status_under_review'] ?? 0 }};
            const accepted = {{ $stats['status_accepted'] ?? 0 }};
            const rejected = {{ $stats['status_rejected'] ?? 0 }};
            const verification = {{ $stats['verifikasi_pending'] ?? 0 }};
            const totalPeserta = {{ $stats['total_peserta'] ?? 0 }};

            // Custom Plugin for Center Text (Scales proportionally)
            const centerTextPlugin = {
                id: 'centerText',
                beforeDraw: function(chart) {
                    var width = chart.width, height = chart.height, ctx = chart.ctx;
                    ctx.restore();
                    
                    // Main Number
                    var fontSize = (height / 120).toFixed(2);
                    ctx.font = "bold " + fontSize + "em sans-serif";
                    ctx.textBaseline = "middle";
                    ctx.fillStyle = "#212529";
                    var text = totalPeserta,
                        textX = Math.round((width - ctx.measureText(text).width) / 2),
                        textY = height / 2 - (height * 0.05);
                    ctx.fillText(text, textX, textY);
                    
                    // "Total" Subtext
                    ctx.font = "normal " + (fontSize * 0.35).toFixed(2) + "em sans-serif";
                    ctx.fillStyle = "#6c757d";
                    var labelText = "Total",
                        labelX = Math.round((width - ctx.measureText(labelText).width) / 2),
                        labelY = height / 2 + (height * 0.15);
                    ctx.fillText(labelText, labelX, labelY);
                    
                    ctx.save();
                }
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Review (' + underReview + ')', 
                        'Diterima (' + accepted + ')', 
                        'Ditolak (' + rejected + ')', 
                        'Perlu Verif (' + verification + ')'
                    ],
                    datasets: [{
                        data: [underReview, accepted, rejected, verification],
                        backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0d6efd'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Prevents forced scaling that breaks layout
                    cutout: '75%', // Thinner doughnut to fit center text better
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 6, // Tiny dots
                                padding: 10, // Reduced padding
                                font: { size: 9, family: 'sans-serif' } // Tiny font to fit single lines
                            }
                        },
                        datalabels: {
                            color: '#ffffff',
                            font: {
                                weight: 'bold',
                                size: 9,
                                family: 'sans-serif'
                            },
                            formatter: function(value, context) {
                                const dataset = context.chart.data.datasets[0];
                                const total = dataset.data.reduce((acc, curr) => acc + (Number(curr) || 0), 0);
                                if (!value || value === 0 || total === 0) return '';
                                const percentage = Math.round((value / total) * 100);
                                return percentage > 0 ? percentage + '%' : '';
                            }
                        }
                    },
                    layout: {
                        padding: 0 // Removes internal canvas padding
                    }
                },
                plugins: [centerTextPlugin, ChartDataLabels]
            });
        });
    </script>
@endpush

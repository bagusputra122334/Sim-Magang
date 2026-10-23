@extends('layouts.admin')

@php
    $statusBadgeMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-slate-100 text-slate-800 border border-slate-200',
        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-amber-100 text-amber-800 border border-amber-200',
        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-rose-100 text-rose-800 border border-rose-200',
    ];
@endphp

@section('title', 'Dashboard')

@section('content')
    @if(auth()->check() && (auth()->user()->is_default_password || auth()->user()->hasDefaultPassword() || \Illuminate\Support\Facades\Hash::check('password', auth()->user()->password)))
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/40 dark:text-red-200 dark:border-red-700 px-3 py-1.5 rounded-lg relative mb-2 d-flex align-items-center justify-content-between gap-2.5 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation-fill fs-5 text-red-600 flex-shrink-0"></i>
                <span class="fw-semibold text-xs sm:text-sm">Peringatan Keamanan: Anda masih menggunakan password default. Harap segera ganti password Anda di menu Pengaturan Akun.</span>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-danger text-nowrap flex-shrink-0 font-medium py-1 px-2.5 text-xs">Ganti Password</a>
        </div>
    @endif

    {{-- Header / Greeting --}}
    <div class="page-heading mb-2 py-0" style="margin-bottom: 0.5rem !important; padding-bottom: 0 !important;">
        <div class="page-heading-copy py-0" style="padding: 0 !important;">
            <h1 class="h4 mb-0.5 fw-bold text-gray-900 dark:text-white" style="line-height: 1.15; margin-bottom: 0.15rem !important;">Dashboard Administrator</h1>
            <p class="text-muted text-xs mb-0" style="line-height: 1.25; margin-top: 0 !important;">
                Selamat datang, <strong>{{ auth()->user()->name }}</strong> — Monitoring pendaftaran magang Diskominfo SP Kabupaten Tuban.
            </p>
        </div>
    </div>

    <!-- TOP ROW: Stat Cards & Chart -->
    <div class="flex flex-col lg:flex-row items-stretch gap-2.5 lg:gap-3 mb-2.5" style="margin-top: 0 !important;">
        <!-- TOP ROW LEFT: 6 Stat Cards (2 Shaf / 3x2) -->
        <div class="lg:w-2/3 flex flex-col justify-between">
            {{-- Statistics Cards (6 Metrics Grid - 2 Shaf Layout) --}}
            <section class="grid grid-cols-1 sm:grid-cols-3 sm:grid-rows-2 gap-2.5 h-full w-full" aria-label="Ringkasan Statistik Magang">
                {{-- Card 1: Total Posisi Magang --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">TOTAL POSISI MAGANG</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['total_positions'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (<span class="!text-emerald-700 dark:!text-emerald-400 font-bold" style="color: #047857 !important;">{{ $stats['total_positions_aktif'] }} aktif</span> / {{ $stats['total_positions'] }})
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-blue-100 dark:bg-blue-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-briefcase text-primary fs-6"></i>
                    </div>
                </div>

                {{-- Card 2: Total Pelamar / Peserta --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">TOTAL PESERTA TERDAFTAR</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['total_peserta'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (<span class="!text-emerald-700 dark:!text-emerald-400 font-bold" style="color: #047857 !important;">{{ $stats['total_peserta_verified'] }}</span> verifikasi)
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-emerald-100 dark:bg-emerald-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-people text-success fs-6"></i>
                    </div>
                </div>

                {{-- Card 3: Pending Applications --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">PERLU VERIFIKASI</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['verifikasi_pending'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (<span class="!text-amber-700 dark:!text-amber-400 font-bold" style="color: #b45309 !important;">{{ $stats['status_submitted'] }}</span> diajukan)
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-amber-100 dark:bg-amber-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-clock-history text-warning fs-6"></i>
                    </div>
                </div>

                {{-- Card 4: Under Review Applications --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">UNDER REVIEW</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['status_under_review'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (proses verifikasi)
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-amber-100 dark:bg-amber-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-search text-warning fs-6"></i>
                    </div>
                </div>

                {{-- Card 5: Accepted Applications --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">PENDAFTARAN DITERIMA</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['status_accepted'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (<span class="!text-emerald-700 dark:!text-emerald-400 font-bold" style="color: #047857 !important;">{{ $stats['percent_accepted'] }}%</span> diterima)
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-emerald-100 dark:bg-emerald-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-check-circle text-success fs-6"></i>
                    </div>
                </div>

                {{-- Card 6: Rejected Applications --}}
                <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 sm:p-3 flex flex-row items-center justify-between h-full bg-white dark:bg-slate-800" style="flex-direction: row !important;">
                    <div class="d-flex flex-column justify-content-center min-w-0 pe-2">
                        <span class="text-xs font-extrabold !text-slate-800 dark:!text-slate-100 tracking-wider uppercase text-nowrap truncate" style="color: #1e293b !important;">PENDAFTARAN DITOLAK</span>
                        <div class="d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span class="text-2xl font-black !text-black dark:!text-white my-0.5 block opacity-100 leading-none" style="color: #000000 !important; font-weight: 900 !important;">{{ number_format($stats['status_rejected'], 0, ',', '.') }}</span>
                            <span class="text-xs font-bold !text-slate-700 dark:!text-slate-200 text-nowrap truncate" style="color: #334155 !important;">
                                (<span class="!text-rose-700 dark:!text-rose-400 font-bold" style="color: #be123c !important;">{{ $stats['total_registrations'] > 0 ? round(($stats['status_rejected'] / $stats['total_registrations']) * 100, 1) : 0 }}%</span> ditolak)
                            </span>
                        </div>
                    </div>
                    <div class="rounded bg-rose-100 dark:bg-rose-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                        <i class="bi bi-x-circle text-danger fs-6"></i>
                    </div>
                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN: Chart Card Matched Height -->
        <div class="lg:w-1/3 flex flex-col">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-700/60 rounded-lg w-100 h-full d-flex flex-column justify-content-between mb-0 bg-white dark:bg-slate-800 p-3" style="padding: 0.75rem !important;">
                <div class="card-header bg-transparent border-0 p-0 mb-1 d-flex align-items-center justify-content-between">
                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        <i class="bi bi-pie-chart-fill me-1.5 text-primary"></i>Rekap Pendaftaran
                    </h6>
                </div>
                <!-- Padding internal rapat p-3 -->
                <div class="card-body p-0 text-center d-flex flex-column justify-content-center align-items-center flex-grow-1" style="min-height: 0;">
                    <!-- Canvas height matched to 6 cards -->
                    <div style="position: relative; height: 155px; width: 100%; display: flex; justify-content: center;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-1">
                        <p class="text-muted mb-0" style="font-size: 0.65rem;">Distribusi status pendaftaran magang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM ROW: Full-Width Recent Registrations Table -->
    <div class="row">
        <div class="col-12">
            <section class="panel p-3.5 shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800" style="padding: 0.875rem !important;">
                <div class="panel-header flex flex-row justify-between items-center flex-nowrap d-flex justify-content-between align-items-center flex-nowrap w-full w-100 gap-2.5 mb-2.5 pb-2" style="padding-bottom: 0.5rem !important; margin-bottom: 0.625rem !important;">
                    <div class="flex-grow-1 min-w-0">
                        <h2 class="h6 mb-0 section-title text-sm sm:text-base font-bold whitespace-nowrap text-nowrap truncate flex items-center">
                            <i class="bi bi-journal-text me-1.5 flex-shrink-0 text-primary" aria-hidden="true"></i>
                            <span class="whitespace-nowrap text-nowrap truncate">Pendaftaran Magang Terbaru</span>
                        </h2>
                    </div>
                    <a class="btn btn-outline-secondary bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg py-1 px-2 text-xs font-semibold shadow-sm hover:shadow-md transition-all duration-200 w-auto text-center flex-shrink-0 whitespace-nowrap text-nowrap" href="{{ route('admin.applications.index') }}">
                        <i class="bi bi-eye me-1" aria-hidden="true"></i> Lihat Semua Pendaftaran
                    </a>
                </div>

                <div class="w-full overflow-x-auto overflow-y-hidden border border-slate-200 dark:border-slate-700 rounded-lg table-responsive">
                    <table class="table table-hover align-middle mb-0 w-full text-xs sm:text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-700 border-bottom border-slate-200 dark:border-slate-600">
                            <tr>
                                <th scope="col" class="whitespace-nowrap w-[1%] ps-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Kode</th>
                                <th scope="col" class="w-1/3 px-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Nama Pemohon</th>
                                <th scope="col" class="w-1/3 px-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Posisi Magang</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] px-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Tanggal Daftar</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] text-center px-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Status</th>
                                <th scope="col" class="whitespace-nowrap w-[1%] text-end pe-2.5 py-1.5 font-extrabold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse ($recentApplications as $reg)
                                @php
                                    $sv = $reg->status->value;
                                    $badgeClass = $statusBadgeMap[$sv] ?? 'bg-secondary';
                                @endphp
                                <tr class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="whitespace-nowrap ps-2.5 py-1.5">
                                        <span class="font-monospace text-xs !text-blue-600 dark:!text-blue-400 font-bold">
                                            {{ $reg->nomor_pendaftaran }}
                                        </span>
                                    </td>
                                    <td class="whitespace-normal break-words px-2.5 py-1.5">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-sm bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 d-flex align-items-center justify-content-center font-bold rounded-2 flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                                {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-bold !text-slate-900 dark:!text-white text-sm block">{{ $reg->user?->name ?? 'N/A' }}</span>
                                                <span class="!text-slate-600 dark:!text-slate-300 text-xs block"><i class="bi bi-envelope me-1"></i>{{ $reg->user?->email ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-normal break-words px-2.5 py-1.5">
                                        <span class="font-semibold !text-slate-800 dark:!text-slate-200 text-xs">{{ $reg->position?->nama_posisi ?? '-' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-2.5 py-1.5">
                                        <div class="font-medium !text-slate-700 dark:!text-slate-300 text-xs">{{ $reg->tanggal_submit?->translatedFormat('d M Y') ?? '-' }}</div>
                                        <div class="font-medium !text-slate-700 dark:!text-slate-300 text-xs" style="font-size: 0.7rem;">{{ $reg->tanggal_submit?->translatedFormat('H:i') ?? '' }} WIB</div>
                                    </td>
                                    <td class="text-center whitespace-nowrap px-2.5 py-1.5">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                            {{ $reg->status->label() }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-2.5 py-1.5 whitespace-nowrap">
                                        <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-light bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg py-1 px-2 font-medium text-xs shadow-sm transition-all duration-200 me-1">
                                            <i class="bi bi-eye me-1" aria-hidden="true"></i> Detail
                                        </a>
                                        @if ($reg->isAccepted())
                                            <a href="{{ route('admin.applications.reply-letter', $reg->id) }}" class="btn btn-outline-danger bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg py-1 px-2 font-medium text-xs shadow-sm transition-all duration-200">
                                                <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Surat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted opacity-50 mb-1.5">
                                            <i class="bi bi-journal-x fs-2"></i>
                                        </div>
                                        <p class="fw-semibold text-muted mb-0.5 text-xs">Belum ada data pendaftaran magang</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">Data pendaftaran terbaru dari peserta akan muncul di sini.</small>
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

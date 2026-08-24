@props(['activeStep' => 1])

@php
    $steps = [
        1 => ['title' => 'Akun Dibuat', 'icon' => 'bi-person-check-fill'],
        2 => ['title' => 'Pilih Jenis Peserta', 'icon' => 'bi-card-checklist'],
        3 => ['title' => 'Lengkapi Biodata', 'icon' => 'bi-person-lines-fill'],
        4 => ['title' => 'Siap Mengajukan Magang', 'icon' => 'bi-send-check-fill'],
    ];
@endphp

<div class="card border border-primary-subtle bg-white shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            @foreach ($steps as $stepNum => $stepData)
                @php
                    $isCompleted = $stepNum < $activeStep || ($activeStep === 4 && $stepNum === 4);
                    $isActive = $stepNum === $activeStep;
                @endphp
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                         style="width: 38px; height: 38px; background-color: {{ $isCompleted ? '#198754' : ($isActive ? '#0d6efd' : '#cbd5e1') }}; font-size: 0.95rem;">
                        @if ($isCompleted)
                            <i class="bi bi-check-lg"></i>
                        @else
                            {{ $stepNum }}
                        @endif
                    </div>
                    <div class="d-none d-sm-block">
                        <span class="d-block text-muted text-uppercase fw-semibold" style="font-size: 10px;">Langkah {{ $stepNum }}</span>
                        <span class="fw-bold small {{ $isActive ? 'text-primary' : ($isCompleted ? 'text-success' : 'text-secondary') }}">
                            {{ $stepData['title'] }}
                        </span>
                    </div>
                </div>
                @if (!$loop->last)
                    <div class="d-none d-md-block text-muted opacity-50 px-1"><i class="bi bi-chevron-right"></i></div>
                @endif
            @endforeach
        </div>
    </div>
</div>

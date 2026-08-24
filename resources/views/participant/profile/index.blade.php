@extends('layouts.participant')
@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-9">


        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white py-4 px-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-white text-primary rounded-pill px-3 py-1.5 fw-bold">
                                {{ $profile->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                            </span>
                        </div>
                        <h3 class="mb-1 fw-bold">
                            <i class="bi bi-person-check-fill me-2"></i>{{ $profile->nama_lengkap ?? $profile->user?->name ?? 'Profil Peserta' }}
                        </h3>
                        <p class="mb-0 text-white-50 small">
                            <i class="bi bi-envelope-at me-1"></i>{{ $profile->user?->email ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-center text-md-end mt-3 mt-md-0">
                        <a href="{{ route('participant.profile.edit') }}" class="btn btn-light btn-lg fw-semibold shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Ubah Profil
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-lg-4 text-center">
                        <div class="mb-4">
                            @if($profile->foto_url)
                                <img src="{{ $profile->foto_url }}" alt="Foto Profil" class="foto-profile-round rounded-circle img-fluid mb-3 border border-4 border-white shadow"
                                     style="width: 180px; height: 180px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($profile->nama_lengkap ?? $profile->user?->name ?? 'Peserta') }}&size=180&background=0d6efd&color=fff&bold=true'">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($profile->nama_lengkap ?? $profile->user?->name ?? 'Peserta') }}&size=180&background=0d6efd&color=fff&bold=true"
                                     alt="Foto Profil Default" class="foto-profile-round rounded-circle img-fluid mb-3 border border-4 border-white shadow" style="width: 180px; height: 180px;">
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill py-2 px-3 fs-6">
                                <i class="bi bi-person-badge me-1"></i>{{ $profile->participantTypeLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <ul class="nav nav-tabs mb-4 border-bottom" id="profilTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab" aria-controls="pribadi" aria-selected="true">
                                    <i class="bi bi-person-vcard me-1"></i>Data Pribadi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="akademik-tab" data-bs-toggle="tab" data-bs-target="#akademik" type="button" role="tab" aria-controls="akademik" aria-selected="false">
                                    <i class="bi bi-mortarboard me-1"></i>Data Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="kontak-tab" data-bs-toggle="tab" data-bs-target="#kontak" type="button" role="tab" aria-controls="kontak" aria-selected="false">
                                    <i class="bi bi-telephone me-1"></i>Kontak
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profilTabContent">
                            <div class="tab-pane fade show active" id="pribadi" role="tabpanel" aria-labelledby="pribadi-tab">
                                <table class="table table-borderless table-striped align-middle">
                                    <tbody>
                                        <tr>
                                            <th style="width: 190px;" class="text-muted fw-normal"><i class="bi bi-person-badge me-2 text-primary"></i>NIK</th>
                                            <td class="fw-semibold">{{ $profile->nik ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal"><i class="bi bi-person me-2 text-primary"></i>Nama Lengkap</th>
                                            <td class="fw-semibold">{{ $profile->nama_lengkap ?? $profile->user?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal"><i class="bi bi-geo-alt me-2 text-primary"></i>Tempat, Tanggal Lahir</th>
                                            <td class="fw-semibold">
                                                {{ $profile->tempat_lahir ?? '-' }}
                                                @if($profile->tanggal_lahir), {{ $profile->tanggal_lahir->translatedFormat('d F Y') }} @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal"><i class="bi bi-gender-ambiguous me-2 text-primary"></i>Jenis Kelamin</th>
                                            <td>
                                                @if($profile->jenis_kelamin)
                                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1.5">
                                                        {{ $profile->jenis_kelamin->label() }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal align-baseline"><i class="bi bi-house-door me-2 text-primary"></i>Alamat Lengkap</th>
                                            <td class="fw-semibold lh-lg">{{ $profile->alamat ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="akademik" role="tabpanel" aria-labelledby="akademik-tab">
                                <table class="table table-borderless table-striped align-middle">
                                    <tbody>
                                        <tr>
                                            <th style="width: 190px;" class="text-muted fw-normal">
                                                <i class="bi bi-building me-2 text-primary"></i>{{ $profile->institutionLabel() }}
                                            </th>
                                            <td class="fw-bold text-primary fs-6">{{ $profile->institusi ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal">
                                                <i class="bi bi-card-heading me-2 text-primary"></i>{{ $profile->numberLabel() }}
                                            </th>
                                            <td class="fw-bold font-monospace"><code class="fs-6 text-primary">{{ $profile->numberValue() }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal">
                                                <i class="bi bi-diagram-3 me-2 text-primary"></i>{{ $profile->majorLabel() }}
                                            </th>
                                            <td class="fw-semibold">{{ $profile->jurusan ?? '-' }}</td>
                                        </tr>
                                        @if($profile->isMahasiswa())
                                            <tr>
                                                <th class="text-muted fw-normal"><i class="bi bg-primary-subtle me-2 text-primary"></i>Semester</th>
                                                <td>
                                                    @if($profile->semester)
                                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-3 py-1.5 fs-6">
                                                            Semester {{ $profile->semester }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th class="text-muted fw-normal"><i class="bi bi-calendar-check me-2 text-primary"></i>Tahun Angkatan</th>
                                            <td class="fw-semibold">{{ $profile->tahun_angkatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="kontak" role="tabpanel" aria-labelledby="kontak-tab">
                                <table class="table table-borderless table-striped align-middle">
                                    <tbody>
                                        <tr>
                                            <th style="width: 190px;" class="text-muted fw-normal"><i class="bi bi-envelope me-2 text-primary"></i>Email</th>
                                            <td class="fw-semibold">{{ $profile->user?->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted fw-normal"><i class="bi bi-whatsapp me-2 text-primary"></i>Nomor HP / WhatsApp</th>
                                            <td class="fw-semibold">{{ $profile->no_telepon ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

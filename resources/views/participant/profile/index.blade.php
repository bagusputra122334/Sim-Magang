@extends('layouts.participant')
@section('title', 'Profil Saya')

@section('content')
<div class="row justify-content-center mb-3">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 rounded-2 overflow-hidden mb-3">
            <div class="card-header bg-gradient bg-primary text-white py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-white text-primary rounded-pill px-2 py-0.5 text-xs fw-bold">
                                {{ $profile->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                            </span>
                        </div>
                        <h3 class="mb-0.5 h5 fw-bold">
                            <i class="bi bi-person-check-fill me-1.5"></i>{{ $profile->nama_lengkap ?? $profile->user?->name ?? 'Profil Peserta' }}
                        </h3>
                        <p class="mb-0 text-white-50 text-xs">
                            <i class="bi bi-envelope-at me-1"></i>{{ $profile->user?->email ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-center text-md-end mt-2 mt-md-0">
                        <a href="{{ route('participant.profile.edit') }}" class="btn bg-white hover:bg-gray-50 text-gray-800 rounded-lg font-bold shadow-sm transition-all px-3 py-1.5 text-xs">
                            <i class="bi bi-pencil-square me-1"></i>Ubah Profil
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-3 sm:p-4">
                <div class="row g-2.5">
                    <div class="col-lg-4 text-center">
                        <div class="mb-3">
                            @if($profile->foto_url)
                                <img src="{{ $profile->foto_url }}" alt="Foto Profil" class="foto-profile-round rounded-circle img-fluid mb-2 border border-2 border-white shadow-sm"
                                     style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($profile->nama_lengkap ?? $profile->user?->name ?? 'Peserta') }}&size=100&background=0d6efd&color=fff&bold=true'">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($profile->nama_lengkap ?? $profile->user?->name ?? 'Peserta') }}&size=100&background=0d6efd&color=fff&bold=true"
                                     alt="Foto Profil Default" class="foto-profile-round rounded-circle img-fluid mb-2 border border-2 border-white shadow-sm" style="width: 100px; height: 100px;">
                            @endif
                        </div>

                        <div class="d-grid gap-1">
                            <span class="badge bg-blue-100 text-blue-950 border border-blue-400 rounded-pill py-1 px-2.5 text-xs font-bold shadow-xs">
                                <i class="bi bi-person-badge me-1 text-blue-700"></i>{{ $profile->participantTypeLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <ul class="nav nav-tabs mb-2.5 border-bottom text-xs" id="profilTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold py-1.5 px-3" id="pribadi-tab" data-bs-toggle="tab" data-bs-target="#pribadi" type="button" role="tab" aria-controls="pribadi" aria-selected="true">
                                    <i class="bi bi-person-vcard me-1"></i>Data Pribadi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-1.5 px-3" id="akademik-tab" data-bs-toggle="tab" data-bs-target="#akademik" type="button" role="tab" aria-controls="akademik" aria-selected="false">
                                    <i class="bi bi-mortarboard me-1"></i>Data Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold py-1.5 px-3" id="kontak-tab" data-bs-toggle="tab" data-bs-target="#kontak" type="button" role="tab" aria-controls="kontak" aria-selected="false">
                                    <i class="bi bi-telephone me-1"></i>Kontak
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profilTabContent">
                            <div class="tab-pane fade show active" id="pribadi" role="tabpanel" aria-labelledby="pribadi-tab">
                                <table class="table table-borderless table-striped align-middle text-xs mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 180px;" class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-person-badge me-1.5 text-primary"></i>NIK</th>
                                            <td class="fw-semibold py-1.5 px-2 font-monospace">{{ $profile->nik ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-person me-1.5 text-primary"></i>Nama Lengkap</th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->nama_lengkap ?? $profile->user?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-geo-alt me-1.5 text-primary"></i>Tempat, Tanggal Lahir</th>
                                            <td class="fw-semibold py-1.5 px-2">
                                                {{ $profile->tempat_lahir ?? '-' }}
                                                @if($profile->tanggal_lahir), {{ $profile->tanggal_lahir->translatedFormat('d F Y') }} @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-gender-ambiguous me-1.5 text-primary"></i>Jenis Kelamin</th>
                                            <td class="py-1.5 px-2">
                                                @if($profile->jenis_kelamin)
                                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-0.5 text-xs">
                                                        {{ $profile->jenis_kelamin->label() }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 align-baseline py-1.5 px-2"><i class="bi bi-house-door me-1.5 text-primary"></i>Alamat Lengkap</th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->alamat ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="akademik" role="tabpanel" aria-labelledby="akademik-tab">
                                <table class="table table-borderless table-striped align-middle text-xs mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 180px;" class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2">
                                                <i class="bi bi-building me-1.5 text-primary"></i>{{ $profile->institutionLabel() }}
                                            </th>
                                            <td class="fw-bold text-primary py-1.5 px-2">{{ $profile->institusi ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2">
                                                <i class="bi bi-card-heading me-1.5 text-primary"></i>{{ $profile->numberLabel() }}
                                            </th>
                                            <td class="fw-bold font-monospace py-1.5 px-2"><code class="text-xs text-primary">{{ $profile->numberValue() }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2">
                                                <i class="bi bi-diagram-3 me-1.5 text-primary"></i>{{ $profile->majorLabel() }}
                                            </th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->jurusan ?? '-' }}</td>
                                        </tr>
                                        @if($profile->isMahasiswa())
                                            <tr>
                                                <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-award me-1.5 text-primary"></i>Semester</th>
                                                <td class="py-1.5 px-2">
                                                    @if($profile->semester)
                                                        <span class="badge bg-blue-100 text-blue-950 border border-blue-400 rounded-pill px-2.5 py-0.5 text-xs font-bold shadow-xs">
                                                            Semester {{ $profile->semester }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-calendar-check me-1.5 text-primary"></i>Tahun Angkatan</th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->tahun_angkatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="kontak" role="tabpanel" aria-labelledby="kontak-tab">
                                <table class="table table-borderless table-striped align-middle text-xs mb-0">
                                    <tbody>
                                        <tr>
                                            <th style="width: 180px;" class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-envelope me-1.5 text-primary"></i>Email</th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->user?->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-xs font-bold !text-slate-800 dark:!text-slate-200 py-1.5 px-2"><i class="bi bi-whatsapp me-1.5 text-primary"></i>Nomor HP / WhatsApp</th>
                                            <td class="fw-semibold py-1.5 px-2">{{ $profile->no_telepon ?? '-' }}</td>
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

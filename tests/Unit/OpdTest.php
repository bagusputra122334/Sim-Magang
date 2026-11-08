<?php

namespace Tests\Unit;

use App\Enums\PositionStatus;
use App\Enums\RegistrationStatus;
use App\Enums\UserRole;
use App\Models\Division;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpdTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_opd_with_factory(): void
    {
        $opd = Opd::factory()->create();

        $this->assertDatabaseHas('opds', [
            'id'   => $opd->id,
            'name' => 'Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban',
            'code' => 'diskominfo-sp',
            'slug' => 'diskominfo-sp',
        ]);
        $this->assertTrue($opd->is_active);
    }

    public function test_opd_auto_generates_slug_and_code(): void
    {
        $opd = Opd::create([
            'name' => 'Dinas Pendidikan Kabupaten Tuban',
            'code' => 'dinas-pendidikan',
        ]);

        $this->assertEquals('dinas-pendidikan', $opd->slug);
        $this->assertEquals('dinas-pendidikan', $opd->code);
    }

    public function test_opd_relationships_work_correctly(): void
    {
        $opd = Opd::factory()->create();

        $division = Division::create([
            'nama_divisi' => 'Bidang Komunikasi',
            'opd_id'      => $opd->id,
        ]);

        $position = Position::create([
            'nama_posisi' => 'Staff IT Diskominfo',
            'slug'        => 'staff-it-diskominfo',
            'deskripsi'   => 'Deskripsi staff',
            'kuota'       => 5,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd->id,
        ]);

        $user = User::factory()->create([
            'role'   => UserRole::Peserta,
            'opd_id' => $opd->id,
        ]);

        $registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9999',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'opd_id'               => $opd->id,
            'cv_path'              => 'cv/test.pdf',
            'surat_pengantar_path' => 'surat/test.pdf',
            'status'               => RegistrationStatus::Submitted,
        ]);

        // HasMany relations
        $this->assertTrue($opd->divisions->contains($division));
        $this->assertTrue($opd->positions->contains($position));
        $this->assertTrue($opd->users->contains($user));
        $this->assertTrue($opd->registrations->contains($registration));

        // BelongsTo inverse relations
        $this->assertEquals($opd->id, $division->opd->id);
        $this->assertEquals($opd->id, $position->opd->id);
        $this->assertEquals($opd->id, $user->opd->id);
        $this->assertEquals($opd->id, $registration->opd->id);
    }

    public function test_registration_inherits_opd_from_position_if_not_specified(): void
    {
        $opd = Opd::factory()->create();

        $position = Position::create([
            'nama_posisi' => 'Staff Desain',
            'slug'        => 'staff-desain',
            'deskripsi'   => 'Deskripsi desain',
            'kuota'       => 2,
            'status'      => PositionStatus::Aktif,
            'opd_id'      => $opd->id,
        ]);

        $user = User::factory()->create();

        $registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9998',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'cv/test2.pdf',
            'surat_pengantar_path' => 'surat/test2.pdf',
            'status'               => RegistrationStatus::Submitted,
        ]);

        $this->assertEquals($opd->id, $registration->opd_id);
    }

    public function test_entities_can_be_created_without_opd_id_backward_compatible(): void
    {
        $division = Division::create(['nama_divisi' => 'Divisi Tanpa OPD']);
        $position = Position::create([
            'nama_posisi' => 'Posisi Tanpa OPD',
            'slug'        => 'posisi-tanpa-opd',
            'deskripsi'   => 'Deskripsi',
            'kuota'       => 1,
            'status'      => PositionStatus::Aktif,
        ]);
        $user = User::factory()->create();
        $registration = Registration::create([
            'nomor_pendaftaran'    => 'MAGANG-2026-9997',
            'user_id'              => $user->id,
            'position_id'          => $position->id,
            'cv_path'              => 'cv/test3.pdf',
            'surat_pengantar_path' => 'surat/test3.pdf',
            'status'               => RegistrationStatus::Submitted,
        ]);

        $this->assertNull($division->opd_id);
        $this->assertNull($division->opd);
        $this->assertNull($position->opd_id);
        $this->assertNull($position->opd);
        $this->assertNull($user->opd_id);
        $this->assertNull($user->opd);
        $this->assertNull($registration->opd_id);
        $this->assertNull($registration->opd);
    }

    public function test_opd_supports_soft_deleting(): void
    {
        $opd = Opd::factory()->create();
        $opdId = $opd->id;

        $opd->delete();

        $this->assertSoftDeleted('opds', ['id' => $opdId]);
        $this->assertNull(Opd::find($opdId));
        $this->assertNotNull(Opd::withTrashed()->find($opdId));
    }
}

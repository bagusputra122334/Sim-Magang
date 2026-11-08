<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Opd;
use App\Models\Position;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultOpd = Opd::firstOrCreate(
            ['code' => 'diskominfo-sp'],
            [
                'name' => 'Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban',
                'slug' => 'diskominfo-sp',
                'address' => 'Jl. Kartini No. 2 Tuban',
                'phone' => '0356-321012',
                'email' => 'diskominfo@tubankab.go.id',
                'head_of_office_name' => 'Kepala Dinas Kominfo SP Kab. Tuban',
                'head_of_office_nip' => '197001011995011001',
                'logo_path' => null,
                'is_active' => true,
            ]
        );

        // Link unassigned records to default OPD if needed
        Division::whereNull('opd_id')->update(['opd_id' => $defaultOpd->id]);
        Position::whereNull('opd_id')->update(['opd_id' => $defaultOpd->id]);
        User::whereNull('opd_id')->update(['opd_id' => $defaultOpd->id]);
        Registration::whereNull('opd_id')->update(['opd_id' => $defaultOpd->id]);

        $this->call([
            LandingContentSeeder::class,
        ]);
    }
}

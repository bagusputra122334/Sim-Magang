<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divStatistik = Division::create(['nama_divisi' => 'Statistik']);
        Division::create(['nama_divisi' => 'Aplikasi & Persandian']);
        Division::create(['nama_divisi' => 'IKP']);

        User::create([
            'role' => UserRole::Pembimbing,
            'name' => 'Budi Pembimbing',
            'email' => 'pembimbing@test.com',
            'password' => Hash::make('password'),
            'nip' => '198001012005011001',
            'division_id' => $divStatistik->id,
        ]);

        User::create([
            'role' => UserRole::Intern,
            'name' => 'Andi Magang',
            'email' => 'intern@test.com',
            'password' => Hash::make('password'),
            'division_id' => $divStatistik->id,
        ]);
    }
}

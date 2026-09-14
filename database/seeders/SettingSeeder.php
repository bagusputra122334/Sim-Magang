<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Global Settings
            [
                'key'   => 'site_title',
                'value' => 'SIMAGANG — Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban',
                'type'  => 'text',
                'group' => 'global',
            ],
            [
                'key'   => 'app_name',
                'value' => 'SIMAGANG',
                'type'  => 'text',
                'group' => 'global',
            ],
            [
                'key'   => 'institution_name',
                'value' => 'Diskominfo SP Kab. Tuban',
                'type'  => 'text',
                'group' => 'global',
            ],
            [
                'key'   => 'site_logo',
                'value' => 'traveland/images/logo.png',
                'type'  => 'image',
                'group' => 'global',
            ],
            [
                'key'   => 'meta_description',
                'value' => 'Portal Resmi Pendaftaran Magang Diskominfo SP Kab. Tuban. Daftarkan dirimu secara digital!',
                'type'  => 'text',
                'group' => 'global',
            ],

            // Landing Page Settings
            [
                'key'   => 'hero_badge',
                'value' => 'Portal Resmi Pendaftaran Magang',
                'type'  => 'text',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'hero_title',
                'value' => 'Membangun Talenta Digital untuk Pelayanan Publik',
                'type'  => 'text',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'hero_description',
                'value' => 'SIMAGANG (Sistem Informasi Magang) merupakan portal resmi Diskominfo SP Kabupaten Tuban untuk memfasilitasi pendaftaran dan pengelolaan magang secara digital. Dapatkan pengalaman kerja nyata dan kembangkan kompetensimu melalui proses rekrutmen yang transparan, terintegrasi, dan 100% paperless.',
                'type'  => 'text',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'hero_image',
                'value' => 'traveland/images/1.png',
                'type'  => 'image',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'about_title',
                'value' => 'SIMAGANG Diskominfo SP',
                'type'  => 'text',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'about_description',
                'value' => 'Platform pendaftaran magang resmi untuk Mahasiswa dan Siswa SMK. Seluruh proses dilakukan 100% secara digital, terstruktur, dan transparan.',
                'type'  => 'text',
                'group' => 'landing_page',
            ],
            [
                'key'   => 'about_image',
                'value' => 'traveland/images/2.png',
                'type'  => 'image',
                'group' => 'landing_page',
            ],

            // Contact Settings
            [
                'key'   => 'contact_address',
                'value' => 'Jl. Mastrip No. 5 A, Sidorejo, Kec. Tuban, Jawa Timur 62315',
                'type'  => 'text',
                'group' => 'contact',
            ],
            [
                'key'   => 'contact_email',
                'value' => 'diskominfo@tubankab.go.id',
                'type'  => 'text',
                'group' => 'contact',
            ],
            [
                'key'   => 'contact_phone',
                'value' => '(0356) 8832697',
                'type'  => 'text',
                'group' => 'contact',
            ],
            [
                'key'   => 'contact_working_hours',
                'value' => "Senin - Jum'at: 07.30 - 16.00 WIB",
                'type'  => 'text',
                'group' => 'contact',
            ],
            [
                'key'   => 'social_website',
                'value' => 'https://diskominfo.tubankab.go.id',
                'type'  => 'url',
                'group' => 'contact',
            ],
            [
                'key'   => 'social_facebook',
                'value' => 'https://www.facebook.com/diskominfo.tuban',
                'type'  => 'url',
                'group' => 'contact',
            ],
            [
                'key'   => 'social_instagram',
                'value' => 'https://www.instagram.com/kominfo.tuban',
                'type'  => 'url',
                'group' => 'contact',
            ],
            [
                'key'   => 'social_twitter',
                'value' => 'https://twitter.com/DiskominfoTuban',
                'type'  => 'url',
                'group' => 'contact',
            ],
            [
                'key'   => 'social_youtube',
                'value' => 'https://www.youtube.com/channel/UC7V9cxzD7Gk-K_jxGMbblgA?view_as=subscriber',
                'type'  => 'url',
                'group' => 'contact',
            ],
            [
                'key'   => 'maps_embed_url',
                'value' => 'https://www.google.com/maps/embed?pb=!4v1788316632382!6m8!1m7!1szab-FoOpFkmJVJ79X0G0Pw!2m2!1d-6.901873934235668!2d112.0440727763729!3f117.32336345271811!4f-6.10453670657121!5f0.4000000000000002',
                'type'  => 'url',
                'group' => 'contact',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type'  => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }
    }
}

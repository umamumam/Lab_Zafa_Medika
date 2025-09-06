<?php

namespace Database\Seeders;

use App\Models\GrupTest;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GrupTestSeeder extends Seeder
{
    public function run(): void
    {
        $grupTests = [
            ['nama' => 'Hematologi'],
            ['nama' => 'Kimia Klinik'],
            ['nama' => 'Imunologi - Serologi'],
            ['nama' => 'Urinalisa'],
        ];

        foreach ($grupTests as $grup) {
            GrupTest::firstOrCreate($grup);
        }
    }
}

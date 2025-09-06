<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Metodebyr;

class MetodebyrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Cash'],
            ['nama' => 'BANK BRI'],
            ['nama' => 'BANK BCA'],
            ['nama' => 'BPJS'],
        ];

        foreach ($data as $item) {
            Metodebyr::create($item);
        }
    }
}

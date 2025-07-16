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
            ['nama' => 'QRIS'],
            ['nama' => 'Transfer'],
            ['nama' => 'MoU'],
            ['nama' => 'BPJS Kerjasama'],
            ['nama' => 'BPJS Prolanis / Lupis'],
            ['nama' => 'Mandiri InHealth'],
        ];

        foreach ($data as $item) {
            Metodebyr::create($item);
        }
    }
}

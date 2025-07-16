<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'kode' => '-',
                'nama' => '-',
                'value' => 0,
                'status' => 'Aktif',
                'keterangan' => 'Tanpa Diskon'
            ],
            [
                'kode' => 'V10',
                'nama' => 'Disc. 10%',
                'value' => 10,
                'status' => 'Aktif',
                'keterangan' => 'Diskon Rujukan RS / Sampel Perawat'
            ],
            [
                'kode' => 'V30',
                'nama' => 'Disc. 30%',
                'value' => 30,
                'status' => 'Aktif',
                'keterangan' => 'Diskon Apolik, Keluarga Pegawal, Sampel Pegawal'
            ],
            [
                'kode' => 'V100',
                'nama' => 'Disc. 100%',
                'value' => 100,
                'status' => 'Aktif',
                'keterangan' => 'Diskon Pegawal Laboratorium'
            ],
            [
                'kode' => 'V20',
                'nama' => 'Disc. 20%',
                'value' => 20,
                'status' => 'Aktif',
                'keterangan' => 'Bawa sampel petugas lab'
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}

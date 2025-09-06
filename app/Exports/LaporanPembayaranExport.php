<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $visits;
    private $rowNumber = 0;

    public function __construct(Collection $visits)
    {
        $this->visits = $visits;
    }

    public function collection()
    {
        return $this->visits;
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Lab',
            'Nama Pasien',
            'Tanggal Order',
            'Total',
            'Tagihan Dibayar',
            'Sisa Pembayaran',
            'Metode Bayar',
            'Tgl Klaim',
            'Kasir',
        ];
    }

    public function map($visit): array
    {
        $this->rowNumber++;
        $sisaPembayaran = $visit->jenis_pasien == 'BPJS' ? 0 : ($visit->total_tagihan - $visit->dibayar);
        $metodeBayar = $visit->penerimaan->metodeBayar->nama ?? '-';
        if ($visit->jenis_pasien == 'BPJS' && ($visit->penerimaan->jumlah ?? 0) == 0) {
            $metodeBayar = 'BPJS-K';
        } else {
            $metodeBayar = str_replace('BPJS', 'BPJS-K', $metodeBayar);
        }

        return [
            $this->rowNumber,
            $visit->no_order,
            $visit->pasien->nama ?? 'N/A',
            $visit->tgl_order->format('d/m/Y H:i'),
            $visit->total_tagihan,
            $visit->dibayar,
            $sisaPembayaran,
            $metodeBayar,
            $visit->penerimaan->created_at->format('d/m/Y') ?? '-',
            $visit->penerimaan->user->name ?? '-',
        ];
    }
}

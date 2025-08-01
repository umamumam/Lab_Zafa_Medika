<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanTahunanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $laporan;
    protected $tahun;
    private $rowCounter = 0;

    public function __construct(array $laporan, int $tahun)
    {
        $this->laporan = $laporan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = new Collection();
        foreach ($this->laporan as $item) {
            if ($item['is_separator']) {
                $data->push([$item['grup']]);
            } else {
                $row = [$item['nama']];
                for ($i = 1; $i <= 12; $i++) {
                    $row[] = $item['per_bulan'][$i];
                }
                $data->push($row);
            }
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'NAMA PEMERIKSAAN',
            'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES',
        ];
    }

    public function map($row): array
    {
        return $row;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 2);

                $sheet->setCellValue('A1', 'Laporan Tahunan Pemeriksaan Laboratorium - ' . $this->tahun);
                $sheet->mergeCells('A1:M1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle('A3:M3')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFD9E1F2']],
                ]);

                $maxRow = $sheet->getHighestRow();
                for ($i = 4; $i <= $maxRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();
                    if (in_array($cellValue, ['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya'])) {
                        $sheet->getStyle('A' . $i . ':M' . $i)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFE0E0E0']],
                        ]);
                        $sheet->mergeCells('A' . $i . ':M' . $i);
                    }
                }
            },
        ];
    }
}

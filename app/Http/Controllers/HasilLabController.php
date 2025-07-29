<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Pasien;
use App\Models\HasilLab;
use App\Models\VisitTest;
use App\Models\DetailTest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class HasilLabController extends Controller
{
    public function edit($visitId)
    {
        $visit = Visit::with([
            'pasien',
            'dokter',
            'ruangan',
            'visitTests' => function ($query) {
                $query->with([
                    'test.detailTests' => function ($q) {
                        $q->where('status', 'Aktif')->orderBy('urutan');
                    },
                    'hasilLabs' => function ($q) {
                        $q->orderBy('id')->with('detailTest');
                    }
                ]);
            }
        ])->findOrFail($visitId);

        return view('hasil_lab.edit', compact('visit'));
    }

    public function update(Request $request, $visitId)
    {
        $request->validate([
            'hasil' => 'required|array',
            'hasil.*' => 'nullable|string',
            'kesan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->hasil as $id => $value) {
                $hasilLab = HasilLab::with(['detailTest', 'visitTest.test'])->findOrFail($id);
                $data = ['hasil' => $value !== '' ? $value : null];
                if (is_numeric($value)) {
                    $minRef = null;
                    $maxRef = null;

                    if ($hasilLab->detailTest) {
                        $minRef = $hasilLab->detailTest->min;
                        $maxRef = $hasilLab->detailTest->max;
                    } else {
                        $test = $hasilLab->visitTest->test;
                        if ($test->nilai_normal) {
                            $refRange = explode('-', $test->nilai_normal);
                            if (count($refRange) == 2) {
                                $minRef = trim($refRange[0]);
                                $maxRef = trim($refRange[1]);
                            }
                        }
                    }

                    if ($minRef !== null && $maxRef !== null) {
                        $numericValue = floatval($value);
                        $numericMin = floatval($minRef);
                        $numericMax = floatval($maxRef);
                        if ($numericValue > $numericMax) {
                            $data['flag'] = 'H';
                        } elseif ($numericValue < $numericMin) {
                            $data['flag'] = 'L';
                        } else {
                            $data['flag'] = null;
                        }
                    }
                } else {
                    $data['flag'] = null;
                }
                $hasilLab->update($data);
            }
            $visit = Visit::findOrFail($visitId);
            $visit->update([
                'kesan' => $request->kesan,
                'catatan' => $request->catatan,
            ]);

            DB::commit();
            return redirect()->route('hasil-lab.edit', $visitId)
                ->with('success', 'Hasil laboratorium berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui hasil: ' . $e->getMessage());
        }
    }

    public function validateResults(Request $request, $visitId)
    {
        DB::beginTransaction();

        try {
            $visit = Visit::with('visitTests.hasilLabs')->findOrFail($visitId);
            $hasilBelumValid = HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->where('status', 'Belum Valid')->exists();
            if (!$hasilBelumValid) {
                return back()->with('info', 'Semua hasil sudah divalidasi sebelumnya.');
            }
            HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->where('status', 'Belum Valid')->update([
                'status' => 'Valid',
                'validator_id' => auth()->id(),
                'validated_at' => now(),
            ]);
            $visit->update(['status_order' => 'Selesai']);
            DB::commit();
            return redirect()->route('visits.show', $visitId)
                ->with('success', 'Semua hasil laboratorium berhasil divalidasi dan status kunjungan diubah menjadi "Selesai".');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memvalidasi hasil: ' . $e->getMessage());
        }
    }
    public function unvalidateResults(Request $request, $visitId)
    {
        DB::beginTransaction();

        try {
            HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->update([
                'status' => 'Belum Valid',
                'validator_id' => null,
                'validated_at' => null,
            ]);
            $visit = Visit::findOrFail($visitId);
            $visit->update(['status_order' => 'Proses']);
            DB::commit();
            return redirect()->route('hasil-lab.edit', $visitId)
                ->with('success', 'Validasi berhasil dibatalkan dan status kunjungan dikembalikan ke Proses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan validasi: ' . $e->getMessage());
        }
    }
    public function validateAdmin(Request $request, $visitId)
    {
        DB::beginTransaction();

        try {
            $visit = Visit::with('visitTests.hasilLabs')->findOrFail($visitId);
            $hasilBelumValid = HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->where('status', 'Belum Valid')->exists();
            if (!$hasilBelumValid) {
                return back()->with('info', 'Semua hasil sudah divalidasi sebelumnya.');
            }
            HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->where('status', 'Belum Valid')->update([
                'status' => 'Valid',
                'validator_id' => auth()->id(),
                'validated_at' => now(),
            ]);
            $visit->update(['status_order' => 'Selesai']);
            DB::commit();
            return redirect()->route('visits.validasi', $visitId)
                ->with('success', 'Semua hasil laboratorium berhasil divalidasi dan status kunjungan diubah menjadi "Selesai".');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memvalidasi hasil: ' . $e->getMessage());
        }
    }
    public function unvalidateAdmin(Request $request, $visitId)
    {
        DB::beginTransaction();

        try {
            HasilLab::whereHas('visitTest', function ($q) use ($visitId) {
                $q->where('visit_id', $visitId);
            })->update([
                'status' => 'Belum Valid',
                'validator_id' => null,
                'validated_at' => null,
            ]);
            $visit = Visit::findOrFail($visitId);
            $visit->update(['status_order' => 'Proses']);
            DB::commit();
            return redirect()->route('visits.validasi', $visitId)
                ->with('success', 'Validasi berhasil dibatalkan dan status kunjungan dikembalikan ke Proses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan validasi: ' . $e->getMessage());
        }
    }

    public function print($no_order)
    {
        $visit = Visit::with([
            'pasien',
            'dokter',
            'ruangan',
            'visitTests' => function ($query) {
                $query->with([
                    'test.detailTests' => function ($q) {
                        $q->where('status', 'Aktif')->orderBy('urutan');
                    },
                    'hasilLabs' => function ($q) {
                        $q->orderBy('id')->with('detailTest');
                    }
                ]);
            }
        ])->where('no_order', $no_order)->firstOrFail();

        // Data untuk verifikator
        $verifikator = 'Belum Divalidasi';
        $tanggalValidasi = 'Belum Divalidasi';
        $jamSampling = '-';
        $jamSelesai = '-';
        $firstValidated = HasilLab::whereHas('visitTest', function ($q) use ($visit) {
            $q->where('visit_id', $visit->id);
        })->whereNotNull('validator_id')->first();

        if ($firstValidated) {
            $verifikator = $firstValidated->validator->name ?? 'Unknown';
            $tanggalValidasi = $firstValidated->validated_at->format('d-m-Y');
            $jamSampling = $firstValidated->created_at->format('d-m-Y H:i:s');
            $jamSelesai = $firstValidated->validated_at->format('d-m-Y H:i:s');
        }

        // Generate barcode/QR code
        $barcodeData = "Hasil Lab Klinik Zafa Medika - ";
        $barcodeData .= "No. Order: " . $visit->no_order . " - ";
        $barcodeData .= "Pasien: " . $visit->pasien->nama . " - ";
        $barcodeData .= "Tgl. Order: " . $visit->tgl_order->format('d-m-Y H:i');
        $barcode = DNS2D::getBarcodeHTML($barcodeData, 'QRCODE', 2, 2);

        $pdf = PDF::loadView('hasil_lab.print', compact('visit', 'verifikator', 'tanggalValidasi', 'barcode', 'jamSampling', 'jamSelesai'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('hasil_lab_' . $visit->no_order . '.pdf');
    }

    public function downloadByHash($hash)
    {
        $visit = Visit::all()->first(function ($v) use ($hash) {
            return md5($v->id) === $hash;
        });
        if (!$visit) {
            abort(404);
        }
        return $this->print($visit->no_order); // atau view cetak
    }

    public function cetakRiwayat($norm)
    {
        $pasien = Pasien::with(['visits' => function ($query) {
            $query->with([
                'dokter',
                'ruangan',
                'visitTests' => function ($q) {
                    $q->with([
                        'test.detailTests' => function ($dt) {
                            $dt->where('status', 'Aktif')->orderBy('urutan');
                        },
                        'hasilLabs' => function ($hl) {
                            $hl->orderBy('id')->with('detailTest');
                        }
                    ]);
                }
            ])->orderBy('tgl_order', 'desc');
        }])->where('norm', $norm)->firstOrFail();

        // Generate barcode
        $barcodeData = "Riwayat Pemeriksaan Klinik Zafa Medika - ";
        $barcodeData .= "Pasien: " . $pasien->nama . " - ";
        $barcodeData .= "No. RM: " . $pasien->norm . " - ";
        $barcodeData .= "Tgl. Cetak: " . now()->format('d-m-Y H:i:s');
        $barcode = DNS1D::getBarcodeHTML($barcodeData, 'C128', 1, 25);

        $data = [
            'pasien' => $pasien,
            'visits' => $pasien->visits,
            'tanggalCetak' => now()->format('d-m-Y H:i:s'),
            'barcode' => $barcode,
            'verifikator' => 'Riwayat Pemeriksaan',
            'tanggalValidasi' => now()->format('d-m-Y')
        ];

        $pdf = PDF::loadView('hasil_lab.riwayat', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('riwayat_pemeriksaan_' . $pasien->norm . '.pdf');
    }
}

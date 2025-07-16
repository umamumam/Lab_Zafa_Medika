<?php

namespace App\Http\Controllers;

use App\Models\HasilLab;
use App\Models\DetailTest;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\VisitTest;
use Illuminate\Support\Facades\DB;

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
    // =================blm selesai==================== //
    public function print($id)
    {
        $visit = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test.detailTests', 'visitTests.hasilLabs'])
            ->findOrFail($id);

        return view('hasil_lab.print', compact('visit'));
    }
}

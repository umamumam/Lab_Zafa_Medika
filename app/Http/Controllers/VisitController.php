<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Test;
use App\Models\Visit;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\Voucher;
use App\Models\HasilLab;
use App\Models\Metodebyr;
use App\Models\VisitTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['pasien', 'dokter', 'visitTests.test', 'ruangan'])
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.index', compact('visits'));
    }
    public function create()
    {
        $pasiens = Pasien::all();
        $dokters = Dokter::where('status', 'Aktif')->get();
        $ruangans = Ruangan::all();
        $tests = Test::where('status', 'Aktif')->get();
        $vouchers = Voucher::where('status', 'Aktif')->get();
        $metodePembayarans = Metodebyr::all();

        // Generate preview nomor order
        $today = now()->format('Ymd');
        $prefix = 'PK' . $today;
        $lastOrder = Visit::where('no_order', 'like', $prefix . '%')
            ->orderBy('no_order', 'desc')
            ->first();

        $lastNumber = $lastOrder ? (int) substr($lastOrder->no_order, -3) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $no_order = $prefix . $newNumber;

        return view('visits.create', compact(
            'pasiens',
            'dokters',
            'ruangans',
            'tests',
            'vouchers',
            'metodePembayarans',
            'no_order'
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'jenis_pasien' => 'required|in:Umum,BPJS',
            'dokter_id' => 'nullable|exists:dokters,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'diagnosa' => 'nullable|string',
            'jenis_order' => 'required|in:Reguler,Cito',
            'tests' => 'required|array',
            'tests.*.test_id' => 'required|exists:tests,id',
            'tests.*.jumlah' => 'required|integer|min:1',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'metodebyr_id' => 'nullable|exists:metodebyrs,id',
        ]);

        DB::beginTransaction();

        try {
            $today = Carbon::now();
            $prefix = 'PK' . $today->format('Ymd');
            $lastOrder = Visit::where('no_order', 'like', $prefix . '%')->latest()->first();

            $number = 1;
            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->no_order, -3);
                $number = $lastNumber + 1;
            }

            $no_order = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
            $visit = Visit::create([
                'no_order' => $no_order,
                'tgl_order' => $today,
                'user_id' => auth()->id(),
                'pasien_id' => $request->pasien_id,
                'jenis_pasien' => $request->jenis_pasien,
                'dokter_id' => $request->dokter_id,
                'ruangan_id' => $request->ruangan_id,
                'diagnosa' => $request->diagnosa,
                'jenis_order' => $request->jenis_order,
                'total_tagihan' => 0,
                'voucher_id' => $request->voucher_id,
                'metodebyr_id' => $request->metodebyr_id,
                'status_pembayaran' => 'Belum Lunas',
                'status_order' => 'Sampling',
            ]);
            foreach ($request->tests as $test) {
                $testModel = Test::find($test['test_id']);
                $harga = $request->jenis_pasien == 'BPJS' ? $testModel->harga_bpjs : $testModel->harga_umum;
                VisitTest::create([
                    'visit_id' => $visit->id,
                    'test_id' => $test['test_id'],
                    'harga' => $harga,
                    'jumlah' => $test['jumlah'],
                    'subtotal' => $harga * $test['jumlah'],
                ]);
            }
            if ($request->voucher_id) {
                $visit->voucher_id = $request->voucher_id;
                $visit->save();
            }
            DB::commit();
            return redirect()->route('visits.show', $visit->id)
                ->with('success', 'Order berhasil dibuat dengan nomor: ' . $no_order);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $visit = Visit::with([
            'pasien',
            'dokter',
            'ruangan',
            'voucher',
            'metodePembayaran',
            'visitTests.test',
            'user'
        ])->findOrFail($id);
        $metodePembayarans = Metodebyr::all();
        return view('visits.show', compact('visit', 'metodePembayarans'));
    }

    public function edit($id)
    {
        $visit = Visit::with([
            'visitTests.test',
            'pasien',
            'dokter',
            'ruangan.dokter',
            'voucher',
            'metodePembayaran'
        ])->findOrFail($id);
        $pasiens = Pasien::all();
        $dokters = Dokter::where('status', 'Aktif')->get();
        $ruangans = Ruangan::all();
        $tests = Test::where('status', 'Aktif')->get();
        $vouchers = Voucher::where('status', 'Aktif')->get();
        $metodePembayarans = Metodebyr::all();

        return view('visits.edit', compact(
            'visit',
            'pasiens',
            'dokters',
            'ruangans',
            'tests',
            'vouchers',
            'metodePembayarans'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'jenis_pasien' => 'required|in:Umum,BPJS',
            'dokter_id' => 'nullable|exists:dokters,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'diagnosa' => 'nullable|string',
            'jenis_order' => 'required|in:Reguler,Cito',
            'tests' => 'required|array',
            'tests.*.test_id' => 'required|exists:tests,id',
            'tests.*.jumlah' => 'required|integer|min:1',
            'tests.*.id' => 'nullable|exists:visit_tests,id',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'metodebyr_id' => 'nullable|exists:metodebyrs,id',
        ]);

        DB::beginTransaction();
        try {
            $visit = Visit::with(['visitTests'])->findOrFail($id);
            $visit->fill($request->only([
                'pasien_id',
                'jenis_pasien',
                'dokter_id',
                'ruangan_id',
                'diagnosa',
                'jenis_order',
                'voucher_id',
                'metodebyr_id'
            ]));
            $existingIds = collect($request->tests)->filter(function ($t) {
                return isset($t['id']);
            })->pluck('id')->toArray();
            $visit->visitTests()->whereNotIn('id', $existingIds)->delete();
            foreach ($request->tests as $test) {
                $harga = $request->jenis_pasien == 'BPJS'
                    ? Test::find($test['test_id'])->harga_bpjs
                    : Test::find($test['test_id'])->harga_umum;
                if (isset($test['id'])) {
                    $visit->visitTests()
                        ->where('id', $test['id'])
                        ->update([
                            'harga' => $harga,
                            'jumlah' => $test['jumlah'],
                            'subtotal' => $harga * $test['jumlah']
                        ]);
                } else {
                    $visit->visitTests()->create([
                        'test_id' => $test['test_id'],
                        'harga' => $harga,
                        'jumlah' => $test['jumlah'],
                        'subtotal' => $harga * $test['jumlah']
                    ]);
                }
            }

            $visit->save();
            DB::commit();

            return redirect()->route('visits.show', $visit->id)
                ->with('success', 'Order berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }
    public function pembayaran(Request $request, $id)
    {
        $request->validate([
            'dibayar' => 'required|numeric|min:0',
            'metodebyr_id' => 'required|exists:metodebyrs,id',
        ]);

        DB::beginTransaction();
        try {
            $visit = Visit::findOrFail($id);
            $totalDibayar = $visit->dibayar + $request->dibayar;
            $visit->update([
                'dibayar' => $totalDibayar,
                'metodebyr_id' => $request->metodebyr_id,
                'status_pembayaran' => ($totalDibayar >= $visit->total_tagihan) ? 'Lunas' : 'Belum Lunas',
            ]);
            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_order' => 'required|in:Sampling,Proses,Selesai',
        ]);
        DB::beginTransaction();
        try {
            $visit = Visit::with([
                'visitTests.test.detailTests' => function ($query) {
                    $query->where('status', 'Aktif')->orderBy('urutan');
                }
            ])->findOrFail($id);
            $originalStatus = $visit->status_order;
            $visit->update(['status_order' => $request->status_order]);
            if ($originalStatus !== 'Proses' && $request->status_order === 'Proses') {
                foreach ($visit->visitTests as $visitTest) {
                    $test = $visitTest->test;
                    HasilLab::where('visit_test_id', $visitTest->id)
                        ->where('status', 'Belum Valid')
                        ->delete();
                    if ($test->detailTests->isNotEmpty()) {
                        foreach ($test->detailTests as $detailTest) {
                            HasilLab::create([
                                'visit_test_id' => $visitTest->id,
                                'test_id' => $test->id,
                                'detail_test_id' => $detailTest->id,
                                'status' => 'Belum Valid',
                            ]);
                        }
                    }
                    HasilLab::create([
                        'visit_test_id' => $visitTest->id,
                        'test_id' => $test->id,
                        'detail_test_id' => null,
                        'status' => 'Belum Valid',
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', 'Status order berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $visit = Visit::findOrFail($id);
            $visit->visitTests()->delete();
            $visit->delete();
            DB::commit();
            return redirect()->route('visits.index')
                ->with('success', 'Order berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus order: ' . $e->getMessage());
        }
    }
    public function sampling()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test'])
            ->where('status_order', 'Sampling')
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.sampling', compact('visits'));
    }
    public function pemeriksaan()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test'])
            ->where('status_order', 'Proses')
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.pemeriksaan', compact('visits'));
    }
    public function validasi()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs'])
            ->whereIn('status_order', ['Proses', 'Selesai'])
            ->orderBy('tgl_order', 'desc')
            ->get()
            ->map(function ($visit) {
                $hasUnvalidatedResults = false;
                $hasResults = false;
                foreach ($visit->visitTests as $visitTest) {
                    foreach ($visitTest->hasilLabs as $hasilLab) {
                        $hasResults = true;
                        if ($hasilLab->status !== 'Valid') {
                            $hasUnvalidatedResults = true;
                        }
                    }
                }
                $visit->verifikasi_status = $hasResults
                    ? ($hasUnvalidatedResults ? 'Belum Valid' : 'Valid')
                    : 'Tidak Ada Hasil';
                return $visit;
            });
        return view('visits.validasi', compact('visits'));
    }
    public function Cetak()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs'])
            ->where('status_order', 'Selesai')
            ->orderBy('tgl_order', 'desc')
            ->get();
        return view('visits.cetak', compact('visits'));
    }
    public function Bayar()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs', 'penerimaan'])
            ->whereIn('status_pembayaran', ['Belum Lunas', 'Lunas'])
            ->whereDoesntHave('penerimaan', function ($query) {
                $query->where('status', 'Terklaim');
            })
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.bayar', compact('visits'));
    }
    public function laporanPembayaran()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests', 'penerimaan.metodeBayar', 'penerimaan.user'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('status', 'Terklaim');
            })
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.laporan-pembayaran', compact('visits'));
    }
}

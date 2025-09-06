<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Test;
use App\Models\Paket;
use App\Models\Visit;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\Voucher;
use App\Models\HasilLab;
use App\Models\Metodebyr;
use App\Models\VisitTest;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Riskihajar\Terbilang\Terbilang;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTahunanExport;
use Illuminate\Support\Facades\Config;
use App\Exports\LaporanPembayaranExport;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['pasien', 'dokter', 'visitTests.test', 'ruangan', 'paket'])
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.index', compact('visits'));
    }
    public function create()
    {
        $pasiens = Pasien::all();
        $dokters = Dokter::where('status', 'Aktif')->get();
        $ruangans = Ruangan::all();
        $tests = Test::where('status', 'Aktif')->with('grupTest')->get();
        $vouchers = Voucher::where('status', 'Aktif')->get();
        $metodePembayarans = Metodebyr::all();
        $pakets = Paket::where('status', 'Aktif')->get();

        // Generate preview nomor order
        $now = now();
        $today = $now->format('d/m/Y H:i');
        $prefix = 'PK' . now()->format('Ymd');
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
            'no_order',
            'pakets',
            'today'
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
            'voucher_id' => 'nullable|exists:vouchers,id',
            'metodebyr_id' => 'nullable|exists:metodebyrs,id',
            'paket_id' => 'nullable|exists:pakets,id',
            // 'tests' hanya dibutuhkan jika tidak ada paket_id
            'tests' => 'nullable|array',
            'tests.*.test_id' => 'required_with:tests|exists:tests,id',
            'tests.*.jumlah' => 'required_with:tests|integer|min:1',
            'tgl_order' => 'nullable|date_format:d/m/Y H:i',
        ]);
        if (!$request->filled('paket_id') && (!isset($request->tests) || empty($request->tests))) {
            return back()->withErrors(['general' => 'Pilih setidaknya satu paket atau satu tes.'])->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->filled('tgl_order')) {
                $tglOrder = Carbon::createFromFormat('d/m/Y H:i', $request->tgl_order);
            } else {
                $tglOrder = Carbon::now();
            }
            $prefix = 'PK' . $tglOrder->format('Ymd');
            $lastOrder = Visit::where('no_order', 'like', $prefix . '%')
                ->whereDate('tgl_order', $tglOrder->toDateString())
                ->latest()->first();

            $number = 1;
            if ($lastOrder) {
                $lastNumber = (int) substr($lastOrder->no_order, -3);
                $number = $lastNumber + 1;
            }

            $no_order = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

            // Buat entri Visit
            $visit = Visit::create([
                'no_order' => $no_order,
                'tgl_order' => $tglOrder,
                'user_id' => auth()->id(),
                'pasien_id' => $request->pasien_id,
                'jenis_pasien' => $request->jenis_pasien,
                'dokter_id' => $request->dokter_id,
                'ruangan_id' => $request->ruangan_id,
                'diagnosa' => $request->diagnosa,
                'jenis_order' => $request->jenis_order,
                'total_tagihan' => 0,
                'total_diskon' => 0,
                'voucher_id' => $request->voucher_id,
                'metodebyr_id' => $request->metodebyr_id,
                'dibayar' => 0,
                'status_pembayaran' => 'Belum Lunas',
                'status_order' => 'Sampling',
                'paket_id' => $request->paket_id,
            ]);

            if ($request->filled('paket_id')) {
                $paket = Paket::with('paketItems.test')->findOrFail($request->paket_id);
                foreach ($paket->paketItems as $paketItem) {
                    $testModel = $paketItem->test;
                    if ($testModel) {
                        $harga = $request->jenis_pasien == 'BPJS' ? $testModel->harga_bpjs : $testModel->harga_umum;
                        $subtotal = $harga * $paketItem->jumlah;
                        VisitTest::create([
                            'visit_id' => $visit->id,
                            'test_id' => $testModel->id,
                            'harga' => $harga,
                            'jumlah' => $paketItem->jumlah,
                            'subtotal' => $subtotal,
                        ]);
                    }
                }
            } else {
                foreach ($request->tests as $test) {
                    $testModel = Test::find($test['test_id']);
                    $harga = $request->jenis_pasien == 'BPJS' ? $testModel->harga_bpjs : $testModel->harga_umum;
                    $subtotal = $harga * $test['jumlah'];

                    VisitTest::create([
                        'visit_id' => $visit->id,
                        'test_id' => $test['test_id'],
                        'harga' => $harga,
                        'jumlah' => $test['jumlah'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }
            $visit->calculateTotal();
            if ($request->jenis_pasien === 'BPJS') {
                $visit->dibayar = 0;
                $visit->status_pembayaran = 'Lunas';
                $visit->save();
            }
            DB::commit();
            return redirect()->route('visits.show', $visit->id)
                ->with('success', 'Order berhasil dibuat dengan nomor: ' . $no_order);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat order: ' . $e->getMessage())->withInput();
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
            'user',
            'paket'
        ])->findOrFail($id);
        $metodePembayarans = Metodebyr::all();
        return view('visits.show', compact('visit', 'metodePembayarans'));
    }

    public function edit($id)
    {
        $visit = Visit::with([
            'pasien',
            'dokter',
            'ruangan',
            'voucher',
            'metodePembayaran',
            'visitTests.test',
            'paket'
        ])->findOrFail($id);

        $pasiens = Pasien::all();
        $dokters = Dokter::where('status', 'Aktif')->get();
        $ruangans = Ruangan::all();
        $tests = Test::where('status', 'Aktif')->get();
        $vouchers = Voucher::where('status', 'Aktif')->get();
        $metodePembayarans = Metodebyr::all();
        $pakets = Paket::where('status', 'Aktif')->get();

        return view('visits.edit', compact(
            'visit',
            'pasiens',
            'dokters',
            'ruangans',
            'tests',
            'vouchers',
            'metodePembayarans',
            'pakets'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validasi data dari form
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'jenis_pasien' => 'required|in:Umum,BPJS',
            'dokter_id' => 'nullable|exists:dokters,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'diagnosa' => 'nullable|string',
            'jenis_order' => 'required|in:Reguler,Cito',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'metodebyr_id' => 'nullable|exists:metodebyrs,id',
            'paket_id' => 'nullable|exists:pakets,id',
            'tests' => 'nullable|array',
            'tests.*.test_id' => 'required_with:tests|exists:tests,id',
            'tests.*.jumlah' => 'required_with:tests|integer|min:1',
            'tests.*.id' => 'nullable|exists:visit_tests,id', // Validasi untuk id VisitTest yang sudah ada
            'tgl_order' => 'nullable|date_format:d/m/Y H:i',
        ]);

        if (!$request->filled('paket_id') && (!isset($request->tests) || empty($request->tests))) {
            return back()->withErrors(['general' => 'Pilih setidaknya satu paket atau satu tes.'])->withInput();
        }

        DB::beginTransaction();
        try {
            // Temukan visit yang akan diupdate
            $visit = Visit::with(['visitTests'])->findOrFail($id);
            $tglOrder = $request->tgl_order;
            if ($tglOrder) {
                $tglOrder = Carbon::createFromFormat('d/m/Y H:i', $tglOrder);
            }
            // Update data utama visit
            $visit->fill($request->only([
                'pasien_id',
                'jenis_pasien',
                'dokter_id',
                'ruangan_id',
                'diagnosa',
                'jenis_order',
                'voucher_id',
                'metodebyr_id',
                'paket_id',
                'tgl_order'
            ]));
            $visit->tgl_order = $tglOrder;
            $visit->save();

            if ($request->filled('paket_id')) {
                // Jika ada paket, hapus semua test yang lama dan buat yang baru
                $visit->visitTests()->delete();
                $paket = Paket::with('paketItems.test')->findOrFail($request->paket_id);
                foreach ($paket->paketItems as $paketItem) {
                    $testModel = $paketItem->test;
                    if ($testModel) {
                        $harga = $request->jenis_pasien == 'BPJS' ? $testModel->harga_bpjs : $testModel->harga_umum;
                        $subtotal = $harga * $paketItem->jumlah;

                        $visit->visitTests()->create([
                            'test_id' => $testModel->id,
                            'harga' => $harga,
                            'jumlah' => $paketItem->jumlah,
                            'subtotal' => $subtotal,
                        ]);
                    }
                }
            } else {
                // Jika tidak ada paket, tangani test satu per satu
                $existingIds = collect($request->tests)->pluck('id')->filter()->toArray();

                // Hapus test yang tidak ada di request
                $visit->visitTests()->whereNotIn('id', $existingIds)->delete();

                foreach ($request->tests as $test) {
                    $testModel = Test::find($test['test_id']);
                    $harga = $request->jenis_pasien == 'BPJS' ? $testModel->harga_bpjs : $testModel->harga_umum;
                    $subtotal = $harga * $test['jumlah'];

                    if (isset($test['id'])) {
                        // Update test yang sudah ada
                        $visit->visitTests()->find($test['id'])->update([
                            'test_id' => $test['test_id'],
                            'harga' => $harga,
                            'jumlah' => $test['jumlah'],
                            'subtotal' => $subtotal
                        ]);
                    } else {
                        // Buat test baru
                        $visit->visitTests()->create([
                            'test_id' => $test['test_id'],
                            'harga' => $harga,
                            'jumlah' => $test['jumlah'],
                            'subtotal' => $subtotal
                        ]);
                    }
                }
            }

            // Hitung ulang total tagihan setelah perubahan
            $visit->calculateTotal();

            DB::commit();

            return redirect()->route('visits.show', $visit->id)
                ->with('success', 'Order berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage())->withInput();
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
                $visitDate = Carbon::parse($visit->tgl_order)->toDateString();
                $currentTime = Carbon::now()->toTimeString();
                $timestampToUse = $visitDate . ' ' . $currentTime;

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
                                'created_at' => $timestampToUse,
                                'updated_at' => $timestampToUse,
                            ]);
                        }
                    } else {
                        HasilLab::create([
                            'visit_test_id' => $visitTest->id,
                            'test_id' => $test->id,
                            'detail_test_id' => null,
                            'status' => 'Belum Valid',
                            'created_at' => $timestampToUse,
                            'updated_at' => $timestampToUse,
                        ]);
                    }
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
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test', 'paket'])
            ->where('status_order', 'Sampling')
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.sampling', compact('visits'));
    }
    public function barcodesampling()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test', 'visitTests.hasilLabs', 'paket'])
            ->whereIn('status_order', ['Sampling', 'Proses'])
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.barcodesampling', compact('visits'));
    }
    public function pemeriksaan()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test', 'paket', 'visitTests.jasmaniMcu'])
            ->where('status_order', 'Proses')
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.pemeriksaan', compact('visits'));
    }
    public function Hematologi()
    {
        $visits = $this->filterPemeriksaanByGrup('Hematologi');
        return view('visits.hematologi', compact('visits'));
    }
    public function KimiaKlinik()
    {
        $visits = $this->filterPemeriksaanByGrup('Kimia Klinik');
        return view('visits.kimiaklinik', compact('visits'));
    }
    public function imunologiSerologi()
    {
        $visits = $this->filterPemeriksaanByGrup('Imunologi - Serologi');
        return view('visits.imunologiserologi', compact('visits'));
    }
    public function Urinalisa()
    {
        $visits = $this->filterPemeriksaanByGrup('Urinalisa');
        return view('visits.urinalisa', compact('visits'));
    }
    public function khusus()
    {
        $visits = $this->filterPemeriksaanByGrup('Khusus');
        return view('visits.khusus', compact('visits'));
    }
    public function lainnya()
    {
        $visits = $this->filterPemeriksaanByGrup('Lainnya');
        return view('visits.lainnya', compact('visits'));
    }
    private function filterPemeriksaanByGrup($grupTest)
    {
        return Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test.grupTest', 'paket'])
            ->where('status_order', 'Proses')
            ->whereHas('visitTests.test.grupTest', function ($query) use ($grupTest) {
                $query->where('nama', $grupTest);
            })
            ->orderBy('tgl_order', 'desc')
            ->get();
    }
    public function Paket()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.test', 'paket', 'visitTests.jasmaniMcu'])
            ->where('status_order', 'Proses')
            ->whereNotNull('paket_id')
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.paket', compact('visits'));
    }
    public function validasi()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs', 'paket'])
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
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs', 'paket', 'visitTests.jasmaniMcu'])
            ->where('status_order', 'Selesai')
            ->orderBy('tgl_order', 'desc')
            ->get();
        return view('visits.cetak', compact('visits'));
    }
    public function Bayar()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests.hasilLabs', 'penerimaan', 'paket'])
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
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests', 'penerimaan.metodeBayar', 'penerimaan.user', 'paket'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('status', 'Terklaim');
            })
            ->orderBy('tgl_order', 'desc')
            ->get();

        return view('visits.laporan-pembayaran', compact('visits'));
    }

    public function cetakLabel($no_order)
    {
        $visit = Visit::with(['pasien'])
            ->where('no_order', $no_order)
            ->firstOrFail();
        $barcodeData = $visit->no_order;
        $barcode = DNS1D::getBarcodeHTML($barcodeData, 'C128', 1.5, 30);
        $data = [
            'visit' => $visit,
            'barcode' => $barcode,
            'tanggalCetak' => now()->format('d-m-Y H:i')
        ];
        $pdf = PDF::loadView('visits.label', $data)
            ->setPaper([0, 0, 200, 100]);
        return $pdf->stream('label_' . $visit->no_order . '.pdf');
    }
    public function cetakNota($no_order)
    {
        Config::set('terbilang.locale', 'id');
        $visit = Visit::with([
            'pasien',
            'dokter',
            'ruangan',
            'visitTests.test',
            'metodePembayaran',
            'penerimaan.user',
            'penerimaan.metodeBayar',
            'paket'
        ])
            ->where('no_order', $no_order)
            ->firstOrFail();
        $total_tagihan = $visit->total_tagihan;
        $subtotal = $visit->visitTests->sum('subtotal');
        $diskon = $visit->voucher ? $visit->voucher->nilai_diskon : 0;
        // $total = $total_tagihan - $diskon;
        $total = $total_tagihan;
        $barcodeData = "NOTA PEMBAYARAN KLINIK ZAFA MEDIKA\n";
        $barcodeData .= "No. Order: " . $visit->no_order . "\n";
        $barcodeData .= "Pasien: " . $visit->pasien->nama . "\n";
        $barcodeData .= "Total: Rp " . number_format($total, 0, ',', '.') . "\n";
        $barcodeData .= "Tgl. Cetak: " . now()->format('d-m-Y H:i');

        $barcode = DNS2D::getBarcodeHTML($barcodeData, 'QRCODE', 2, 2);
        $verifikator = 'Belum Divalidasi';
        $tanggalValidasi = 'Belum Divalidasi';
        if ($visit->penerimaan) {
            $verifikator = $visit->penerimaan->user->name ?? 'Kasir: ' . auth()->user()->name;
            $tanggalValidasi = $visit->penerimaan->created_at->format('d-m-Y');
        }
        $data = [
            'visit' => $visit,
            'subtotal' => $subtotal,
            'total_tagihan' => $total_tagihan,
            'diskon' => $diskon,
            'total' => $total,
            'terbilang' => ucwords((new Terbilang)->make($total)),
            'tanggalCetak' => now()->format('d-m-Y H:i'),
            'penerimaan' => $visit->penerimaan,
            'barcode' => $barcode,
            'tanggalValidasi' => $tanggalValidasi,
            'verifikator' => $verifikator,
            'petugasLab' => 'Petugas Laboratorium'
        ];
        $pdf = PDF::loadView('visits.nota', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream('nota_' . $visit->no_order . '.pdf');
    }
    public function cetakBarcode($no_order)
    {
        $visit = Visit::with(['pasien', 'visitTests.test'])
            ->where('no_order', $no_order)
            ->firstOrFail();
        $samples = $visit->visitTests->map(function ($vt) {
            return $vt->test->jenis_sampel;
        })->unique()->values()->all();
        $barcodeData = $visit->no_order;
        $barcode = DNS1D::getBarcodeHTML($barcodeData, 'C128', 1.5, 30);
        $data = [
            'visit' => $visit,
            'barcode' => $barcode,
            'samples' => $samples,
            'tanggalCetak' => now()->format('d-m-Y H:i')
        ];
        $pdf = PDF::loadView('visits.barcode', $data)
            ->setPaper([0, 0, 200, 100]);
        return $pdf->stream('label_' . $visit->no_order . '.pdf');
    }
    public function laporanTahunan(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $tests = Test::with(['grupTest' => function ($query) {
            $query->orderBy('nama', 'asc');
        }])
            ->get()
            ->sortBy(function ($test) {
                return $test->grupTest->nama;
            })
            ->groupBy('grupTest.nama');

        $laporan = [];
        foreach ($tests as $grup => $testGroup) {
            $laporan[] = [
                'is_separator' => true,
                'grup' => $grup
            ];

            foreach ($testGroup as $test) {
                $perBulan = [];
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    $jumlah = VisitTest::where('test_id', $test->id)
                        ->whereHas('visit', function ($q) use ($tahun, $bulan) {
                            $q->whereYear('tgl_order', $tahun)
                                ->whereMonth('tgl_order', $bulan);
                        })
                        ->count();
                    $perBulan[$bulan] = $jumlah;
                }
                $laporan[] = [
                    'is_separator' => false,
                    'grup' => $grup,
                    'nama' => $test->nama,
                    'per_bulan' => $perBulan
                ];
            }
        }
        return view('visits.laporan-tahunan', [
            'laporan' => $laporan,
            'tahun' => $tahun
        ]);
    }
    public function laporanKasirHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $transactions = Penerimaan::with(['visit.pasien', 'visit.ruangan', 'metodeBayar', 'user'])
            ->whereDate('created_at', $tanggal)
            ->where('status', 'Terklaim')
            ->orderBy('created_at', 'asc')
            ->get();
        $dataLaporan = [];
        $totalCash = 0;
        $totalQris = 0;
        $totalTransfer = 0;
        foreach ($transactions as $transaction) {
            $namaPasien = $transaction->visit->pasien->nama ?? 'Pasien Tidak Ditemukan';
            $jenisPasien = $transaction->visit->jenis_pasien ?? 'N/A';
            $metodeBayar = $transaction->metodeBayar->nama ?? 'Lainnya';
            $jumlahDibayar = $transaction->jumlah;
            $kasir = $transaction->user->name ?? 'Kasir Tidak Ditemukan';
            $namaRuangan = $transaction->visit->ruangan->nama ?? 'Lab. Zafa Medika';
            if ($jenisPasien == 'BPJS' && $jumlahDibayar == 0) {
                $dataLaporan[] = [
                    'nama' => $namaPasien . ' (' . $namaRuangan . ')',
                    'jumlah' => $jumlahDibayar,
                    'metode' => 'BPJS-K',
                    'kasir' => $kasir,
                ];
                continue;
            }
            $dataLaporan[] = [
                'nama' => $namaPasien . ' (' . $namaRuangan . ')',
                'jumlah' => $jumlahDibayar,
                'metode' => $metodeBayar,
                'kasir' => $kasir,
            ];
            if (stripos($metodeBayar, 'Cash') !== false) {
                $totalCash += $jumlahDibayar;
            } elseif (stripos($metodeBayar, 'QRIS') !== false) {
                $totalQris += $jumlahDibayar;
            } elseif (stripos($metodeBayar, 'Transfer') !== false) {
                $totalTransfer += $jumlahDibayar;
            }
        }
        $tanggalCetak = Carbon::now()->format('d-m-Y');
        $waktuCetak = Carbon::now()->format('H:i:s');
        $data = [
            'tanggal' => $tanggalCetak,
            'waktu' => $waktuCetak,
            'laporan' => $dataLaporan,
            'totalCash' => $totalCash,
            'totalQris' => $totalQris,
            'totalTransfer' => $totalTransfer,
            'kasirBertugas' => auth()->user()->name ?? 'Administrator',
        ];
        $pdf = Pdf::loadView('visits.laporan-kasir-harian', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream('laporan_kasir_harian_' . $tanggal . '.pdf');
    }
    public function exportLaporanPembayaranExcel()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests', 'penerimaan.metodeBayar', 'penerimaan.user'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('status', 'Terklaim');
            })
            ->orderBy('tgl_order', 'desc')
            ->get();
        return Excel::download(new LaporanPembayaranExport($visits), 'laporan_pembayaran.xlsx');
    }
    public function exportLaporanPembayaranPdf()
    {
        $visits = Visit::with(['pasien', 'dokter', 'ruangan', 'visitTests', 'penerimaan.metodeBayar', 'penerimaan.user'])
            ->whereHas('penerimaan', function ($query) {
                $query->where('status', 'Terklaim');
            })
            ->orderBy('tgl_order', 'desc')
            ->get();
        $pdf = Pdf::loadView('visits.laporan-pembayaran-pdf', compact('visits'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream('laporan_pembayaran.pdf');
    }
    public function exportLaporanTahunanExcel(Request $request)
    {
        $tahun = $request->input('tahun', now()->year);
        $tests = Test::with(['grupTest' => function ($query) {
            $query->orderBy('nama', 'asc');
        }])
            ->get()
            ->sortBy(function ($test) {
                return $test->grupTest->nama;
            })
            ->groupBy('grupTest.nama');
        $laporan = [];
        foreach ($tests as $grup => $testGroup) {
            $laporan[] = [
                'is_separator' => true,
                'grup' => $grup
            ];
            $sortedTestGroup = $testGroup->sortBy('nama');

            foreach ($sortedTestGroup as $test) {
                $perBulan = [];
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    $jumlah = VisitTest::where('test_id', $test->id)
                        ->whereHas('visit', function ($q) use ($tahun, $bulan) {
                            $q->whereYear('tgl_order', $tahun)
                                ->whereMonth('tgl_order', $bulan);
                        })
                        ->count();
                    $perBulan[$bulan] = $jumlah;
                }

                $laporan[] = [
                    'is_separator' => false,
                    'grup' => $grup,
                    'nama' => $test->nama,
                    'per_bulan' => $perBulan
                ];
            }
        }
        return Excel::download(new LaporanTahunanExport($laporan, $tahun), 'laporan_tahunan_' . $tahun . '.xlsx');
    }
    public function getPaketTests(Paket $paket)
    {
        $paket->load('paketItems.test');
        $testsData = [];
        foreach ($paket->paketItems as $paketItem) {
            $test = $paketItem->test;
            if ($test) {
                $testsData[] = [
                    'id' => $test->id,
                    'kode' => $test->kode,
                    'nama' => $test->nama,
                    'harga_umum' => $test->harga_umum,
                    'harga_bpjs' => $test->harga_bpjs,
                    'grup_test' => $test->grup_test ?? '',
                    'sub_grup' => $test->sub_grup ?? '',
                    'jumlah_paket' => $paketItem->jumlah,
                ];
            }
        }
        return response()->json($testsData);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\VisitTest;
use App\Models\JasmaniMcu;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class JasmaniMcuController extends Controller
{
    public function index()
    {
        $jasmaniMcus = JasmaniMcu::with(['visitTest.visit.pasien', 'dokterPemeriksa'])
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->get();
        return view('jasmani_mcu.index', compact('jasmaniMcus'));
    }

    public function create(VisitTest $visitTest)
    {
        $mcuTest = Test::whereRaw('LOWER(nama) LIKE ?', ['%jasmani%'])->first();
        if (!$mcuTest || $visitTest->test_id !== $mcuTest->id) {
            return redirect()->back()->with('error', 'Visit Test ini bukan untuk Pemeriksaan Jasmani MCU.');
        }
        $pasien = $visitTest->visit->pasien;
        $dokters = Dokter::where('status', 'Aktif')->get();
        return view('jasmani_mcu.form', compact('visitTest', 'pasien', 'dokters'));
    }

    public function store(Request $request, VisitTest $visitTest)
    {
        $request->validate([
            'keluhan_saat_ini' => 'nullable|string',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'bmi' => 'nullable|string',
            'hidung' => 'nullable|string',
            'mata_tanpa_kacamata_kiri' => 'nullable|string',
            'mata_tanpa_kacamata_kanan' => 'nullable|string',
            'mata_dengan_kacamata_kiri' => 'nullable|string',
            'mata_dengan_kacamata_kanan' => 'nullable|string',
            'buta_warna' => 'nullable|string',
            'lapang_pandang' => 'nullable|string',
            'liang_telinga_kiri' => 'nullable|string',
            'liang_telinga_kanan' => 'nullable|string',
            'gendang_telinga_kiri' => 'nullable|string',
            'gendang_telinga_kanan' => 'nullable|string',
            'ritme_pernapasan' => 'nullable|string',
            'pergerakan_dada' => 'nullable|string',
            'suara_pernapasan' => 'nullable|string',
            'tekanan_darah' => 'nullable|string',
            'frekuensi_jantung' => 'nullable|string',
            'bunyi_jantung' => 'nullable|string',
            'gigi' => 'nullable|string',
            'peristaltik' => 'nullable|string',
            'abdominal_mass' => 'nullable|string',
            'bekas_operasi' => 'nullable|string',
            'kesimpulan_medis' => 'nullable|string',
            'temuan' => 'nullable|string',
            'rekomendasi_dokter' => 'nullable|string',
            'dokter_pemeriksa_id' => 'nullable|exists:dokters,id',
            'tanggal_pemeriksaan' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $jasmaniMcu = JasmaniMcu::create([
                'visit_test_id' => $visitTest->id,
                'pasien_id' => $visitTest->visit->pasien_id,
                'dokter_pemeriksa_id' => $request->dokter_pemeriksa_id,
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan ?? now(),
                'keluhan_saat_ini' => $request->keluhan_saat_ini,
                'berat_badan' => $request->berat_badan,
                'tinggi_badan' => $request->tinggi_badan,
                'bmi' => $request->bmi,
                'hidung' => $request->hidung,
                'mata_tanpa_kacamata_kiri' => $request->mata_tanpa_kacamata_kiri,
                'mata_tanpa_kacamata_kanan' => $request->mata_tanpa_kacamata_kanan,
                'mata_dengan_kacamata_kiri' => $request->mata_dengan_kacamata_kiri,
                'mata_dengan_kacamata_kanan' => $request->mata_dengan_kacamata_kanan,
                'buta_warna' => $request->buta_warna,
                'lapang_pandang' => $request->lapang_pandang,
                'liang_telinga_kiri' => $request->liang_telinga_kiri,
                'liang_telinga_kanan' => $request->liang_telinga_kanan,
                'gendang_telinga_kiri' => $request->gendang_telinga_kiri,
                'gendang_telinga_kanan' => $request->gendang_telinga_kanan,
                'ritme_pernapasan' => $request->ritme_pernapasan,
                'pergerakan_dada' => $request->pergerakan_dada,
                'suara_pernapasan' => $request->suara_pernapasan,
                'tekanan_darah' => $request->tekanan_darah,
                'frekuensi_jantung' => $request->frekuensi_jantung,
                'bunyi_jantung' => $request->bunyi_jantung,
                'gigi' => $request->gigi,
                'peristaltik' => $request->peristaltik,
                'abdominal_mass' => $request->abdominal_mass,
                'bekas_operasi' => $request->bekas_operasi,
                'kesimpulan_medis' => $request->kesimpulan_medis,
                'temuan' => $request->temuan,
                'rekomendasi_dokter' => $request->rekomendasi_dokter,
            ]);

            DB::commit();
            return redirect()->route('jasmani-mcu.show', $jasmaniMcu->id)
                ->with('success', 'Hasil Pemeriksaan Jasmani MCU berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan hasil Pemeriksaan Jasmani MCU: ' . $e->getMessage())->withInput();
        }
    }

    public function show(JasmaniMcu $jasmaniMcu)
    {
        $jasmaniMcu->load(['visitTest.visit.pasien', 'dokterPemeriksa']);
        return view('jasmani_mcu.show', compact('jasmaniMcu'));
    }

    public function edit(JasmaniMcu $jasmaniMcu)
    {
        $jasmaniMcu->load(['visitTest.visit.pasien']);
        $pasien = $jasmaniMcu->pasien;
        $visitTest = $jasmaniMcu->visitTest;
        $dokters = Dokter::where('status', 'Aktif')->get();

        return view('jasmani_mcu.form', compact('jasmaniMcu', 'visitTest', 'pasien', 'dokters'));
    }

    public function update(Request $request, JasmaniMcu $jasmaniMcu)
    {
        $request->validate([
            'keluhan_saat_ini' => 'nullable|string',
            'berat_badan' => 'nullable|numeric|min:0',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'bmi' => 'nullable|string',
            'hidung' => 'nullable|string',
            'mata_tanpa_kacamata_kiri' => 'nullable|string',
            'mata_tanpa_kacamata_kanan' => 'nullable|string',
            'mata_dengan_kacamata_kiri' => 'nullable|string',
            'mata_dengan_kacamata_kanan' => 'nullable|string',
            'buta_warna' => 'nullable|string',
            'lapang_pandang' => 'nullable|string',
            'liang_telinga_kiri' => 'nullable|string',
            'liang_telinga_kanan' => 'nullable|string',
            'gendang_telinga_kiri' => 'nullable|string',
            'gendang_telinga_kanan' => 'nullable|string',
            'ritme_pernapasan' => 'nullable|string',
            'pergerakan_dada' => 'nullable|string',
            'suara_pernapasan' => 'nullable|string',
            'tekanan_darah' => 'nullable|string',
            'frekuensi_jantung' => 'nullable|string',
            'bunyi_jantung' => 'nullable|string',
            'gigi' => 'nullable|string',
            'peristaltik' => 'nullable|string',
            'abdominal_mass' => 'nullable|string',
            'bekas_operasi' => 'nullable|string',
            'kesimpulan_medis' => 'nullable|string',
            'temuan' => 'nullable|string',
            'rekomendasi_dokter' => 'nullable|string',
            'dokter_pemeriksa_id' => 'nullable|exists:dokters,id',
            'tanggal_pemeriksaan' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $jasmaniMcu->update([
                'dokter_pemeriksa_id' => $request->dokter_pemeriksa_id,
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'keluhan_saat_ini' => $request->keluhan_saat_ini,
                'berat_badan' => $request->berat_badan,
                'tinggi_badan' => $request->tinggi_badan,
                'bmi' => $request->bmi,
                'hidung' => $request->hidung,
                'mata_tanpa_kacamata_kiri' => $request->mata_tanpa_kacamata_kiri,
                'mata_tanpa_kacamata_kanan' => $request->mata_tanpa_kacamata_kanan,
                'mata_dengan_kacamata_kiri' => $request->mata_dengan_kacamata_kiri,
                'mata_dengan_kacamata_kanan' => $request->mata_dengan_kacamata_kanan,
                'buta_warna' => $request->buta_warna,
                'lapang_pandang' => $request->lapang_pandang,
                'liang_telinga_kiri' => $request->liang_telinga_kiri,
                'liang_telinga_kanan' => $request->liang_telinga_kanan,
                'gendang_telinga_kiri' => $request->gendang_telinga_kiri,
                'gendang_telinga_kanan' => $request->gendang_telinga_kanan,
                'ritme_pernapasan' => $request->ritme_pernapasan,
                'pergerakan_dada' => $request->pergerakan_dada,
                'suara_pernapasan' => $request->suara_pernapasan,
                'tekanan_darah' => $request->tekanan_darah,
                'frekuensi_jantung' => $request->frekuensi_jantung,
                'bunyi_jantung' => $request->bunyi_jantung,
                'gigi' => $request->gigi,
                'peristaltik' => $request->peristaltik,
                'abdominal_mass' => $request->abdominal_mass,
                'bekas_operasi' => $request->bekas_operasi,
                'kesimpulan_medis' => $request->kesimpulan_medis,
                'temuan' => $request->temuan,
                'rekomendasi_dokter' => $request->rekomendasi_dokter,
            ]);

            DB::commit();
            return redirect()->route('jasmani-mcu.show', $jasmaniMcu->id)
                ->with('success', 'Hasil Pemeriksaan Jasmani MCU berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui hasil Pemeriksaan Jasmani MCU: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(JasmaniMcu $jasmaniMcu)
    {
        DB::beginTransaction();
        try {
            $visitId = $jasmaniMcu->visitTest->visit_id;
            $jasmaniMcu->delete();
            DB::commit();
            return redirect()->route('visits.show', $visitId)
                ->with('success', 'Hasil Pemeriksaan Jasmani MCU berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus hasil Pemeriksaan Jasmani MCU: ' . $e->getMessage());
        }
    }
    public function printMcu(JasmaniMcu $jasmaniMcu)
    {
        $jasmaniMcu->load(['visitTest.visit.pasien', 'dokterPemeriksa']);
        $pasien = $jasmaniMcu->pasien;
        $barcodeData = "Hasil Pemeriksaan Jasmani MCU Klinik Zafa Medika\n";
        $barcodeData .= "No. Order: " . $jasmaniMcu->visitTest->visit->no_order . "\n";
        $barcodeData .= "Pasien: " . $pasien->nama . "\n";
        $barcodeData .= "Tgl. Pemeriksaan: " . $jasmaniMcu->tanggal_pemeriksaan->format('d-m-Y H:i');
        $barcode = DNS2D::getBarcodeHTML($barcodeData, 'QRCODE', 2, 2);
        $tanggalCetak = now()->format('d-m-Y H:i');
        $data = [
            'jasmaniMcu' => $jasmaniMcu,
            'pasien' => $pasien,
            'barcode' => $barcode,
            'tanggalCetak' => $tanggalCetak,
        ];
        $pdf = Pdf::loadView('jasmani_mcu.print', $data)
            ->setPaper('a4', 'portrait');
        return $pdf->stream('hasil_jasmani_mcu_' . $pasien->norm . '.pdf');
    }
}

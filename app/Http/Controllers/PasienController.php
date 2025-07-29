<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::orderBy('norm')->get();
        return view('pasiens.index', compact('pasiens'));
    }

    public function create()
    {
        return view('pasiens.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki - Laki,Perempuan',
            'status_pasien' => 'nullable|in:APS / UMUM,Asuransi,BPJS,Lupis,Medical Check Up,Prolanis,Rujukan Faskes,Rujukan Dokter,Lainnya',
            'nik' => 'required|digits:16|unique:pasiens',
            'no_bpjs' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'required|max:15',
            'alamat' => 'required'
        ]);

        // Norm akan otomatis dibuat oleh model
        Pasien::create($validated);

        return redirect()->route('pasiens.index')
            ->with('success', 'Pasien berhasil didaftarkan');
    }

    public function edit(Pasien $pasien)
    {
        return view('pasiens.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki - Laki,Perempuan',
            'status_pasien' => 'nullable|in:APS / UMUM,Asuransi,BPJS,Lupis,Medical Check Up,Prolanis,Rujukan Faskes,Rujukan Dokter,Lainnya',
            'nik' => 'required|digits:16|unique:pasiens,nik,' . $pasien->id,
            'no_bpjs' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'required|max:15',
            'alamat' => 'required'
        ]);

        $pasien->update($validated);

        return redirect()->route('pasiens.index')
            ->with('success', 'Data pasien berhasil diperbarui');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        return redirect()->route('pasiens.index')
            ->with('success', 'Pasien berhasil dihapus');
    }
    public function cetakKartuByNorm($norm)
    {
        $pasien = Pasien::where('norm', $norm)->firstOrFail();
        $barcodeData = "Identitas Pasien Laboratorium Klinik Zafa Medika - ";
        $barcodeData .= $pasien->norm . " - ";
        $barcodeData .= "Nama: " . $pasien->nama . " - ";
        $barcodeData .= "Tgl Lahir: " . \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') . " ";
        $barcodeData .= "Teregistrasi: " . $pasien->created_at->format('d-m-Y');
        $barcode = DNS2D::getBarcodeHTML($barcodeData, 'QRCODE', 2, 2);
        $pdf = PDF::loadView('pasiens.kartu', compact('pasien', 'barcode'))
            ->setPaper([0, 0, 255.1, 153.1]);
        return $pdf->stream('kartu_pasien_' . $pasien->norm . '.pdf');
    }
    public function cetakLabelIdentitas($norm)
    {
        $pasien = Pasien::where('norm', $norm)->firstOrFail();
        $tglLahir = \Carbon\Carbon::parse($pasien->tgl_lahir);
        $umur = $tglLahir->diffInYears(\Carbon\Carbon::now());
        $barcodeData = "LAB ZAFA MEDIKA - ";
        $barcodeData .= strtoupper($pasien->nama) . " (" . substr($pasien->jenis_kelamin, 0, 1) . ") - ";
        $barcodeData .= $tglLahir->format('d-m-Y') . "/" . $umur . "Thn - ";
        $barcodeData .= "RM" . $pasien->norm . " - ";
        $statusPasienText = $pasien->status_pasien ? explode(' / ', $pasien->status_pasien)[0] : 'N/A';
        $barcodeData .= "Status:" . $statusPasienText . " - ";
        $barcodeData .= "Tgl Register:" . $pasien->created_at->format('Y-m-d');
        $barcode = DNS2D::getBarcodeHTML($barcodeData, 'QRCODE', 1.5, 1.5);
        $data = [
            'pasien' => $pasien,
            'barcode' => $barcode,
            'umur' => $umur,
            'tglLahirFormatted' => $tglLahir->format('d-m-Y'),
            'tglRegisterFormatted' => $pasien->created_at->format('Y-m-d')
        ];
        $pdf = PDF::loadView('pasiens.label', $data)
            ->setPaper([0, 0, 200, 100]);
        return $pdf->stream('label_' . $pasien->norm . '.pdf');
    }
}

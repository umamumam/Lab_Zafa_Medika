<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

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
            'status_pasien' => 'required|in:APS / UMUM,Asuransi,BPJS,Lupis,Medical Check Up,Prolanis,Rujukan Faskes,Rujukan Dokter,Lainnya',
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
            'status_pasien' => 'required|in:APS / UMUM,Asuransi,BPJS,Lupis,Medical Check Up,Prolanis,Rujukan Faskes,Rujukan Dokter,Lainnya',
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
}

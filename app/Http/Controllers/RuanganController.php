<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Dokter;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::with('dokter')->orderBy('nama')->get();
        $dokters = Dokter::where('status', 'Aktif')->orderBy('nama')->get();
        return view('ruangans.index', compact('ruangans', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'dokter_id' => 'required|exists:dokters,id',
            'kontak' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        // Kode akan otomatis digenerate oleh model
        Ruangan::create([
            'nama' => $request->nama,
            'dokter_id' => $request->dokter_id,
            'kontak' => $request->kontak,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('ruangans.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return response()->json([
            'ruangan' => $ruangan,
            'dokters' => Dokter::where('status', 'Aktif')->orderBy('nama')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'dokter_id' => 'required|exists:dokters,id',
            'kontak' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->update([
            'nama' => $request->nama,
            'dokter_id' => $request->dokter_id,
            'kontak' => $request->kontak,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('ruangans.index')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();
        return redirect()->route('ruangans.index')->with('success', 'Ruangan berhasil dihapus');
    }
}

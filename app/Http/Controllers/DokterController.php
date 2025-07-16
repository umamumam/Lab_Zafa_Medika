<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::orderBy('nama')->get();
        return view('dokters.index', compact('dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'spesialis' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'jenis_kelamin' => 'required|in:L,P',
        ]);
        Dokter::create([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit($id)
    {
        $dokter = Dokter::findOrFail($id);
        return response()->json($dokter);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'spesialis' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $dokter = Dokter::findOrFail($id);
        $dokter->update([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => $request->status,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil diperbarui');
    }

    public function destroy($id)
    {
        Dokter::findOrFail($id)->delete();
        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil dihapus');
    }
}

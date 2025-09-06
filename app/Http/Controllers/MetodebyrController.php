<?php

namespace App\Http\Controllers;

use App\Models\Metodebyr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MetodebyrController extends Controller
{
    public function index()
    {
        $metodebyrs = Metodebyr::orderBy('nama')->get();
        return view('metodebyrs.index', compact('metodebyrs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:25',
        ]);
        Metodebyr::create([
            'nama' => $request->nama,
        ]);
        return redirect()->route('metodebyrs.index')->with('success', 'Metode Pembayaran berhasil ditambahkan.');
    }

    public function edit($id): JsonResponse
    {
        $metodebyr = Metodebyr::findOrFail($id);
        return response()->json($metodebyr);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:25',
        ]);
        $metodebyr = Metodebyr::findOrFail($id);
        $metodebyr->update([
            'nama' => $request->nama,
        ]);
        return redirect()->route('metodebyrs.index')->with('success', 'Metode Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Metodebyr::findOrFail($id)->delete();
        return redirect()->route('metodebyrs.index')->with('success', 'Metode Pembayaran berhasil dihapus.');
    }
}

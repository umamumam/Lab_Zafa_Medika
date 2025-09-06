<?php

namespace App\Http\Controllers;

use App\Models\GrupTest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class GrupTestController extends Controller
{
    public function index()
    {
        $grupTests = GrupTest::orderBy('nama')->get();
        return view('grup_tests.index', compact('grupTests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:grup_tests,nama',
        ]);

        GrupTest::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('grup_tests.index')->with('success', 'Grup Test berhasil ditambahkan.');
    }

    public function edit($id): JsonResponse
    {
        $grupTest = GrupTest::findOrFail($id);
        return response()->json($grupTest);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('grup_tests', 'nama')->ignore($id),
            ],
        ]);

        $grupTest = GrupTest::findOrFail($id);
        $grupTest->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('grup_tests.index')->with('success', 'Grup Test berhasil diperbarui.');
    }

    public function destroy($id)
    {
        GrupTest::findOrFail($id)->delete();

        return redirect()->route('grup_tests.index')->with('success', 'Grup Test berhasil dihapus.');
    }
}

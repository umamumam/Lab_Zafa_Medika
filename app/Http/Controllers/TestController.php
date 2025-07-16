<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::orderBy('nama')->get();
        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        return view('tests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:3',
            'nama' => 'required|string|max:255',
            'metode' => 'required|string|max:255',
            'nilai_normal' => 'required|string|max:255',
            'type' => ['required', Rule::in(['Single', 'Range'])],
            'min' => [
                'nullable',
                'numeric',
                'decimal:0,2',
                Rule::requiredIf($request->type === 'Range')
            ],
            'max' => [
                'nullable',
                'numeric',
                'decimal:0,2',
                Rule::requiredIf($request->type === 'Range'),
                'gt:min'
            ],
            'satuan' => 'required|string|max:50',
            'harga_umum' => 'required|integer|min:0',
            'harga_bpjs' => 'required|integer|min:0',
            'grup_test' => ['required', Rule::in([
                'Hematologi',
                'Kimia Klinik',
                'Imunologi / Serologi',
                'Mikrobiologi',
                'Khusus',
                'Lainnya'
            ])],
            'sub_grup' => ['required', Rule::in([
                'Cairan dan Parasitologi (E1)',
                'Elektrometri (D1)',
                'Endokrin Metabolik (B1)',
                'Faal Ginjal (B3)',
                'Faal Hati (B2)',
                'Faal Hemotsasis (A2)',
                'Faal Tiroid (B5)',
                'Hematologi (A1)',
                'Imunologi / Serologi (B4)',
                'Marker Infeksi / Inflamasi (C1)',
                'Marker Jantung (C2)',
                'Lain - Lain (D2)'
            ])],
            'jenis_sampel' => ['required', Rule::in([
                'Whole Blood EDTA',
                'Whole Blood Heparin',
                'Serum',
                'Plasma Citrat',
                'Urin',
                'Feaces',
                'Sputum',
                'Cairan',
                'LCS',
                'Preparat',
                'Swab'
            ])],
            'interpretasi' => 'nullable|string',
            'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
        ]);

        Test::create($validated);

        return redirect()->route('tests.index')->with('success', 'Test berhasil ditambahkan');
    }

    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:3',
            'nama' => 'required|string|max:255',
            'metode' => 'required|string|max:255',
            'nilai_normal' => 'required|string|max:255',
            'type' => ['required', Rule::in(['Single', 'Range'])],
            'min' => [
                'nullable',
                'numeric',
                'decimal:0,2',
                Rule::requiredIf($request->type === 'Range')
            ],
            'max' => [
                'nullable',
                'numeric',
                'decimal:0,2',
                Rule::requiredIf($request->type === 'Range'),
                'gt:min'
            ],
            'satuan' => 'required|string|max:50',
            'harga_umum' => 'required|integer|min:0',
            'harga_bpjs' => 'required|integer|min:0',
            'grup_test' => ['required', Rule::in([
                'Hematologi',
                'Kimia Klinik',
                'Imunologi / Serologi',
                'Mikrobiologi',
                'Khusus',
                'Lainnya'
            ])],
            'sub_grup' => ['required', Rule::in([
                'Cairan dan Parasitologi (E1)',
                'Elektrometri (D1)',
                'Endokrin Metabolik (B1)',
                'Faal Ginjal (B3)',
                'Faal Hati (B2)',
                'Faal Hemotsasis (A2)',
                'Faal Tiroid (B5)',
                'Hematologi (A1)',
                'Imunologi / Serologi (B4)',
                'Marker Infeksi / Inflamasi (C1)',
                'Marker Jantung (C2)',
                'Lain - Lain (D2)'
            ])],
            'jenis_sampel' => ['required', Rule::in([
                'Whole Blood EDTA',
                'Whole Blood Heparin',
                'Serum',
                'Plasma Citrat',
                'Urin',
                'Feaces',
                'Sputum',
                'Cairan',
                'LCS',
                'Preparat',
                'Swab'
            ])],
            'interpretasi' => 'nullable|string',
            'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
        ]);

        $test->update($validated);

        return redirect()->route('tests.index')->with('success', 'Test berhasil diperbarui');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.index')->with('success', 'Test berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\NilaiNormal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $validatedTestData = $request->validate([
                'kode' => 'required|string|max:50',
                'nama' => 'required|string|max:255',
                'metode' => 'required|string|max:255',
                'nilai_normal' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_umum' => 'required|integer|min:0',
                'harga_bpjs' => 'required|integer|min:0',
                'grup_test' => ['required', Rule::in(['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya'])],
                'sub_grup' => ['required', Rule::in(['Cairan dan Parasitologi (E1)', 'Elektrometri (D1)', 'Endokrin Metabolik (B1)', 'Faal Ginjal (B3)', 'Faal Hati (B2)', 'Faal Hemotsasis (A2)', 'Faal Tiroid (B5)', 'Hematologi (A1)', 'Imunologi / Serologi (B4)', 'Marker Infeksi / Inflamasi (C1)', 'Marker Jantung (C2)', 'Lain - Lain (D2)'])],
                'jenis_sampel' => ['required', Rule::in(['Whole Blood EDTA', 'Whole Blood Heparin', 'Serum', 'Plasma Citrat', 'Urin', 'Feaces', 'Sputum', 'Cairan', 'LCS', 'Preparat', 'Swab'])],
                'interpretasi' => 'nullable|string',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
            ]);

            $test = Test::create($validatedTestData);

            $request->validate([
                'nilai_normals_data' => 'nullable|array',
                'nilai_normals_data.*.jenis_kelamin' => ['required_with:nilai_normals_data', Rule::in(['Laki - Laki', 'Perempuan', 'Umum'])],
                'nilai_normals_data.*.usia_min' => 'nullable|integer|min:0',
                'nilai_normals_data.*.usia_max' => 'nullable|integer|min:0|gte:nilai_normals_data.*.usia_min',
                'nilai_normals_data.*.type' => ['required_with:nilai_normals_data', Rule::in(['Single', 'Range'])],
                'nilai_normals_data.*.min' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range'],
                'nilai_normals_data.*.max' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range', 'gt:nilai_normals_data.*.min'],
            ]);

            if ($request->has('nilai_normals_data')) {
                foreach ($request->nilai_normals_data as $normalData) {
                    $test->normalValues()->create([
                        'jenis_kelamin' => $normalData['jenis_kelamin'],
                        'usia_min' => $normalData['usia_min'],
                        'usia_max' => $normalData['usia_max'],
                        'type' => $normalData['type'],
                        'min' => $normalData['min'],
                        'max' => $normalData['max'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('tests.index')->with('success', 'Test berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan test: ' . $e->getMessage()]);
        }
    }

    public function edit(Test $test)
    {
        $test->load('normalValues');
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        DB::beginTransaction();
        try {
            $validatedTestData = $request->validate([
                'kode' => 'required|string|max:50',
                'nama' => 'required|string|max:255',
                'metode' => 'required|string|max:255',
                'nilai_normal' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'harga_umum' => 'required|integer|min:0',
                'harga_bpjs' => 'required|integer|min:0',
                'grup_test' => ['required', Rule::in(['Hematologi', 'Kimia Klinik', 'Imunologi / Serologi', 'Mikrobiologi', 'Khusus', 'Lainnya'])],
                'sub_grup' => ['required', Rule::in(['Cairan dan Parasitologi (E1)', 'Elektrometri (D1)', 'Endokrin Metabolik (B1)', 'Faal Ginjal (B3)', 'Faal Hati (B2)', 'Faal Hemotsasis (A2)', 'Faal Tiroid (B5)', 'Hematologi (A1)', 'Imunologi / Serologi (B4)', 'Marker Infeksi / Inflamasi (C1)', 'Marker Jantung (C2)', 'Lain - Lain (D2)'])],
                'jenis_sampel' => ['required', Rule::in(['Whole Blood EDTA', 'Whole Blood Heparin', 'Serum', 'Plasma Citrat', 'Urin', 'Feaces', 'Sputum', 'Cairan', 'LCS', 'Preparat', 'Swab'])],
                'interpretasi' => 'nullable|string',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
            ]);

            $test->update($validatedTestData);

            $request->validate([
                'nilai_normals_data' => 'nullable|array',
                'nilai_normals_data.*.jenis_kelamin' => ['required_with:nilai_normals_data', Rule::in(['Laki - Laki', 'Perempuan', 'Umum'])],
                'nilai_normals_data.*.usia_min' => 'nullable|integer|min:0',
                'nilai_normals_data.*.usia_max' => 'nullable|integer|min:0|gte:nilai_normals_data.*.usia_min',
                'nilai_normals_data.*.type' => ['required_with:nilai_normals_data', Rule::in(['Single', 'Range'])],
                'nilai_normals_data.*.min' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range'],
                'nilai_normals_data.*.max' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range', 'gt:nilai_normals_data.*.min'],
            ]);

            $test->normalValues()->delete();
            if ($request->has('nilai_normals_data')) {
                foreach ($request->nilai_normals_data as $normalData) {
                    $test->normalValues()->create([
                        'jenis_kelamin' => $normalData['jenis_kelamin'],
                        'usia_min' => $normalData['usia_min'],
                        'usia_max' => $normalData['usia_max'],
                        'type' => $normalData['type'],
                        'min' => $normalData['min'],
                        'max' => $normalData['max'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('tests.index')->with('success', 'Test berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui test: ' . $e->getMessage()]);
        }
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.index')->with('success', 'Test berhasil dihapus');
    }
}

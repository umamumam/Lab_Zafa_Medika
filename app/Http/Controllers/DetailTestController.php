<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\DetailTest;
use App\Models\NilaiNormal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DetailTestController extends Controller
{
    public function index()
    {
        // Eager load 'test' and 'normalValues' relationships
        $detailTests = DetailTest::with('test', 'normalValues')
                                 ->orderBy('test_id')
                                 ->orderBy('urutan')
                                 ->get();

        return view('detail_tests.index', compact('detailTests'));
    }

    public function create()
    {
        $tests = Test::orderBy('nama')->get();
        return view('detail_tests.create', compact('tests'));
    }

    public function store(Request $request)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {
            $validatedDetailTestData = $request->validate([
                'test_id' => 'required|exists:tests,id',
                'urutan' => 'required|integer|min:0',
                'nama' => 'required|string|max:255',
                'nilai_normal' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
            ]);

            $detailTest = DetailTest::create($validatedDetailTestData);

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
                    $detailTest->normalValues()->create([
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
            return redirect()->route('detail_tests.index')
                ->with('success', 'Detail test berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan detail test: ' . $e->getMessage()]);
        }
    }

    public function edit(DetailTest $detailTest)
    {
        $detailTest->load('test');
        $detailTest->nilai_normals_data = $detailTest->normalValues;
        $tests = Test::orderBy('nama')->get();
        return view('detail_tests.edit', compact('detailTest', 'tests'));
    }

    public function update(Request $request, DetailTest $detailTest)
    {
        DB::beginTransaction();
        try {
            $validatedDetailTestData = $request->validate([
                'test_id' => 'required|exists:tests,id',
                'urutan' => 'required|integer|min:0',
                'nama' => 'required|string|max:255',
                'nilai_normal' => 'required|string|max:255',
                'satuan' => 'required|string|max:50',
                'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
            ]);
            $detailTest->update($validatedDetailTestData);
            $request->validate([
                'nilai_normals_data' => 'nullable|array',
                'nilai_normals_data.*.jenis_kelamin' => ['required_with:nilai_normals_data', Rule::in(['Laki - Laki', 'Perempuan', 'Umum'])],
                'nilai_normals_data.*.usia_min' => 'nullable|integer|min:0',
                'nilai_normals_data.*.usia_max' => 'nullable|integer|min:0|gte:nilai_normals_data.*.usia_min',
                'nilai_normals_data.*.type' => ['required_with:nilai_normals_data', Rule::in(['Single', 'Range'])],
                'nilai_normals_data.*.min' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range'],
                'nilai_normals_data.*.max' => ['nullable', 'numeric', 'decimal:0,2', 'required_if:nilai_normals_data.*.type,Range', 'gt:nilai_normals_data.*.min'],
            ]);

            $detailTest->normalValues()->delete();
            if ($request->has('nilai_normals_data')) {
                foreach ($request->nilai_normals_data as $normalData) {
                    $detailTest->normalValues()->create([
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
            return redirect()->route('detail_tests.index')
                ->with('success', 'Detail test berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui detail test: ' . $e->getMessage()]);
        }
    }

    public function destroy(DetailTest $detailTest)
    {
        $detailTest->delete();
        return redirect()->route('detail_tests.index')
            ->with('success', 'Detail test berhasil dihapus');
    }
}

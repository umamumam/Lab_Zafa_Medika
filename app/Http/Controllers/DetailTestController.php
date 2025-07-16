<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\DetailTest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DetailTestController extends Controller
{
    public function index()
    {
        $detailTests = DetailTest::with('test')->orderBy('test_id')->orderBy('urutan')->get();
        return view('detail_tests.index', compact('detailTests'));
    }

    public function create()
    {
        $tests = Test::orderBy('nama')->get();
        return view('detail_tests.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'test_id' => 'required|exists:tests,id',
            'urutan' => 'required|integer|min:0',
            'nama' => 'required|string|max:255',
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
            'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
        ], [
            'max.gt' => 'Nilai maksimum harus lebih besar dari nilai minimum.'
        ]);

        DetailTest::create($validated);

        return redirect()->route('detail_tests.index')
            ->with('success', 'Detail test berhasil ditambahkan');
    }

    public function edit(DetailTest $detailTest)
    {
        $tests = Test::orderBy('nama')->get();
        return view('detail_tests.edit', compact('detailTest', 'tests'));
    }

    public function update(Request $request, DetailTest $detailTest)
    {
        $validated = $request->validate([
            'test_id' => 'required|exists:tests,id',
            'urutan' => 'required|integer|min:0',
            'nama' => 'required|string|max:255',
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
            'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])]
        ], [
            'max.gt' => 'Nilai maksimum harus lebih besar dari nilai minimum.'
        ]);

        $detailTest->update($validated);

        return redirect()->route('detail_tests.index')
            ->with('success', 'Detail test berhasil diperbarui');
    }

    public function destroy(DetailTest $detailTest)
    {
        $detailTest->delete();
        return redirect()->route('detail_tests.index')
            ->with('success', 'Detail test berhasil dihapus');
    }
}

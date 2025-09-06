<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidationRules());

        Voucher::create($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dibuat.');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate($this->getValidationRules($voucher->id));

        $voucher->update($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    protected function getValidationRules(?int $id = null)
    {
        return [
            'kode' => ['required', 'string', 'max:5', Rule::unique('vouchers')->ignore($id)],
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:persen,nominal',
            'value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when(request('tipe') === 'persen', ['between:0,100']),
                Rule::when(request('tipe') === 'nominal', ['min:1']),
            ],
            'status' => 'required|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string'
        ];
    }
}

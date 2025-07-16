<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'kode' => 'required|unique:vouchers|max:5',
            'nama' => 'required|max:100',
            'value' => 'required|numeric|between:0,100',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string'
        ]);

        Voucher::create($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dibuat.');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'kode' => 'required|max:5|unique:vouchers,kode,'.$voucher->id,
            'nama' => 'required|max:100',
            'value' => 'required|numeric|between:0,100',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'keterangan' => 'nullable|string'
        ]);

        $voucher->update($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}

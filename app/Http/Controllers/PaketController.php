<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Test; // Import model Test
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketController extends Controller
{
    /**
     * Menampilkan daftar semua paket.
     */
    public function index()
    {
        $pakets = Paket::orderBy('nama')->get();
        return view('pakets.index', compact('pakets'));
    }

    /**
     * Menampilkan formulir untuk membuat paket baru.
     */
    public function create()
    {
        $tests = Test::where('status', 'Aktif')->orderBy('nama')->get();
        return view('pakets.create', compact('tests'));
    }

    /**
     * Menyimpan paket baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_umum' => 'required|integer|min:0',
            'harga_bpjs' => 'required|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tests' => 'required|array|min:1', // Pastikan ada minimal 1 tes dalam paket
            'tests.*.test_id' => 'required|exists:tests,id',
            'tests.*.jumlah' => 'required|integer|min:1',
        ], [
            'tests.required' => 'Minimal satu pemeriksaan harus ditambahkan ke paket.',
            'tests.min' => 'Minimal satu pemeriksaan harus ditambahkan ke paket.',
        ]);

        DB::beginTransaction();
        try {
            $paket = Paket::create([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'harga_umum' => $request->harga_umum,
                'harga_bpjs' => $request->harga_bpjs,
                'status' => $request->status,
            ]);

            foreach ($request->tests as $testItem) {
                $paket->paketItems()->create([
                    'test_id' => $testItem['test_id'],
                    'jumlah' => $testItem['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('pakets.index')->with('success', 'Paket "' . $paket->nama . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan paket: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail paket tertentu.
     */
    public function show(Paket $paket)
    {
        $paket->load('tests'); // Load relasi tests
        return view('pakets.show', compact('paket'));
    }

    /**
     * Menampilkan formulir untuk mengedit paket.
     */
    public function edit(Paket $paket)
    {
        $tests = Test::where('status', 'Aktif')->orderBy('nama')->get();
        $paket->load('paketItems.test'); // Load relasi paketItems dan test di dalamnya
        return view('pakets.edit', compact('paket', 'tests'));
    }

    /**
     * Memperbarui paket di database.
     */
    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_umum' => 'required|integer|min:0',
            'harga_bpjs' => 'required|integer|min:0',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tests' => 'required|array|min:1',
            'tests.*.test_id' => 'required|exists:tests,id',
            'tests.*.jumlah' => 'required|integer|min:1',
            'tests.*.id' => 'nullable|exists:paket_items,id', // Untuk item yang sudah ada
        ], [
            'tests.required' => 'Minimal satu pemeriksaan harus ditambahkan ke paket.',
            'tests.min' => 'Minimal satu pemeriksaan harus ditambahkan ke paket.',
        ]);

        DB::beginTransaction();
        try {
            $paket->update([
                'kode' => $request->kode,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'harga_umum' => $request->harga_umum,
                'harga_bpjs' => $request->harga_bpjs,
                'status' => $request->status,
            ]);

            // Sinkronisasi item paket
            $existingItemIds = collect($request->tests)->filter(function ($item) {
                return isset($item['id']);
            })->pluck('id')->toArray();

            // Hapus item yang tidak ada lagi di request
            $paket->paketItems()->whereNotIn('id', $existingItemIds)->delete();

            foreach ($request->tests as $testItem) {
                if (isset($testItem['id'])) {
                    // Update item yang sudah ada
                    $paket->paketItems()->where('id', $testItem['id'])->update([
                        'test_id' => $testItem['test_id'],
                        'jumlah' => $testItem['jumlah'],
                    ]);
                } else {
                    // Buat item baru
                    $paket->paketItems()->create([
                        'test_id' => $testItem['test_id'],
                        'jumlah' => $testItem['jumlah'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('pakets.index')->with('success', 'Paket "' . $paket->nama . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui paket: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus paket dari database.
     */
    public function destroy(Paket $paket)
    {
        DB::beginTransaction();
        try {
            $paket->paketItems()->delete(); // Hapus item-item terkait terlebih dahulu
            $paket->delete();
            DB::commit();
            return redirect()->route('pakets.index')->with('success', 'Paket "' . $paket->nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus paket: ' . $e->getMessage());
        }
    }
}

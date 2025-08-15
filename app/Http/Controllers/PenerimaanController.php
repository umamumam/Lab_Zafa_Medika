<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenerimaanController extends Controller
{
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['Kasir', 'Admin'])) {
            return back()->with('error', 'Hanya kasir yang dapat melakukan klaim.');
        }
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
        ]);

        $visit = Visit::with('metodePembayaran')->findOrFail($request->visit_id);

        if ($visit->status_pembayaran !== 'Lunas') {
            return back()->with('error', 'Pembayaran belum lunas. Tidak bisa diklaim.');
        }

        if ($visit->penerimaan) {
            return back()->with('error', 'Data sudah diklaim.');
        }

        try {
            DB::beginTransaction();

            $penerimaan = Penerimaan::create([
                'visit_id' => $visit->id,
                'jumlah' => $visit->dibayar,
                'status' => 'Terklaim',
                'tgl_terima' => Carbon::now()->toDateString(),
                'user_id' => Auth::id(),
                'metodebyr_id' => $visit->metodebyr_id,
            ]);

            DB::commit();
            return back()->with('success', 'Data berhasil diklaim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal klaim: ' . $e->getMessage());
        }
    }
}

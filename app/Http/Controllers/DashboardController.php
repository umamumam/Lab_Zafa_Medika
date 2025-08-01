<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasiens = Pasien::count();
        $pasiensThisMonth = Pasien::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $totalVisits = Visit::count();
        $visitsThisMonth = Visit::whereMonth('tgl_order', Carbon::now()->month)
            ->whereYear('tgl_order', Carbon::now()->year)
            ->count();
        $totalValidatedVisits = Visit::where('status_order', 'Selesai')->count();
        $totalProsesVisits = Visit::where('status_order', 'Proses')->count();

        $totalRevenue = Visit::sum('total_tagihan');
        $revenueThisMonth = Visit::whereMonth('tgl_order', Carbon::now()->month)
            ->whereYear('tgl_order', Carbon::now()->year)
            ->sum('total_tagihan');

        $totalUsers = User::count();
        $usersOnline = DB::table('sessions')
            ->where('user_id', '!=', null)
            ->where('last_activity', '>=', Carbon::now()->subMinutes(10)->timestamp)
            ->distinct('user_id')
            ->count('user_id');

        // Data untuk grafik kunjungan Umum dan BPJS per bulan
        $visitsUmum = [];
        $visitsBPJS = [];
        $currentYear = Carbon::now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $countUmum = Visit::where('jenis_pasien', 'Umum')
                ->whereMonth('tgl_order', $month)
                ->whereYear('tgl_order', $currentYear)
                ->count();
            $visitsUmum[] = $countUmum;

            $countBPJS = Visit::where('jenis_pasien', 'BPJS')
                ->whereMonth('tgl_order', $month)
                ->whereYear('tgl_order', $currentYear)
                ->count();
            $visitsBPJS[] = $countBPJS;
        }
        $latestVisits = Visit::with(['pasien', 'dokter', 'ruangan'])
            ->orderBy('tgl_order', 'desc')
            ->limit(6)
            ->get();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $dailySalesRevenue = Visit::whereBetween('tgl_order', [$startDate, $endDate])
            ->sum('dibayar');
        $dailySalesDateRange = $startDate->format('F Y');

        $today = Carbon::now();
        $todayRevenue = Visit::whereDate('tgl_order', $today->toDateString())
            ->sum('dibayar');
        $yesterday = Carbon::yesterday();
        $yesterdayRevenue = Visit::whereDate('tgl_order', $yesterday->toDateString())
            ->sum('dibayar');
        $revenuePercentageChange = 0;
        if ($yesterdayRevenue > 0) {
            $revenuePercentageChange = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
        } elseif ($todayRevenue > 0) {
            $revenuePercentageChange = 100;
        }
        $testGroupCounts = DB::table('visits')
            ->join('visit_tests', 'visits.id', '=', 'visit_tests.visit_id')
            ->join('tests', 'visit_tests.test_id', '=', 'tests.id')
            ->select('tests.grup_test', DB::raw('count(DISTINCT visits.id) as visit_count'))
            ->groupBy('tests.grup_test')
            ->get();

        return view('dashboard', compact(
            'totalPasiens',
            'pasiensThisMonth',
            'totalVisits',
            'visitsThisMonth',
            'totalValidatedVisits',
            'totalProsesVisits',
            'totalRevenue',
            'revenueThisMonth',
            'totalUsers',
            'usersOnline',
            'visitsUmum',
            'visitsBPJS',
            'latestVisits',
            'dailySalesRevenue',
            'dailySalesDateRange',
            'todayRevenue',
            'revenuePercentageChange',
            'testGroupCounts'
        ));
    }
}

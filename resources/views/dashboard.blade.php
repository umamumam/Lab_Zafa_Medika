@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <p>Selamat Datang <b>{{ Auth::user()->name }}</b> di Sistem Informasi Lab Zafa Medika</p>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a> --}}
            <a href="#" class="btn btn-primary btn-round">Manage</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Pasien</p>
                                <h4 class="card-title">{{ $totalPasiens }}</h4>
                                <p class="card-category small">Bulan Ini: {{ $pasiensThisMonth }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-hospital-user"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Kunjungan</p>
                                <h4 class="card-title">{{ $totalVisits }}</h4>
                                <p class="card-category small">Bulan Ini: {{ $visitsThisMonth }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="far fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Validasi Selesai</p>
                                <h4 class="card-title">{{ $totalValidatedVisits }}</h4>
                                <p class="card-category small">Status Proses: {{ $totalProsesVisits }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Pengguna</p>
                                <h4 class="card-title">{{ $totalUsers }}</h4>
                                <p class="card-category small">Petugas Online: {{ $usersOnline }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Grafik Kunjungan Pemeriksaan</div>
                        <div class="card-tools">
                            <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                <span class="btn-label">
                                    <i class="fa fa-pencil"></i>
                                </span>
                                Export
                            </a>
                            <a href="#" class="btn btn-label-info btn-round btn-sm">
                                <span class="btn-label">
                                    <i class="fa fa-print"></i>
                                </span>
                                Print
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
                <script>
                    // Pastikan variabel ini tersedia dari PHP
var visitsUmum = @json($visitsUmum);
var visitsBPJS = @json($visitsBPJS);
                </script>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Omset Bulanan</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-label-light dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-category">{{ $dailySalesDateRange }}</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>Rp {{ number_format($dailySalesRevenue, 0, ',', '.') }}</h1>
                    </div>
                    <div class="pull-in">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary">
                        @if ($revenuePercentageChange >= 0)
                        +{{ number_format($revenuePercentageChange, 0) }}%
                        @else
                        {{ number_format($revenuePercentageChange, 0) }}%
                        @endif
                    </div>
                    <h2 class="mb-2">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
                    <p class="text-muted">Omset Hari Ini</p>
                    <div class="pull-in sparkline-fix">
                        <div id="lineChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <h4 class="card-title">Pemeriksaan Terakhir</h4>
                        <div class="card-tools">
                            <button class="btn btn-icon btn-link btn-primary btn-xs">
                                <span class="fa fa-angle-down"></span>
                            </button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                <span class="fa fa-sync-alt"></span>
                            </button>
                            <button class="btn btn-icon btn-link btn-primary btn-xs">
                                <span class="fa fa-times"></span>
                            </button>
                        </div>
                    </div>
                    <p class="card-category">
                        Daftar kunjungan pasien terbaru
                    </p>
                </div>
                <div class="card-body">
                    <div class="latest-visits-list">
                        @forelse ($latestVisits as $visit)
                        <p style="margin-bottom: 5px;">
                            {{ \Carbon\Carbon::parse($visit->tgl_order)->format('d M Y') }} -
                            {{ $visit->no_order }} -
                            {{ $visit->pasien->nama ?? 'N/A' }} -
                            {{ $visit->ruangan->nama ?? 'N/A' }} -
                            <span style="
                                @if($visit->status_order == 'Selesai')
                                    color: #28a745; /* Hijau /
                                @elseif($visit->status_order == 'Proses')
                                    color: #ffc107; / Kuning/Oranye /
                                @else {{-- Sampling --}}
                                    color: #17a2b8; / Biru Muda */
                                @endif ">
                                {{ $visit->status_order }}
                            </span>
                        </p>
                        @empty
                        <p class="text-center">Tidak ada data pemeriksaan terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <h4 class="card-title">Kunjungan per Grup Test</h4>
                    </div>
                    <p class="card-category">
                        Total kunjungan berdasarkan kategori test
                    </p>
                </div>
                <div class="card-body">
                    <div class="test-group-list">
                        @forelse ($testGroupCounts as $group)
                        <p style="margin-bottom: 5px; display: flex; justify-content: space-between;">
                            {{-- Perbaikan: Mengambil nama dari grup_tests --}}
                            <span>{{ $group->grup_test_nama }}</span>
                            <span class="fw-bold">{{ $group->visit_count }}</span>
                        </p>
                        @empty
                        <p class="text-center">Tidak ada data grup test.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

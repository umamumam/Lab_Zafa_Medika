@extends('layouts1.app')

@section('content')
<style>
    .badge-purple {
        background-color: #6f42c1;
        color: white;
    }
</style>
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Visit Laboratorium</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="basic-datatables">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No. Lab</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Order</th>
                            <th>Tanggal Sampel</th>
                            <th>Jenis Sampel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($visits as $visit)
                        @php
                        $samples = $visit->visitTests->map(fn($vt) => $vt->test->jenis_sampel)->unique();

                        $badgeMap = [
                        'Whole Blood EDTA' => ['label' => 'EDTA', 'class' => 'badge-purple'],
                        'Serum' => ['label' => 'Serum', 'class' => 'badge-warning'],
                        'Plasma Citrat' => ['label' => 'Citrat', 'class' => 'badge-info'],
                        'Urin' => ['label' => 'Urin', 'class' => 'badge-secondary'],
                        ];

                        $sampleDate = null;
                        foreach ($visit->visitTests as $vt) {
                        if ($vt->hasilLabs->isNotEmpty()) {
                        $sampleDate = $vt->hasilLabs->first()->created_at;
                        break;
                        }
                        }
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $visit->no_order }}</td>
                            <td>{{ $visit->pasien->nama }}</td>
                            <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($sampleDate)
                                <span class="badge badge-info">{{ $sampleDate->format('d/m/Y H:i') }}</span>
                                @else
                                <span class="badge badge-danger">Belum</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @foreach($samples as $sampel)
                                @php
                                $badge = $badgeMap[$sampel] ?? ['label' => $sampel, 'class' => 'badge-dark'];
                                @endphp
                                <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('visits.cetak.barcode', $visit->no_order) }}"
                                    class="btn btn-sm btn-success" target="_blank" title="Cetak Barcode">
                                    <i class="fas fa-print"></i> &nbsp;<i class="fas fa-barcode"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

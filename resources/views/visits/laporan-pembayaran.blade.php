@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Laporan Pembayaran</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="basic-datatables">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No. Lab</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Order</th>
                            <th>Total</th>
                            <th>Tagihan</th>
                            <th>Sisa</th>
                            <th>Metode Bayar</th>
                            <th>Tgl Klaim</th>
                            <th>Kasir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($visits as $visit)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $visit->no_order }}</td>
                            <td>{{ $visit->pasien->nama }}</td>
                            <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($visit->total_tagihan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($visit->dibayar, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($visit->jenis_pasien == 'BPJS' ? 0 : ($visit->total_tagihan - $visit->dibayar), 0, ',', '.') }}</td>
                            <td>{{ str_replace('BPJS Kerjasama', 'BPJS-K', $visit->penerimaan->metodeBayar->nama ?? '-') }}</td>
                            <td>{{ \Carbon\Carbon::parse($visit->penerimaan->tgl_terima)->format('d/m/Y') }}</td>
                            <td>{{ $visit->penerimaan->user->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

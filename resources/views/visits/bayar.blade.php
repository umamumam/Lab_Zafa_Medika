@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Daftar Pembayaran & Klaim</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="display table table-striped table-hover" id="basic-datatables">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No. Order</th>
                            <th>Nama Pasien</th>
                            <th>Tgl Order</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                            <td>Rp {{ number_format($visit->total_tagihan) }}</td>
                            <td>Rp {{ number_format($visit->dibayar) }}</td>
                            <td>{{ $visit->metodePembayaran->nama ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $visit->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $visit->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center" style="gap: 5px;">

                                    {{-- Tombol Bayar --}}
                                    @if($visit->status_pembayaran == 'Belum Lunas')
                                    <a href="{{ route('visits.show', $visit->id) }}#form-bayar" class="btn btn-sm btn-warning" title="Bayar">
                                        <i class="fas fa-money-bill"></i>
                                    </a>
                                    @endif

                                    {{-- Tombol Klaim --}}
                                    @if ($visit->status_pembayaran == 'Lunas' && !$visit->penerimaan)
                                    <form action="{{ route('penerimaan.store') }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Klaim pembayaran untuk Visit ini?')">
                                        @csrf
                                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">Klaim</button>
                                    </form>
                                    @elseif($visit->penerimaan)
                                    <span class="badge bg-success">Sudah Diklaim</span>
                                    @endif
                                </div>
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

@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Detail Paket Pemeriksaan</h5>
                    <div class="ms-auto">
                        <a href="{{ route('pakets.edit', $paket->id) }}" class="btn btn-warning btn-round">
                            <i class="fa fa-edit"></i> Edit Paket
                        </a>
                        <a href="{{ route('pakets.index') }}" class="btn btn-info btn-round">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2 mb-3">INFORMASI PAKET</h6>
                            <p><strong>Kode Paket:</strong> {{ $paket->kode }}</p>
                            <p><strong>Nama Paket:</strong> {{ $paket->nama }}</p>
                            <p><strong>Deskripsi:</strong> {{ $paket->deskripsi ?? '-' }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge {{ $paket->status == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $paket->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2 mb-3">HARGA PAKET</h6>
                            <p><strong>Harga Umum:</strong> Rp {{ number_format($paket->harga_umum, 0, ',', '.') }}</p>
                            <p><strong>Harga BPJS:</strong> Rp {{ number_format($paket->harga_bpjs, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">ITEM PEMERIKSAAN DALAM PAKET</h6>
                        @if ($paket->tests->isEmpty())
                        <p>Tidak ada pemeriksaan dalam paket ini.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Test</th>
                                        <th>Nama Pemeriksaan</th>
                                        <th>Jumlah</th>
                                        <th>Grup Test</th>
                                        <th>Sub Grup</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paket->tests as $test)
                                    <tr>
                                        <td>{{ $test->kode }}</td>
                                        <td>{{ $test->nama }}</td>
                                        <td>{{ $test->pivot->jumlah }}</td>
                                        <td>{{ $test->grup_test }}</td>
                                        <td>{{ $test->sub_grup }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

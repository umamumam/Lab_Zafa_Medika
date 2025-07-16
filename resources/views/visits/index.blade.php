@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Pemeriksaan</h4>
            <a href="{{ route('visits.create') }}" class="btn btn-primary btn-round ms-auto">
                <i class="fas fa-plus"></i> Tambah Pemeriksaan
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Nama Pasien</th>
                            <th>Jenis Pasien</th>
                            <th>Dokter</th>
                            <th>Ruangan</th>
                            {{-- <th>Total Tagihan</th>
                            <th>Diskon</th>
                            <th>Status Pembayaran</th> --}}
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
                            <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                            <td>{{ $visit->pasien->nama }}</td>
                            <td>
                                <span class="badge badge-{{ $visit->jenis_pasien == 'Umum' ? 'primary' : 'info' }}">
                                    {{ $visit->jenis_pasien }}
                                </span>
                            </td>
                            <td>{{ $visit->dokter->nama ?? '-' }}</td>
                            <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                            {{-- <td>{{ number_format($visit->total_tagihan, 0, ',', '.') }}</td>
                            <td>{{ number_format($visit->total_diskon, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $visit->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $visit->status_pembayaran }}
                                </span>
                            </td> --}}
                            <td>
                                <span class="badge badge-{{
                                    $visit->status_order == 'Selesai' ? 'success' :
                                    ($visit->status_order == 'Proses' ? 'info' : 'secondary')
                                }}">
                                    {{ $visit->status_order }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($visit->status_order == 'Sampling')
                                <a href="{{ route('visits.edit', $visit->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('visits.destroy', $visit->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus pemeriksaan ini?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
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

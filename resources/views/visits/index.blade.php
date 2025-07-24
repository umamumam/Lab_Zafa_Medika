@extends('layouts1.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
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
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle py-1 px-3" type="button"
                                        id="dropdownMenuButton{{ $visit->id }}" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="font-size: 0.9rem;">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right"
                                        aria-labelledby="dropdownMenuButton{{ $visit->id }}"
                                        style="font-size: 0.95rem;">
                                        <a class="dropdown-item py-2" href="{{ route('visits.show', $visit->id) }}">
                                            Preview
                                        </a>
                                        <a class="dropdown-item py-2"
                                            href="{{ route('visits.cetak.label', $visit->no_order) }}" target="_blank">
                                            Cetak Label
                                        </a>
                                        <a class="dropdown-item py-2"
                                            href="{{ route('visits.cetak.nota', $visit->no_order) }}" target="_blank">
                                            Cetak Nota
                                        </a>
                                        @if($visit->status_order == 'Selesai')
                                        <a class="dropdown-item py-2" href="{{ route('hasil-lab.print', $visit->id) }}"
                                            target="_blank">
                                            Cetak Hasil
                                        </a>
                                        @endif
                                        @if($visit->status_order == 'Sampling')
                                        <hr>
                                        <form action="{{ route('visits.destroy', $visit->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger"
                                                onclick="return confirm('Hapus data ini?')"
                                                style="cursor: pointer; width: 100%; text-align: left; background: none; border: none;">
                                                Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
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

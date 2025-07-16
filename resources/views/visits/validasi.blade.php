@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Review Hasil dan Validasi</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any()))
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
                            <th>Dokter</th>
                            <th>Ruangan</th>
                            <th>Status Order</th>
                            <th>Verifikasi</th>
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
                            <td>{{ $visit->dokter->nama ?? '-' }}</td>
                            <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $visit->status_order === 'Selesai' ? 'success' : 'primary' }}">
                                    {{ $visit->status_order }}
                                </span>
                            </td>
                            <td>
                                @if($visit->verifikasi_status === 'Valid')
                                <span class="badge badge-success">Valid</span>
                                @elseif($visit->verifikasi_status === 'Belum Valid')
                                <span class="badge badge-danger">Belum Valid</span>
                                @else
                                <span class="badge badge-secondary">Tidak Ada Hasil</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center" style="gap: 5px;">
                                    <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-sm btn-info"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'Admin')
                                    @if($visit->status_order === 'Proses')
                                    <form action="{{ route('hasil-lab.valid', $visit->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Validasi semua hasil pemeriksaan ini?')"
                                            title="Validasi Hasil">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('hasil-lab.unvalid', $visit->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Batalkan validasi semua hasil pemeriksaan ini?')"
                                            title="Batalkan Validasi">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    @endif
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

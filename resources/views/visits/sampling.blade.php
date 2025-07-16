@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Antrian Sampling</h4>
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
                            <th rowspan="2">No</th>
                            <th rowspan="2">No. Lab</th>
                            <th rowspan="2">Nama Pasien</th>
                            <th rowspan="2">Tanggal Order</th>
                            <th colspan="5" style="text-align: center">Jenis Sampel</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Edta</th>
                            <th>Serum</th>
                            <th>Citrat</th>
                            <th>Urin</th>
                            <th>Lainnya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($visits as $visit)
                        @php
                        // Ambil semua jenis sampel dari visit_tests terkait
                        $samples = $visit->visitTests->map(function($vt) {
                        return $vt->test->jenis_sampel;
                        })->unique();

                        // Cek keberadaan masing-masing jenis sampel
                        $hasEdta = $samples->contains('Whole Blood EDTA');
                        $hasSerum = $samples->contains('Serum');
                        $hasCitrat = $samples->contains('Plasma Citrat');
                        $hasUrin = $samples->contains('Urin');
                        $hasLainnya = $samples->diff(['Whole Blood EDTA', 'Serum', 'Plasma Citrat', 'Urin'])->count() >
                        0;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $visit->no_order }}</td>
                            <td>{{ $visit->pasien->nama }}</td>
                            <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{!! $hasEdta ? '<i class="fas fa-check text-success"></i>' : '' !!}
                            </td>
                            <td class="text-center">{!! $hasSerum ? '<i class="fas fa-check text-success"></i>' : '' !!}
                            </td>
                            <td class="text-center">{!! $hasCitrat ? '<i class="fas fa-check text-success"></i>' : ''
                                !!}</td>
                            <td class="text-center">{!! $hasUrin ? '<i class="fas fa-check text-success"></i>' : '' !!}
                            </td>
                            <td class="text-center">{!! $hasLainnya ? '<i class="fas fa-check text-success"></i>' : ''
                                !!}</td>
                            <td>
                                @if($visit->status_order == 'Sampling')
                                <form action="{{ route('visits.update-status', $visit->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_order" value="Proses">
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Ubah status order ke Proses?')">
                                        <i class="fas fa-forward"></i>
                                    </button>
                                </form>
                                @else
                                <span class="badge badge-{{ $visit->status_order == 'Proses' ? 'info' : 'success' }}">
                                    {{ $visit->status_order }}
                                </span>
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

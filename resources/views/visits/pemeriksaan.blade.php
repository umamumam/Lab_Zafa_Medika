@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Daftar Pemeriksaan - Status Proses</h4>
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
                            <th>Jumlah Test</th>
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
                            <td>{{ $visit->dokter->nama ?? '-' }}</td>
                            <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                            <td>{{ $visit->visitTests->count() }}</td>
                            <td>
                                <span class="badge badge-info">{{ $visit->status_order }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Tombol Hasil Lab -->
                                    <a href="{{ route('hasil-lab.edit', $visit->id) }}" class="btn btn-sm btn-primary"
                                        title="Input Hasil">
                                        <i class="fas fa-flask"></i>
                                    </a>

                                    @php
                                    $hasJasmaniMcuTest = false;
                                    $jasmaniMcuVisitTest = null;
                                    foreach ($visit->visitTests as $vt) {
                                    if (stripos($vt->test->nama, 'jasmani') !== false) {
                                    $hasJasmaniMcuTest = true;
                                    $jasmaniMcuVisitTest = $vt;
                                    break;
                                    }
                                    }
                                    @endphp

                                    @if($hasJasmaniMcuTest)
                                    @if($jasmaniMcuVisitTest->jasmaniMcu)
                                    {{-- Tombol Show MCU --}}
                                    <a href="{{ route('jasmani-mcu.show', $jasmaniMcuVisitTest->jasmaniMcu->id) }}"
                                        class="btn btn-sm btn-info" title="Lihat Hasil Jasmani MCU">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Tombol Edit MCU --}}
                                    <a href="{{ route('jasmani-mcu.edit', $jasmaniMcuVisitTest->jasmaniMcu->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit Hasil Jasmani MCU">
                                        <i class="fas fa-user-md"></i>
                                    </a>
                                    @else
                                    {{-- Tombol Input MCU --}}
                                    <a href="{{ route('jasmani-mcu.create', $jasmaniMcuVisitTest->id) }}"
                                        class="btn btn-sm btn-success" title="Input Hasil Jasmani MCU">
                                        <i class="fas fa-user-md"></i> MCU
                                    </a>
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

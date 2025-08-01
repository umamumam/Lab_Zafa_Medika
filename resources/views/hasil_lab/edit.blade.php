@extends('layouts1.app')

@section('title', 'Review Hasil Laboratorium')

@section('content')
@php
function nilaiRujukanText($model, $pasien, $usia)
{
    $ref = null;

    if ($model instanceof \App\Models\DetailTest) {
        $ref = \App\Models\NilaiNormal::query()
                ->where('detail_test_id', $model->id)
                ->where(function ($q) use ($pasien, $usia) {
                    $q->where('jenis_kelamin', $pasien->jenis_kelamin)
                        ->orWhere('jenis_kelamin', 'Umum');
                })
                ->where(function ($q) use ($usia) {
                    $q->whereNull('usia_min')->orWhere('usia_min', '<=', $usia);
                })
                ->where(function ($q) use ($usia) {
                    $q->whereNull('usia_max')->orWhere('usia_max', '>=', $usia);
                })
                ->orderByDesc('jenis_kelamin')
                ->first();
    } else { // instance of Test
        $ref = \App\Models\NilaiNormal::query()
                ->where('test_id', $model->id)
                ->where(function ($q) use ($pasien, $usia) {
                    $q->where('jenis_kelamin', $pasien->jenis_kelamin)
                        ->orWhere('jenis_kelamin', 'Umum');
                })
                ->where(function ($q) use ($usia) {
                    $q->whereNull('usia_min')->orWhere('usia_min', '<=', $usia);
                })
                ->where(function ($q) use ($usia) {
                    $q->whereNull('usia_max')->orWhere('usia_max', '>=', $usia);
                })
                ->orderByDesc('jenis_kelamin')
                ->first();
    }

    if (!$ref) {
        return [
            'text' => $model->nilai_normal ?? '-',
            'min'  => null,
            'max'  => null,
        ];
    }

    if ($ref->type === 'Range') {
        return [
            'text' => $ref->min . ' - ' . $ref->max,
            'min'  => $ref->min,
            'max'  => $ref->max,
        ];
    } else {
        return [
            'text' => $ref->min,
            'min'  => $ref->min,
            'max'  => $ref->min,
        ];
    }
}

$usia = $visit->pasien->tgl_lahir->age;
@endphp
<div class="page-inner">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Review Hasil Laboratorium - {{ $visit->no_order }}</h4>
                    <div>
                        <a href="{{ route('visits.show', $visit->id) }}" class="btn btn-warning">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('hasil-lab.update', $visit->id) }}" method="POST" id="hasilForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th width="40%">No Order Laboratorium</th>
                                        <td>{{ $visit->no_order }}</td>
                                    </tr>
                                    <tr>
                                        <th width="40%">Nomor RM</th>
                                        <td>{{ $visit->pasien->norm }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Pasien</th>
                                        <td>{{ $visit->pasien->nama }} ({{ $visit->pasien->jenis_kelamin == 'Laki - Laki' ? 'L' :
                                            'P'
                                            }})</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <td>{{ $visit->pasien->tgl_lahir->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $visit->jenis_pasien }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Waktu Order</th>
                                        <td>{{ $visit->tgl_order->format('Y-m-d - H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dokter Pengirim</th>
                                        <td>{{ $visit->dokter->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ruangan</th>
                                        <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kontak</th>
                                        <td>{{ $visit->pasien->no_hp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diagnosa</th>
                                        <td>{{ $visit->diagnosa ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Parameter</th>
                                        <th width="10%">Flag</th>
                                        <th width="15%">Hasil</th>
                                        <th width="10%">Satuan</th>
                                        <th width="20%">Nilai Rujukan</th>
                                        <th width="15%">Metode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1 @endphp
                                    @foreach($visit->visitTests as $vt)
                                        @php
                                            $mainRef = nilaiRujukanText($vt->test, $visit->pasien, $usia);
                                        @endphp
                                        @foreach($vt->hasilLabs->whereNull('detail_test_id') as $mainTest)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td><strong>{{ $vt->test->nama }}</strong></td>
                                            <td class="text-center">
                                                @if($mainTest->flag)
                                                    <span class="badge {{ $mainTest->flag == 'H' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                        {{ $mainTest->flag }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($vt->test->detailTests->count() > 0)
                                                    &nbsp;
                                                @else
                                                    <input type="text"
                                                        class="form-control form-control-sm hasil-input"
                                                        name="hasil[{{ $mainTest->id }}]"
                                                        value="{{ $mainTest->hasil }}"
                                                        data-test-id="{{ $mainTest->id }}"
                                                        data-min-ref="{{ $mainRef['min'] }}"
                                                        data-max-ref="{{ $mainRef['max'] }}">
                                                @endif
                                            </td>
                                            <td>{{ $vt->test->satuan ?? '-' }}</td>
                                            <td>{{ $vt->test->nilai_normal ?? '-' }}</td>
                                            {{-- <td>{{ $mainRef['text'] }}</td> --}}
                                            <td>{{ $vt->test->metode ?? '-' }}</td>
                                        </tr>
                                        @endforeach

                                        @foreach($vt->test->detailTests as $detailTest)
                                            @php
                                                $detailHasil = $vt->hasilLabs->where('detail_test_id', $detailTest->id)->first();
                                                $detailRef   = nilaiRujukanText($detailTest, $visit->pasien, $usia);
                                            @endphp
                                            @if($detailHasil)
                                            <tr>
                                                <td></td>
                                                <td style="padding-left: 30px;">{{ $detailTest->nama }}</td>
                                                <td class="text-center">
                                                    @if($detailHasil->flag)
                                                        <span class="badge {{ $detailHasil->flag == 'H' ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                            {{ $detailHasil->flag }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text"
                                                        class="form-control form-control-sm hasil-input"
                                                        name="hasil[{{ $detailHasil->id }}]"
                                                        value="{{ $detailHasil->hasil }}"
                                                        data-test-id="{{ $detailHasil->id }}"
                                                        data-min-ref="{{ $detailRef['min'] }}"
                                                        data-max-ref="{{ $detailRef['max'] }}">
                                                </td>
                                                <td>{{ $detailTest->satuan ?? '-' }}</td>
                                                <td>{{ $detailTest->nilai_normal ?? '-' }}</td>
                                                {{-- <td>{{ $detailRef['text'] }}</td> --}}
                                                <td>{{ $vt->test->metode ?? '-' }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="kesan">Kesan</label>
                                    <textarea class="form-control" id="kesan" name="kesan"
                                        rows="2">{{ $visit->kesan }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan"
                                        rows="2">{{ $visit->catatan }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-4 gap-3">
                            @if($visit->status_order == 'Proses')
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Hasil
                            </button>
                            @endif
                        </div>
                    </form>
                    <div class="d-flex justify-content-center mt-4 gap-3">
                        @if($visit->status_order == 'Proses')
                        <form action="{{ route('hasil-lab.validate', $visit->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin memvalidasi semua hasil dan menyelesaikan kunjungan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Validasi Semua
                            </button>
                        </form>
                        @else
                        <form action="{{ route('hasil-lab.unvalidate', $visit->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan validasi dan mengembalikan ke status Proses?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Batal Validasi
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
    $('.hasil-input').on('change', function() {
        const value = parseFloat($(this).val());
        const minRef = parseFloat($(this).data('min-ref'));
        const maxRef = parseFloat($(this).data('max-ref'));

        if (!isNaN(value) && !isNaN(minRef) && !isNaN(maxRef)) {
            let flag = null;
            if (value > maxRef) {
                flag = 'H';
            } else if (value < minRef) {
                flag = 'L';
            }

            // Update the flag badge immediately
            const flagCell = $(this).closest('tr').find('.badge');
            if (flag) {
                flagCell.removeClass('bg-warning bg-danger');
                if (flag === 'H') {
                    flagCell.addClass('bg-danger').text('H');
                } else {
                    flagCell.addClass('bg-warning').text('L');
                }
                flagCell.show();
            } else {
                flagCell.hide();
            }
        }
    });
});
</script>
@endpush
@endsection

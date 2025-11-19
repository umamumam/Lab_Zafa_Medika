@extends('layouts1.app')

@section('title', 'Detail Kunjungan')

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Order Laboratorium - {{ $visit->no_order }}</h4>
                    <div>
                        <a href="{{ route('visits.index', $visit->id) }}" class="btn btn-warning">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                                    <td>{{ $visit->pasien->nama }} ({{ $visit->pasien->jenis_kelamin == 'L' ? 'L' : 'P'
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
                                    <th class="text-center" width="15%">Hasil</th>
                                    <th width="10%">Satuan</th>
                                    <th width="20%">Nilai Rujukan</th>
                                    <th width="15%">Metode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1 @endphp
                                @foreach($visit->visitTests as $vt)
                                @foreach($vt->hasilLabs->whereNull('detail_test_id') as $mainTest)
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td><strong>{{ $vt->test->nama }}</strong></td>
                                    <td class="text-center">
                                        @if($mainTest->flag)
                                        @if($mainTest->flag == 'H')
                                        <span class="badge bg-danger">H</span>
                                        @else
                                        <span class="badge bg-warning text-dark">L</span>
                                        @endif
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if($vt->test->detailTests->count() > 0)
                                        &nbsp;
                                        @else
                                        <span class="hasil-value">{{ $mainTest->hasil ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($vt->test->detailTests->count() > 0)
                                        &nbsp;
                                        @else
                                        {{ $vt->test->satuan ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($vt->test->detailTests->count() > 0)
                                        &nbsp;
                                        @else
                                        {{ $vt->test->nilai_normal ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($vt->test->detailTests->count() > 0)
                                        &nbsp;
                                        @else
                                        {{ $vt->test->metode ?? '-' }}
                                        @endif
                                    </td>
                                </tr>

                                {{-- Detail tests --}}
                                @foreach($vt->test->detailTests as $detailTest)
                                @php
                                $detailHasil = $vt->hasilLabs->where('detail_test_id', $detailTest->id)->first();
                                @endphp
                                @if($detailHasil)
                                <tr>
                                    <td></td>
                                    <td style="padding-left: 30px;">{{ $detailTest->nama }}</td>
                                    <td class="text-center">
                                        @if($detailHasil->flag)
                                        @if($detailHasil->flag == 'H')
                                        <span class="badge bg-danger">H</span>
                                        @else
                                        <span class="badge bg-warning text-dark">L</span>
                                        @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="hasil-value">{{ $detailHasil->hasil ?? '-' }}</span>
                                    </td>
                                    <td>{{ $detailTest->satuan ?? '-' }}</td>
                                    <td>
                                        {{ $detailTest->nilai_normal ?? '-' }}
                                    </td>
                                    <td>{{ $vt->test->metode ?? '-' }}</td>
                                </tr>
                                @endif
                                @endforeach
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kesan">Kesan</label>
                                <textarea class="form-control" id="kesan" name="kesan" rows="2"
                                    disabled>{{ $visit->kesan }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="catatan">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="2"
                                    disabled>{{ $visit->catatan }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class="table">
                        <tr>
                            <th width="80%">Status Pembayaran</th>
                            <td>
                                <span
                                    class="badge bg-{{ $visit->status_pembayaran == 'Lunas' ? 'success' : 'warning' }}">
                                    {{ $visit->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total Tagihan</th>
                            <td>Rp {{ number_format($visit->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Dibayar</th>
                            <td>Rp {{ number_format($visit->dibayar, 0, ',', '.') }}</td>
                        </tr>
                        @if($visit->status_pembayaran !== 'Lunas')
                        <tr>
                            <th style="color: red">Belum Terbayar</th>
                            <td>
                                @php
                                $sisa = $visit->total_tagihan - $visit->dibayar;
                                @endphp
                                Rp {{ number_format(max($sisa, 0), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    </table>

                    @if($visit->status_pembayaran !== 'Lunas')
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#modalPembayaran">
                            <i class="fas fa-money-bill-wave"></i> Bayar
                        </button>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pembayaran -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('visits.pembayaran', $visit->id) }}" method="POST" id="formPembayaran">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPembayaranLabel">Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Total Tagihan</label>
                        <input type="text" class="form-control"
                            value="Rp {{ number_format($visit->total_tagihan, 0, ',', '.') }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label>Dibayar</label>
                        <input type="number" name="dibayar" id="dibayar" class="form-control" min="0" value="0" required>
                        <small class="text-muted" id="infoBPJS" style="display: none;">
                            Untuk BPJS, pembayaran otomatis dianggap lunas
                        </small>
                    </div>
                    <div class="mb-3">
                        <label>Metode Pembayaran</label>
                        <select name="metodebyr_id" id="metodePembayaran" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            @foreach($metodePembayarans as $metode)
                            <option value="{{ $metode->id }}" data-nama="{{ $metode->nama }}">
                                {{ $metode->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const metodeSelect = document.getElementById('metodePembayaran');
        const dibayarInput = document.getElementById('dibayar');
        const infoBPJS = document.getElementById('infoBPJS');

        metodeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const metodeNama = selectedOption.getAttribute('data-nama');

            if (metodeNama === 'BPJS') {
                dibayarInput.value = 0;
                dibayarInput.disabled = true;
                dibayarInput.placeholder = 'Otomatis 0 untuk BPJS';
                infoBPJS.style.display = 'block';
            } else {
                dibayarInput.disabled = false;
                dibayarInput.placeholder = '';
                infoBPJS.style.display = 'none';

                if (dibayarInput.value == 0) {
                    dibayarInput.value = '';
                }
            }
        });

        metodeSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection

@endsection

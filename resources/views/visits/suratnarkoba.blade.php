@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Surat Narkoba</h4>
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
                            <th>No</th>
                            <th>No. Lab</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Order</th>
                            <th>Dokter</th>
                            <th>Ruangan</th>
                            <th>Status Order</th>
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
                                <div class="d-flex justify-content-start align-items-center" style="gap: 5px;">

                                    {{-- Tombol Cetak --}}
                                    <a href="{{ route('hasil-lab.print', $visit->no_order) }}" target="_blank"
                                        class="btn btn-sm btn-primary" title="Cetak Hasil">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @php
                                    $noHp = $visit->pasien->no_hp ?? '';
                                    $noHp = preg_replace('/[^0-9]/', '', $noHp);
                                    if (substr($noHp, 0, 1) === '0') {
                                    $noHp = '62' . substr($noHp, 1);
                                    } elseif (substr($noHp, 0, 1) !== '6') {
                                    $noHp = '62' . $noHp;
                                    }
                                    $noWa = $noHp;
                                    $namaPasien = strtoupper($visit->pasien->nama);
                                    $tanggalOrder = $visit->tgl_order->format('Y-m-d');
                                    $hasilLab = $visit->hasilLabs ?? collect();
                                    $firstHasil = $hasilLab->first();
                                    $waktuValidasi = $firstHasil?->validated_at ?? now();
                                    $waktuValidasiStr = \Carbon\Carbon::parse($waktuValidasi)->format('Y-m-d H:i:s');
                                    $noLab = $visit->no_order;
                                    $linkPdf = url('/hasil-lab/download/' . md5($visit->id));
                                    $pesan = "*Notifikasi Lab. Zafa Medika*\n"
                                    . "Kepada Yth. Bpk/Ibu *{$namaPasien}*\n\n"
                                    . "Kami informasikan bahwa pemeriksaan laboratorium:\n"
                                    . "Nama: {$namaPasien}\n"
                                    . "No Lab: {$noLab}\n"
                                    . "Tanggal: {$tanggalOrder}\n"
                                    . "Status: *Selesai*\n\n"
                                    . "Hasil laboratorium Anda telah tersedia di link berikut:\n"
                                    . "{$linkPdf}\n\n"
                                    . "Demi menjaga kerahasiaan dokumen medis Anda, disarankan untuk tidak membagikan
                                    pesan ini atau upload ke media sosial atau media publik lainnya.\n"
                                    . "Dokumen tidak memerlukan tanda tangan basah karena sudah divalidasi oleh sistem
                                    Lab Zafa Medika pada {$waktuValidasiStr}.\n\n"
                                    . "Terimakasih atas kepercayaan Anda.\n\n"
                                    . "*- Zafa Medika -*\n"
                                    . "_Pesan ini dibuat oleh sistem_";
                                    $waLink = $noWa ? 'https://api.whatsapp.com/send?phone=' . preg_replace('/[^0-9]/',
                                    '', $noWa) . '&text=' . urlencode($pesan) : '#';
                                    @endphp
                                    @if($noWa)
                                    <a href="{{ $waLink }}" target="_blank" class="btn btn-sm btn-success"
                                        title="Kirim ke WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
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

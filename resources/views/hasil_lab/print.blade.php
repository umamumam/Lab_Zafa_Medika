<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Hasil Laboratorium</title>
    <style>
        @page {
            margin-top: 0.5cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 1cm;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1;
        }

        .kop-container {
            width: 100%;
            text-align: center;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .kop-image {
            width: 100%;
            max-height: 150px;
            object-fit: contain;
            display: block;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 5px;
        }

        .info-table {
            width: 100%;
        }

        .info-table th,
        .info-table td {
            border: none;
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }

        .info-table th {
            width: 30%;
        }

        .result-table {
            margin-top: 15px;
        }

        .result-table th,
        .result-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .title {
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .barcode-container {
            position: relative;
            width: 100%;
            text-align: center;
        }

        .barcode-adjustable {
            display: inline-block;
            position: relative;
            left: 0;
            transform: translateX(0);
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <!-- Ganti seluruh bagian header dengan kop image -->
    <div class="kop-container">
        <img src="{{ public_path('kopp.png') }}" class="kop-image">
    </div>
    <table class="info-table">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>No Lab</th>
                        <td>: {{ $visit->no_order }}</td>
                    </tr>
                    <tr>
                        <th>Nomor RM</th>
                        <td>: {{ $visit->pasien->norm }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td>: {{ $visit->pasien->nama }} ({{ $visit->pasien->jenis_kelamin == 'Laki - Laki' ? 'L' : 'P'
                            }})</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>: {{ \Carbon\Carbon::parse($visit->pasien->tgl_lahir)->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: {{ $visit->jenis_pasien }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>Registered</th>
                        <td>: {{ $visit->tgl_order->format('Y-m-d - H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Dokter</th>
                        <td>: {{ $visit->dokter->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fasyankes</th>
                        <td>: {{ $visit->ruangan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Diagnosa</th>
                        <td>: {{ $visit->diagnosa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>: {{ $visit->jenis_order ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>
    <div class="title">HASIL LABORATORIUM</div>

    <table class="result-table">
        <thead>
            <tr>
                <th style="text-align: left;">Parameter</th>
                <th>Flag</th>
                <th>Hasil</th>
                <th>Nilai Rujukan</th>
                <th>Satuan</th>
                {{-- <th>Metode</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($visit->visitTests as $vt)
            @foreach($vt->hasilLabs->whereNull('detail_test_id') as $hasil)
            <tr>
                <td style="text-align: left;"><strong>{{ $vt->test->nama }}</strong></td>
                <td>{{ $hasil->flag }}</td>
                <td>{{ $hasil->hasil }}</td>
                <td>{{ $vt->test->nilai_normal }}</td>
                <td>{{ $vt->test->satuan }}</td>
                {{-- <td>{{ $vt->test->metode }}</td> --}}
            </tr>
            @endforeach

            @foreach($vt->test->detailTests as $dt)
            @php
            $hasilDetail = $vt->hasilLabs->where('detail_test_id', $dt->id)->first();
            @endphp
            @if($hasilDetail)
            <tr>
                <td style="padding-left: 20px; text-align: left;">{{ $dt->nama }}</td>
                <td>{{ $hasilDetail->flag }}</td>
                <td>{{ $hasilDetail->hasil }}</td>
                <td>{{ $dt->nilai_normal }}</td>
                <td>{{ $dt->satuan }}</td>
                {{-- <td>{{ $vt->test->metode }}</td> --}}
            </tr>
            @endif
            @endforeach
            @endforeach
        </tbody>
    </table>
    <br>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 2px 0; vertical-align: top; width: 20%;">Verifikator</td>
            <td style="padding: 2px 0;">: 1. Allyssa Dwi Wasilah, S.Tr. Kes | 2. {{ $verifikator }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Jam Order</td>
            <td style="padding: 2px 0;">: {{ $visit->tgl_order->format('d-m-Y - H:i:s') }} | {{ $visit->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Jam Sampling</td>
            <td style="padding: 2px 0;">: {{ $jamSampling }} | {{ $verifikator }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Jam Selesai</td>
            <td style="padding: 2px 0;">: {{ $jamSelesai }} | {{ $verifikator }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Kesan</td>
            <td style="padding: 2px 0;">: {{ $visit->kesan }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Keterangan Hasil</td>
            <td style="padding: 2px 0;">: {{ $visit->catatan }}</td>
        </tr>
        <tr>
            <td style="padding: 2px 0; vertical-align: top;">Keterangan Tanda</td>
            <td style="padding: 2px 0;">
                : H : hasil pemeriksaan di atas nilai normal.<br>
                &nbsp;&nbsp;L : hasil pemeriksaan di bawah nilai normal.<br>
                &nbsp;&nbsp; * : hasil pemeriksaan abnormal.
            </td>
        </tr>
    </table>

    <table class="signature-table" style="width: 100%;">
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: top;">
                &nbsp;
            </td>

            <td style="width: 33%; text-align: center; vertical-align: top;">
                &nbsp;
            </td>

            <td style="width: 33%; text-align: center; vertical-align: top;">
                <p>Banjarbaru, {{ $tanggalValidasi }}</p>
                <label>Validator</label>
                <div class="barcode-container">
                    <div class="barcode-adjustable" style="left: 3px;">
                        {!! $barcode !!}
                    </div>
                </div>
                <label><strong>{{ $verifikator }}</strong></label>
            </td>
        </tr>
    </table>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Record Pemeriksaan</title>
    <style>
        @page {
            margin: 0.5cm 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header img {
            max-height: 135px;
        }

        .patient-info {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .patient-info strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 11px;
        }


        .barcode-container {
            text-align: center;
            width: 150px;
        }

        .barcode-wrapper {
            max-height: 60px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .norm-barcode {
            font-size: 12px;
            letter-spacing: 2px;
        }
        .patient-container table,
        .patient-container td,
        .patient-container tr {
            border: none;
            border-collapse: collapse;
        }

        .patient-container td {
            padding: 2px 4px;
            font-size: 12px;
            line-height: 1.2;
        }

        .patient-container .label-cell {
            width: 1%;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('kopp.png') }}" alt="Klinik Logo">
        <h3>RECORD PEMERIKSAAN</h3>
    </div>

    <div class="patient-container">
        <style>
            .patient-container table,
            .patient-container td,
            .patient-container tr {
                border: none;
                border-collapse: collapse;
            }
        </style>
        <table style="width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
            <tr>
                <td style="vertical-align: top; width: 70%;">
                    <table style="width: 100%;">
                        <tr>
                            <td><strong>Rekam Medik</strong></td>
                            <td>: {{ $pasien->norm }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pasien</strong></td>
                            <td>: {{ strtoupper($pasien->nama) }} ({{ $pasien->jenis_kelamin == 'Laki - Laki' ? 'L' :
                                'P' }})</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Lahir</strong></td>
                            <td>: {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}</td>
                        </tr>
                        {{-- <tr>
                            <td><strong>Status Pasien</strong></td>
                            <td>: {{ explode(' / ', $pasien->status_pasien)[0] }}</td>
                        </tr> --}}
                        <tr>
                            <td><strong>Registered</strong></td>
                            <td>: {{ $pasien->created_at->format('Y-m-d') }}</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: right; vertical-align: top; width: 30%;">
                    <div class="barcode-container">
                        <div class="barcode-wrapper">
                            {!! $barcode !!}
                        </div>
                        <div class="norm-barcode">{{ $pasien->norm }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Ruangan</th>
                <th>Dokter</th>
                <th>Parameter</th>
                <th>Hasil (satuan)</th>
                <th>Diagnosa</th>
                <th>Ket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visits as $visit)
            @foreach($visit->visitTests as $vt)
            @php
            $mainResult = $vt->hasilLabs->whereNull('detail_test_id')->first();
            $detailResults = $vt->hasilLabs->whereNotNull('detail_test_id');
            @endphp

            <!-- Main Test Result -->
            <tr>
                <td>{{ $visit->tgl_order->format('Y-m-d') }}</td>
                <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                <td>{{ $visit->dokter->nama ?? '-' }}</td>
                <td>{{ $vt->test->nama }}</td>
                <td>
                    @if($mainResult)
                    {{ $mainResult->hasil }} ({{ $vt->test->satuan }})
                    @else
                    -
                    @endif
                </td>
                <td>{{ $visit->diagnosa ?? '-' }}</td>
                <td>{{ $visit->jenis_order ?? 'Reguler' }}</td>
            </tr>

            <!-- Detail Test Results -->
            @foreach($detailResults as $detail)
            <tr>
                <td>{{ $visit->tgl_order->format('Y-m-d') }}</td>
                <td>{{ $visit->ruangan->nama ?? '-' }}</td>
                <td>{{ $visit->dokter->nama ?? '-' }}</td>
                <td>&nbsp; &nbsp;{{ $detail->detailTest->nama ?? $vt->test->nama }}</td>
                <td>
                    {{ $detail->hasil }}
                    @if($detail->detailTest)
                    ({{ $detail->detailTest->satuan }})
                    @else
                    ({{ $vt->test->satuan }})
                    @endif
                </td>
                <td>{{ $visit->diagnosa ?? '-' }}</td>
                <td>{{ $visit->jenis_order ?? 'Reguler' }}</td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $tanggalCetak }}
    </div>
</body>

</html>

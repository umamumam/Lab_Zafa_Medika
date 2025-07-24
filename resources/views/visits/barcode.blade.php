<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 250px;
        }

        .label-container {
            border: 1px solid #000;
            margin: 5px;
            padding: 5px;
            width: 245px;
            text-align: center;
            page-break-after: always;
        }

        .label-container:last-child {
            page-break-after: auto;
        }

        .no-order {
            font-size: 11px;
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-number {
            font-weight: bold;
        }

        .sample-type {
            font-size: 10px;
            font-weight: bold;
        }

        .barcode {
            margin: 5px 12px;
            width: fit-content;
        }

        .info-text {
            font-size: 11px;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        $sampleNames = [
            'Whole Blood EDTA' => 'EDTA',
            'Serum' => 'SERUM',
            'Plasma Citrat' => 'CITRAT',
            'Urin' => 'URIN',
        ];

        $samples = $visit->visitTests->map(function($vt) {
            return $vt->test->jenis_sampel;
        })->unique()->values()->all();
    @endphp

    @foreach($samples as $sample)
    <div class="label-container">
        <div class="info-text">
            {{ strtoupper($visit->pasien->nama) }}
            ({{ ($visit->pasien->jenis_kelamin) == 'Laki - Laki' ? 'L' : 'P' }})
            RM: {{ $visit->pasien->norm }}
        </div>

        <div class="barcode">
            {!! $barcode !!}
        </div>

        <div class="no-order">
            <span class="order-number">{{ $visit->no_order }}</span> &nbsp;
            <span class="sample-type">{{ $sampleNames[$sample] ?? $sample }}</span>
        </div>
    </div>
    @endforeach
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Pasien</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            font-family: sans-serif;
        }
        .card {
            width: 255.1pt; /* 85mm */
            height: 153.1pt; /* 54mm */
            position: relative;
            background-image: url('{{ public_path("kartu.png") }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .logo {
            position: absolute;
            top: 10pt;
            left: 5pt;
            width: 85pt;
            height: auto;
        }
        .barcode-container {
            position: absolute;
            top: 58pt;
            left: 19pt;
            width: 65pt;
            height: 88pt;
            padding: 2pt;
        }
        .barcode-container .barcode {
            width: 100%;
            height: auto;
        }
        .info {
            position: absolute;
            top: 60pt;
            right: 1pt;
            font-size: 12pt;
            width: 160pt;
            text-align: left;
            line-height: 1.5;
        }
        .info strong {
            font-size: 12pt;
        }
        .label-website {
            position: absolute;
            bottom: 6pt;
            right: 10pt;
            font-size: 10pt;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ public_path('Logo2.png') }}" class="logo" alt="Logo">
        <div class="barcode-container">
            <div class="barcode">
                {!! $barcode !!}
            </div>
        </div>
        <div class="info">
            <strong>{{ $pasien->nama }} ({{ $pasien->jenis_kelamin == 'Laki - Laki' ? 'L' : 'P' }})</strong><br>
            No RM: {{ $pasien->norm }}<br>
            Tgl Lahir: {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') }}
        </div>
        <div class="label-website">www.zafamedika.com</div>
    </div>
</body>
</html>

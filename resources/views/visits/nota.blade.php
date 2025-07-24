<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nota Pemeriksaan - {{ $visit->no_order }}</title>
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

        .pemeriksaan-table {
            margin-top: 15px;
            width: 100%;
        }

        .pemeriksaan-table th,
        .pemeriksaan-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .pemeriksaan-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .total-table {
            width: 100%;
            margin-top: 15px;
        }

        .total-table td {
            padding: 6px;
            border: none;
        }

        .payment-table {
            width: 100%;
            margin-top: 10px;
        }

        .payment-table th,
        .payment-table td {
            padding: 6px;
            border: none;
        }

        .title {
            text-align: center;
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            text-align: left;
            font-size: 11px;
        }

        .terbilang {
            font-style: italic;
            margin: 10px 0;
            padding: 5px;
            border: 1px dashed #999;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Kop Surat -->
    <div class="kop-container">
        <img src="{{ public_path('kopp.png') }}" class="kop-image">
    </div>

    <div class="title">NOTA / KWITANSI</div>
    <div class="divider"></div>

    <!-- Info Pasien -->
    <table class="info-table">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>No Lab</th>
                        <td>: {{ $visit->no_order }}</td>
                    </tr>
                    <tr>
                        <th>Rekam Medik</th>
                        <td>: {{ $visit->pasien->norm }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pasien</th>
                        <td>: {{ $visit->pasien->nama }} ({{ $visit->pasien->jenis_kelamin == 'Laki - Laki' ? 'L' : 'P'
                            }})</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>: {{ \Carbon\Carbon::parse($visit->pasien->tgl_lahir)->format('d-m-Y') }} ({{
                            \Carbon\Carbon::parse($visit->pasien->tgl_lahir)->age }} Thn)</td>
                    </tr>
                    <tr>
                        <th>Status Pasien</th>
                        <td>: {{ $visit->jenis_pasien }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>Dokter</th>
                        <td>: {{ $visit->dokter->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Fasyankes</th>
                        <td>: {{ $visit->ruangan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Registered</th>
                        <td>: {{ $visit->tgl_order->format('Y-m-d / H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>: {{ $visit->jenis_order == 'Reguler' ? 'Regular' : $visit->jenis_order }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Daftar Pemeriksaan -->
    <table class="pemeriksaan-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="35%">Pemeriksaan</th>
                <th width="22%">Grup</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Harga</th>
                <th width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visit->visitTests as $vt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $vt->test->nama }}</td>
                <td>{{ $vt->test->grup_test ?? '-' }}</td>
                <td class="text-center">{{ $vt->jumlah }}</td>
                <td class="text-right">Rp. {{ number_format($vt->harga, 0, ',', '.') }},-</td>
                <td class="text-right">Rp. {{ number_format($vt->subtotal, 0, ',', '.') }},-</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Pembayaran -->
    <table class="total-table">
        <tr>
            <td width="70%"></td>
            <td width="15%">Sub Total</td>
            <td width="15%" class="text-right">Rp. {{ number_format($subtotal, 0, ',', '.') }},-</td>
        </tr>
        @if($diskon > 0)
        <tr>
            <td></td>
            <td>Diskon</td>
            <td class="text-right">- Rp. {{ number_format($diskon, 0, ',', '.') }},-</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td><strong>Total Tagihan</strong></td>
            <td class="text-right"><strong>Rp. {{ number_format($total, 0, ',', '.') }},-</strong></td>
        </tr>
    </table>

    <!-- Terbilang -->
    <div class="terbilang">
        <strong>Terbilang :</strong> {{ ucwords(Terbilang::make($total)) }} Rupiah
    </div>

    <!-- Pembayaran -->
    <table class="payment-table">
        <tr>
            <td width="70%"></td>
            <td width="15%">Pembayaran</td>
            <td width="15%" class="text-right">
                Rp. {{ number_format($visit->dibayar, 0, ',', '.') }},-
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                @if ($visit->dibayar < $total) <span style="color:red;">Kurang</span>
                    @else
                    Sisa
                    @endif
            </td>
            <td class="text-right">
                Rp. {{ number_format($total - $visit->dibayar, 0, ',', '.') }},-
            </td>
        </tr>
    </table>
    <div class="footer">
        <div><em>*Keterangan : Segala jenis pembayaran hanya dilakukan di kasir Laboratorium Zafa Medika*</em></div>
        <div><em>*Pembelian yang sudah terinput pada sistem tidak dapat dibatalkan.*</em></div>
    </div>
    <table class="signature-table" style="width: 100%; border-collapse: collapse; margin-top: 30px;">
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: top; padding-top: 50px;">
                &nbsp;
            </td>
            <td style="width: 33%; text-align: center; vertical-align: top; padding-top: 50px;">
                &nbsp;
            </td>
            <td style="width: 33%; text-align: center; vertical-align: top; padding-top: 50px;">
                <p>Banjarbaru Utara, {{ $tanggalValidasi }}</p>
                <p>Petugas,</p>
                @if ($visit->penerimaan)
                <div style="margin: 0 50px; text-align: center; padding: 0 30px;">
                    {!! $barcode !!}
                </div>
                @endif
                <div style="margin-top: 5px;">
                    <strong>{{ $verifikator }}</strong>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>

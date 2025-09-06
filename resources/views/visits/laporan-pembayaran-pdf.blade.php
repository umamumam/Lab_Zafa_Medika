<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-end {
            text-align: right;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Laporan Pembayaran</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Lab</th>
                <th>Nama Pasien</th>
                <th>Tanggal Order</th>
                <th class="text-end">Total</th>
                <th class="text-end">Tagihan Dibayar</th>
                <th class="text-end">Sisa</th>
                <th>Metode Bayar</th>
                <th>Tgl Klaim</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($visits as $visit)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $visit->no_order }}</td>
                <td>{{ $visit->pasien->nama ?? 'N/A' }}</td>
                <td>{{ $visit->tgl_order->format('d/m/Y H:i') }}</td>
                <td class="text-end">Rp {{ number_format($visit->total_tagihan, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($visit->dibayar, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($visit->jenis_pasien == 'BPJS' ? 0 : ($visit->total_tagihan - $visit->dibayar), 0, ',', '.') }}</td>
                <td>{{ str_replace('BPJS', 'BPJS-K', $visit->penerimaan->metodeBayar->nama ?? '-') }}</td>
                <td>{{ \Carbon\Carbon::parse($visit->penerimaan->created_at)->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $visit->penerimaan->user->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

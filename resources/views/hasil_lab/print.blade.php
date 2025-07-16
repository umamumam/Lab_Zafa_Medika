<h3>Hasil Laboratorium - {{ $visit->no_order }}</h3>
    <p>Nama Pasien: {{ $visit->pasien->nama }}</p>
    <p>Tanggal Order: {{ $visit->tgl_order->format('Y-m-d H:i') }}</p>

    <hr>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Hasil</th>
                <th>Satuan</th>
                <th>Nilai Rujukan</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visit->visitTests as $vt)
                @foreach($vt->hasilLabs->whereNull('detail_test_id') as $hasil)
                    <tr>
                        <td><strong>{{ $vt->test->nama }}</strong></td>
                        <td>{{ $hasil->hasil }}</td>
                        <td>{{ $vt->test->satuan }}</td>
                        <td>{{ $vt->test->nilai_normal }}</td>
                        <td>{{ $vt->test->metode }}</td>
                    </tr>
                @endforeach

                {{-- Detail Test --}}
                @foreach($vt->test->detailTests as $dt)
                    @php
                        $hasilDetail = $vt->hasilLabs->where('detail_test_id', $dt->id)->first();
                    @endphp
                    @if($hasilDetail)
                    <tr>
                        <td style="padding-left: 20px;">{{ $dt->nama }}</td>
                        <td>{{ $hasilDetail->hasil }}</td>
                        <td>{{ $dt->satuan }}</td>
                        <td>{{ $dt->nilai_normal }}</td>
                        <td>{{ $vt->test->metode }}</td>
                    </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

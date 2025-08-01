@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Laporan Tahunan Pemeriksaan Laboratorium - {{ $tahun }}</h4>
                <div>
                    <a href="{{ route('visits.export.tahunan.excel', ['tahun' => $tahun]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            <hr class="mt-0 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <form method="GET" action="{{ route('visits.laporan.tahunan') }}" class="form-inline d-flex">
                        <select name="tahun" class="form-control me-2">
                            @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ $tahun==$year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary"
                            style="height: 38px; padding: 0 12px; font-size: 12px;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </form>
                </div>
                <div style="max-width: 250px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama pemeriksaan...">
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover" style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>NAMA PEMERIKSAAN</th>
                            <th>JAN</th>
                            <th>FEB</th>
                            <th>MAR</th>
                            <th>APR</th>
                            <th>MEI</th>
                            <th>JUN</th>
                            <th>JUL</th>
                            <th>AGS</th>
                            <th>SEP</th>
                            <th>OKT</th>
                            <th>NOV</th>
                            <th>DES</th>
                        </tr>
                    </thead>
                    <tbody id="dataRows">
                        @php $currentGroup = null; @endphp
                        @foreach($laporan as $item)
                        @if($item['is_separator'])
                        <tr class="group-separator" data-grup="{{ $item['grup'] }}">
                            <td colspan="13"><strong>{{ $item['grup'] }}</strong></td>
                        </tr>
                        @else
                        <tr class="data-row" data-nama="{{ $item['nama'] }}" data-grup="{{ $item['grup'] }}">
                            <td>{{ $item['nama'] }}</td>
                            @for($bulan = 1; $bulan <= 12; $bulan++) <td>{{ $item['per_bulan'][$bulan] }}</td>
                                @endfor
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .group-separator {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .group-separator td {
        padding: 8px;
        border-top: 2px solid #ddd;
        border-bottom: 2px solid #ddd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const dataRows = document.getElementById('dataRows').querySelectorAll('tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        dataRows.forEach(row => {
            if (row.classList.contains('group-separator')) {
                const grupName = row.getAttribute('data-grup').toLowerCase();
                const nextRows = getNextRowsUntilNextSeparator(row);

                if (grupName.includes(searchTerm)) {
                    row.style.display = '';
                    nextRows.forEach(r => r.style.display = '');
                } else {
                    const hasVisibleChild = Array.from(nextRows).some(r => {
                        const nama = r.getAttribute('data-nama').toLowerCase();
                        return nama.includes(searchTerm);
                    });

                    if (hasVisibleChild) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            } else if (row.classList.contains('data-row')) {
                const nama = row.getAttribute('data-nama').toLowerCase();
                if (nama.includes(searchTerm)) {
                    row.style.display = '';
                    const separator = findParentSeparator(row);
                    if (separator) separator.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    function getNextRowsUntilNextSeparator(row) {
        const rows = [];
        let nextRow = row.nextElementSibling;

        while (nextRow && !nextRow.classList.contains('group-separator')) {
            rows.push(nextRow);
            nextRow = nextRow.nextElementSibling;
        }

        return rows;
    }

    function findParentSeparator(row) {
        let prevRow = row.previousElementSibling;

        while (prevRow) {
            if (prevRow.classList.contains('group-separator')) {
                return prevRow;
            }
            prevRow = prevRow.previousElementSibling;
        }

        return null;
    }
});
</script>
@endsection

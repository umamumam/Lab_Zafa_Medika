@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Detail Test</h4>
            <a href="{{ route('detail_tests.create') }}" class="btn btn-primary btn-round ms-auto">
                <i class="fas fa-plus"></i> Tambah Detail Test
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <div class="mb-3">
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="Cari kode / pemeriksaan / grup / sub grup">
                </div>

                <table id="basic-datatables" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Pemeriksaan</th>
                            <th>Grup</th>
                            <th>Sub Grup</th>
                            <th>Jumlah Detail</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no = 1;
                        // Group details by test_id
                        $groupedTests = $detailTests->groupBy('test_id');
                        @endphp

                        @foreach($groupedTests as $testId => $details)
                        @php $test = $details->first()->test; @endphp
                        <tr class="test-row" data-test-id="{{ $testId }}">
                            <td>{{ $no++ }}</td>
                            <td>{{ $test->kode }}</td>
                            <td>{{ $test->nama }}</td>
                            <td>{{ $test->grupTest->nama }}</td>
                            <td>{{ $test->sub_grup }}</td>
                            <td>{{ $details->count() }}</td>
                            <td style="text-align: center">
                                <button class="btn btn-sm btn-info toggle-details" data-test-id="{{ $testId }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                {{-- <a href="{{ route('detail_tests.create') }}?test_id={{ $testId }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> Tambah
                                </a> --}}
                            </td>
                        </tr>
                        <tr class="detail-row" id="details-{{ $testId }}" style="display: none;">
                            <td colspan="7">
                                <table class="table table-bordered sub-table">
                                    <thead>
                                        <tr>
                                            <th>Urutan</th>
                                            <th>Detail Pemeriksaan</th>
                                            <th>Nilai Rujukan</th>
                                            <th>Satuan</th>
                                            <th>Min</th>
                                            <th>Max</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details->sortBy('urutan') as $detail)
                                        <tr>
                                            <td>{{ $detail->urutan }}</td>
                                            <td>{{ $detail->nama }}</td>
                                            <td>{{ $detail->nilai_normal }}</td>
                                            <td>{{ $detail->satuan }}</td>
                                            <td>
                                                @if($detail->normalValues->first())
                                                {{ $detail->normalValues->first()->min !== null ?
                                                (int)$detail->normalValues->first()->min : '' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($detail->normalValues->first())
                                                {{ $detail->normalValues->first()->max !== null ?
                                                (int)$detail->normalValues->first()->max : '' }}
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $detail->status == 'Aktif' ? 'success' : 'danger' }}">
                                                    {{ $detail->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('detail_tests.edit', $detail->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('detail_tests.destroy', $detail->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus detail test ini?')">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="pagination" class="mt-3 d-flex justify-content-center"></div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.toggle-details');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const testId = this.getAttribute('data-test-id');
                const detailRow = document.getElementById(`details-${testId}`);
                const isVisible = detailRow.style.display === 'table-row';
                detailRow.style.display = isVisible ? 'none' : 'table-row';
                this.classList.toggle('active');

                if (!isVisible) {
                    document.querySelectorAll('.detail-row').forEach(row => {
                        if (row.id !== `details-${testId}`) {
                            row.style.display = 'none';
                            const otherButton = row.previousElementSibling.querySelector('.toggle-details');
                            if (otherButton) otherButton.classList.remove('active');
                        }
                    });
                }
            });
        });

        // ============================
        // Filter + Pagination
        // ============================
        const rows = Array.from(document.querySelectorAll('.test-row'));
        const detailRows = document.querySelectorAll('.detail-row');
        const searchInput = document.getElementById('searchInput');
        const pagination = document.getElementById('pagination');
        const rowsPerPage = 10;
        let currentPage = 1;

        function displayRows() {
            const filterText = searchInput.value.toLowerCase();
            let filteredRows = rows.filter(row => {
                return row.innerText.toLowerCase().includes(filterText);
            });
            rows.forEach(row => row.style.display = 'none');
            detailRows.forEach(row => row.style.display = 'none');
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            currentPage = Math.min(currentPage, totalPages || 1);
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            filteredRows.slice(start, end).forEach(row => {
                row.style.display = '';
            });
            renderPagination(totalPages);
        }
        function renderPagination(totalPages) {
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = `btn btn-sm me-1 ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    displayRows();
                });
                pagination.appendChild(btn);
            }
        }
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            displayRows();
        });

        displayRows();
    });
</script>

@endsection

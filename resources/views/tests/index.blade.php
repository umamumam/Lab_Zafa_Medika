@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Test</h4>
            <a href="{{ route('tests.create') }}" class="btn btn-primary btn-round ms-auto">
                <i class="fas fa-plus"></i> Tambah Test
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover dt-responsive nowrap"
                    style="width: 100%">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Test</th>
                            <th>Metode</th>
                            <th>Nilai Rujukan</th>
                            <th>Satuan</th>
                            <th>Harga Umum</th>
                            <th>Harga BPJS</th>
                            <th>Grup</th>
                            <th>Sub Grup</th>
                            <th>Jenis Sampel</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($tests as $test)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $test->kode }}</td>
                            <td>{{ $test->nama }}</td>
                            <td>{{ $test->metode }}</td>
                            <td>{{ $test->nilai_normal }}</td>
                            <td>{{ $test->satuan }}</td>
                            <td>{{ number_format($test->harga_umum, 0, ',', '.') }}</td>
                            <td>{{ number_format($test->harga_bpjs, 0, ',', '.') }}</td>
                            <td>{{ $test->grup_test }}</td>
                            <td>{{ $test->sub_grup }}</td>
                            <td>{{ $test->jenis_sampel }}</td>
                            <td>
                                <span class="badge badge-{{ $test->status == 'Aktif' ? 'success' : 'danger' }}">
                                    {{ $test->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('tests.edit', $test->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('tests.destroy', $test->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus test ini?')">
                                        <i class="fa fa-trash-alt"></i>
                                    </button>
                                </form>
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

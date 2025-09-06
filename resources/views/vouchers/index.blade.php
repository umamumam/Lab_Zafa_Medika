@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4>Data Voucher</h4>
            <a href="{{ route('vouchers.create') }}" class="btn btn-primary btn-round ms-auto">
                <i class="fas fa-plus"></i> Tambah Voucher
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
                            <th>Kode Voucher</th>
                            <th>Nama Voucher</th>
                            <th>Nilai Voucher</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($vouchers as $voucher)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $voucher->kode }}</td>
                            <td>{{ $voucher->nama }}</td>
                            @if ($voucher->tipe === 'persen')
                            <td>{{ $voucher->value }}%</td>
                            @elseif ($voucher->tipe === 'nominal')
                            <td>Rp {{ number_format($voucher->value, 0, ',', '.') }}</td>
                            @else
                            <td>{{ $voucher->value }}</td>
                            @endif
                            <td>
                                <span class="badge badge-{{ $voucher->status == 'Aktif' ? 'success' : 'danger' }}">
                                    {{ $voucher->status }}
                                </span>
                            </td>
                            <td>{{ $voucher->keterangan }}</td>
                            <td>
                                <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus voucher ini?')">
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

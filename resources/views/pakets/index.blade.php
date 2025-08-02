@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="card-title">Data Paket Pemeriksaan</div>
                    <a href="{{ route('pakets.create') }}" class="btn btn-primary btn-round">
                        <i class="fa fa-plus"></i> Tambah Paket
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Paket</th>
                                    <th>Harga Umum</th>
                                    <th>Harga BPJS</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pakets as $paket)
                                <tr>
                                    <td>{{ $paket->kode }}</td>
                                    <td>{{ $paket->nama }}</td>
                                    <td>Rp {{ number_format($paket->harga_umum, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($paket->harga_bpjs, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $paket->status == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $paket->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pakets.show', $paket->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pakets.edit', $paket->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pakets.destroy', $paket->id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                <i class="fas fa-trash"></i>
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
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#basic-datatables').DataTable();

        $('.delete-button').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            if (confirm('Apakah Anda yakin ingin menghapus paket ini?')) {
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection

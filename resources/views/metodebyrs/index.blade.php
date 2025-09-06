@extends('layouts1.app')

@section('content')

<div class="page-inner">
    <div class="row">
        <!-- Metodebyr table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4>Data Metode Pembayaran</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">
                            <i class="fa fa-plus"></i> <span class="d-none d-sm-inline">Tambah Metode Pembayaran</span>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="overflow-x:auto;">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table id="basic-datatables" class="display table table-striped table-hover" style="width: 100%">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Metode Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($metodebyrs as $metodebyr)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $metodebyr->nama }}</td>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center" style="gap: 5px;">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $metodebyr->id }}">
                                            <i class="fa fa-pencil-alt"></i>
                                        </button>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('metodebyrs.destroy', $metodebyr->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Hapus data metode pembayaran ini?')"
                                                class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $metodebyr->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('metodebyrs.update', $metodebyr->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Metode Pembayaran</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label>Nama Metode Pembayaran</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="{{ $metodebyr->nama }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('metodebyrs.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Metode Pembayaran</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

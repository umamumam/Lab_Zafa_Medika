@extends('layouts1.app')

@section('content')

<div class="page-inner">
    <div class="row">
        <!-- Grup Test table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4>Data Grup Test</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">
                            <i class="fa fa-plus"></i> <span class="d-none d-sm-inline">Tambah Grup Test</span>
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
                                <th>Nama Grup Test</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($grupTests as $grupTest)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $grupTest->nama }}</td>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center" style="gap: 5px;">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $grupTest->id }}">
                                            <i class="fa fa-pencil-alt"></i>
                                        </button>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('grup_tests.destroy', $grupTest->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Hapus data grup test ini?')"
                                                class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $grupTest->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('grup_tests.update', $grupTest->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Grup Test</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label>Nama Grup Test</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="{{ $grupTest->nama }}" required>
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
        <form action="{{ route('grup_tests.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Grup Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Grup Test</label>
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

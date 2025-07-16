@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row">
        <!-- Ruangan table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4>Data Ruangan</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">
                            <i class="fa fa-plus"></i> <span class="d-none d-sm-inline">Tambah Ruangan</span>
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

                    <table id="basic-datatables" class="display table table-striped table-hover dt-responsive nowrap"
                        style="width: 100%">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Ruangan</th>
                                <th>Penanggung Jawab</th>
                                <th>Kontak</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($ruangans as $ruangan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $ruangan->kode }}</td>
                                <td>{{ $ruangan->nama }}</td>
                                <td>{{ $ruangan->dokter->nama }}</td>
                                <td>{{ $ruangan->kontak ?? '-' }}</td>
                                <td>{{ $ruangan->keterangan ?? '-' }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $ruangan->id }}">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                    <!-- Form Hapus -->
                                    <form action="{{ route('ruangans.destroy', $ruangan->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus data ruangan ini?')"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $ruangan->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('ruangans.update', $ruangan->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Ruangan</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label>Kode Ruangan</label>
                                                    <input type="text" class="form-control" value="{{ $ruangan->kode }}"
                                                        disabled>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Nama Ruangan</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="{{ $ruangan->nama }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Penanggung Jawab</label>
                                                    <select name="dokter_id" class="form-select" required>
                                                        @foreach($dokters as $dokter)
                                                        <option value="{{ $dokter->id }}" {{ $ruangan->dokter_id ==
                                                            $dokter->id ? 'selected' : '' }}>
                                                            {{ $dokter->nama }} ({{ $dokter->spesialis }})
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Kontak</label>
                                                    <input type="text" name="kontak" class="form-control"
                                                        value="{{ $ruangan->kontak }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label>Keterangan</label>
                                                    <textarea name="keterangan"
                                                        class="form-control">{{ $ruangan->keterangan }}</textarea>
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
        <form action="{{ route('ruangans.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Ruangan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Penanggung Jawab</label>
                        <select name="dokter_id" class="form-select" required>
                            <option value="">Pilih Dokter</option>
                            @foreach($dokters as $dokter)
                            <option value="{{ $dokter->id }}">{{ $dokter->nama }} ({{ $dokter->spesialis }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Kontak</label>
                        <input type="text" name="kontak" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
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

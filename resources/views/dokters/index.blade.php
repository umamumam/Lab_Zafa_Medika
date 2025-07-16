@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="row">
        <!-- Dokter table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4>Data Dokter</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                            data-bs-target="#tambahModal">
                            <i class="fa fa-plus"></i> <span class="d-none d-sm-inline">Tambah Dokter</span>
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
                                <th>Nama Dokter</th>
                                <th>Spesialis</th>
                                <th>Gender</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($dokters as $dokter)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $dokter->kode }}</td>
                                <td>{{ $dokter->nama }}</td>
                                <td>{{ $dokter->spesialis }}</td>
                                <td>{{ $dokter->jenis_kelamin }}</td>
                                <td>{{ $dokter->no_hp }}</td>
                                <td>{{ $dokter->alamat }}</td>
                                <td>
                                    <span class="badge bg-{{ $dokter->status == 'Aktif' ? 'success' : 'danger' }}">
                                        {{ $dokter->status }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $dokter->id }}">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                    <!-- Form Hapus -->
                                    <form action="{{ route('dokters.destroy', $dokter->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus data dokter ini?')"
                                            class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $dokter->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('dokters.update', $dokter->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5>Edit Dokter</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label>Kode Dokter</label>
                                                    <input type="text" class="form-control" value="{{ $dokter->kode }}"
                                                        disabled>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Nama Dokter</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="{{ $dokter->nama }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Spesialis</label>
                                                    <input type="text" name="spesialis" class="form-control"
                                                        value="{{ $dokter->spesialis }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>No. HP</label>
                                                    <input type="text" name="no_hp" class="form-control"
                                                        value="{{ $dokter->no_hp }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Alamat</label>
                                                    <textarea name="alamat" class="form-control"
                                                        required>{{ $dokter->alamat }}</textarea>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Aktif" {{ $dokter->status == 'Aktif' ? 'selected'
                                                            : '' }}>Aktif</option>
                                                        <option value="Tidak Aktif" {{ $dokter->status == 'Tidak Aktif'
                                                            ? 'selected' : '' }}>Tidak Aktif</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label>Jenis Kelamin</label>
                                                    <select name="jenis_kelamin" class="form-select" required>
                                                        <option value="L" {{ $dokter->jenis_kelamin == 'L' ? 'selected'
                                                            : '' }}>Laki-laki</option>
                                                        <option value="P" {{ $dokter->jenis_kelamin == 'P' ? 'selected'
                                                            : '' }}>Perempuan</option>
                                                    </select>
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
        <form action="{{ route('dokters.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Dokter</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Spesialis</label>
                        <input type="text" name="spesialis" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
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

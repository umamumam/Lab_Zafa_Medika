@extends('layouts1.app')

@section('content')
<div class="page-inner">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4>Tambah Pasien Baru</h4>
        </div>
        <div class="card-body">
            <h5>Perhatian Sebelum Registrasi Rekam Medik Baru / Pasien Baru</h5>
            <ol class="mb-4">
                <li>Setiap Pasien yang datang, tanyakan "Apakah pernah terdaftar sebelumnya di Laboratorium Zafa
                    Medika?"
                </li>
                <li>Lakukan crosscheck kembali data Pasien pada Sistem LabKlin, dengan melakukan pencarian NIK atau Nama
                    Pasien pada halaman daftar pasien.</li>
                <li>Jika pasien sudah pernah terdaftar, periksa kembali kelengkapan data yang terinput, silahkan
                    lengkapi data jika belum lengkap.</li>
                <li>Jika pasien belum pernah terdaftar, lakukan pendaftaran dengan mengisi form registrasi dibawah ini.
                </li>
                <li>Nomor RM harus 6 digit, jika nomor RM sebelumnya kurang dari 6 digit, maka tambahkan 0 didepan.</li>
            </ol>
            <p class="text-muted">Langkah-langkah diatas dilakukan untuk menghindari double RM pada pasien dan
                melengkapi data pasien lama yang tidak lengkap.</p>
            <hr>
            <form action="{{ route('pasiens.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}" required>
                            @error('tgl_lahir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                                name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki - Laki" {{ old('jenis_kelamin')=='Laki - Laki' ? 'selected' : '' }}>
                                    Laki - Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                                name="nik" value="{{ old('nik') }}" maxlength="16">
                            <small class="text-muted">16 digit angka</small>
                            @error('nik')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_bpjs">Nomor BPJS / Asuransi Lain</label>
                            <input type="text" class="form-control @error('no_bpjs') is-invalid @enderror" id="no_bpjs"
                                name="no_bpjs" value="{{ old('no_bpjs') }}" maxlength="20">
                            @error('no_bpjs')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_hp">Nomor HP/WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                                name="no_hp" value="{{ old('no_hp') }}" maxlength="15" required>
                            @error('no_hp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat"
                        rows="3" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('pasiens.index') }}" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

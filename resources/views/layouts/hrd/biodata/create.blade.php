@extends('main')
@section('content')

    <div class="container-xxl">
        <div class="card mb-4">
        <h5 class="card-header">Create Biodata</h5>
            <div class="card-body">
                <form action="{{ route('biodata.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">User</label>
                            <select id="user_id" name="user_id" class="select2 form-select">
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                @php
                                    $roleName = $user->roles->first()->name ?? 'No Role';
                                    @endphp
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->id }} - {{ $user->name }} - {{ $roleName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label for="nik" class="form-label">Nomor KTP</label>
                            <input id="nik" type="text" name="nik" class="form-control" value="{{ old('nik') }}" minlength="3" required>
                            @error('nik')
                                <div class="text-danger text-sm ">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2 col-md-4">
                            <label for="nokk" class="form-label">Nomor Kartu Keluarga</label>
                            <input id="nokk" type="text" name="nokk" class="form-control" value="{{ old('nokk') }}" minlength="3" required>
                            @error('nokk')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-2 col-md-6">
                            <label class="form-label" for="kota_id">Tempat Lahir</label>
                            <select id="kota_id" name="kota_id" class="select2 form-select">
                                <option value="">{{ __('Select Kota') }}</option>
                                @foreach($kotas as $data)
                                    <option value="{{ $data->id }}" {{ old('kota_id') == $data->id ? 'selected' : '' }}>
                                        {{ $data->nama_kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input id="tgl_lahir" type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}" required>
                            @error('tgl_lahir')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-2 col-md-4">
                            <label class="form-label" for="nikah">Perkawinan</label>
                            <select id="nikah" name="nikah" class="select2 form-select" required>
                                <option value="Singel" {{ old('nikah') == 'Singel' ? 'selected' : '' }}>Singel</option>
                                <option value="Menikah" {{ old('nikah') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai" {{ old('nikah') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label" for="status">Agama</label>
                            <select id="agama" name="agama" class="select2 form-select" required>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Budha" {{ old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                            </select>
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label" for="jenis">Jenis</label>
                            <select id="jenis" name="jenis" class="select2 form-select" required>
                                <option value="Laki" {{ old('jenis') == 'Laki' ? 'selected' : '' }}>Laki</option>
                                <option value="Perempuan" {{ old('jenis') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-2 col-md-8">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}"/>
                        </div>
                        <div class="mb-2 col-md-2">
                            <label for="rt" class="form-label">RT</label>
                            <input type="text" class="form-control" id="rt" name="rt" value="{{ old('rt') }}" />
                        </div>
                        <div class="mb-2 col-md-2">
                            <label for="rw" class="form-label">RW</label>
                            <input type="text" class="form-control" id="rw" name="rw" value="{{ old('rw') }}"/>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="mb-2 col-md-6">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" name="action">Submit</button>
                        <a href="{{ route('biodata.index') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#user_id').select2();
                $('#kota_id').select2();


            });
        </script>
@endsection

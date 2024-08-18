@extends('main')
@section('content')

<div class="card">
    <div class="card-body">
        <h5 class="card-header">Karyawan</h5>
        <hr class="my-0" />
        <div class="card-body">
            <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="user_id" class="form-label">User</label>
                        <select id="user_id" name="user_id" class="select2 form-select" data-allow-clear="false">
                            <option value="">Pilih User</option>
                            @foreach ($users as $user)
                                @php
                                    $roleName = $user->roles->first()->name ?? 'No Role'; // Mengambil nama peran pertama atau 'No Role' jika tidak ada
                                @endphp
                                @if (!$user->hasRole('pengemudi') && !$user->hasRole('kondektur') && !$user->hasRole('super admin') && !$user->hasRole('admin'))
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->id }} - {{ $user->name }} - {{ $roleName }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="noinduk" class="form-label">Nomor Induk</label>
                        <input id="noinduk" name="noinduk" type="text"
                        value="{{ \App\Models\Hrd\Karyawan::generateNoinduk() }}" class="form-control">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="jabatan_id" class="form-label">Jabatan</label>
                        <select id="jabatan_id" name="jabatan_id" class="select2 form-select">
                            <option value="">{{ __('Select Jabatan') }}</option>
                            @foreach($jabatans as $data)
                                <option value="{{ $data->id }}" {{ old('jabatan_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="pool_id">Pool</label>
                        <select id="pool_id" name="pool_id" class="select2 form-select">
                            <option value="">{{ __('Select Pool') }}</option>
                            @foreach($pools as $data)
                                <option value="{{ $data->id }}" {{ old('pool_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama_pool }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tanggal_kp" class="form-label">Tanggal KP</label>
                        <input id="tanggal_kp" name="tanggal_kp" type="date" value="{{ old('tanggal_kp') }}" class="form-control" minlength="16" required @error('nik') is-invalid @enderror>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ old('tgl_masuk') }}" />
                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"/>
                    </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary" name="action">Submit</button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-warning">Kembali</a>
                </div>
            </form>
        </div>
        <!-- /Account -->
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
      $(document).ready(function () {
          $('#jabatan_id').select2();
          $('#pool_id').select2();

      });
  </script>
@endsection

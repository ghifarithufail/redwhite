@extends('main')
@section('content')

<div class="card">
    <div class="card-body">
        <h5 class="card-header">Kondektur</h5>
        <hr class="my-0" />
        <div class="card-body">
            <form action="{{ route('kondektur.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- <div class="mb-3 col-md-6">
                        <label for="user_id" class="form-label">User</label>
                        <select id="user_id" name="user_id" class="select2 form-select">
                          <option value="">Pilih User</option>
                          @foreach ($users as $user)
                            @php
                              $roleName = $user->roles->first()->name ?? 'No Role'; // Mengambil nama peran pertama atau 'No Role' jika tidak ada
                            @endphp
                            @if ($user->hasRole('Kondektur'))  <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->id }} - {{ $user->name }} - {{ $roleName }}
                              </option>
                            @endif
                          @endforeach
                        </select>
                      </div> --}}
                      <div class="mb-3 col-md-6">
                        <label for="user_id" class="form-label">User Kondektur</label>
                        <select id="user_id" name="user_id" class="select2 form-select" data-allow-clear="false">
                            <option value="">Pilih User</option>
                            @foreach ($users->filter(function($user) {
                                return $user->hasRole('Kondektur') && !empty($user->biodata->nik);
                            }) as $data)
                                <option value="{{ $data->id }}" {{ old('user_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->id }} - {{ $data->name }} - {{ $data->biodata->nik }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('user_id'))
                            <div class="text-danger">{{ $errors->first('user_id') }}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="nokondektur" class="form-label">Nomor Induk</label>
                        <input id="nokondektur" name="nokondektur" type="text"
                        value="{{ \App\Models\Hrd\Kondektur::generatenokondektur() }}" class="form-control">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="rute_id" class="form-label">Rute</label>
                        <select id="rute_id" name="rute_id" class="select2 form-select">
                            <option value="">Pilih Rute</option>
                            @if(isset($rutes) && count($rutes) > 0)
                                @foreach($rutes as $rute)
                                    @php
                                        $pool = $rute->poolS; // Asumsi relasi "pool" ada di model Rute
                                    @endphp
                                    <option value="{{ $rute->id }}"
                                            data-rute="{{ $rute->kode_route }}"
                                            data-pool="{{ $pool ? $pool->id : '' }}"
                                            {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                        {{ $rute->nama_rute }} - {{ $pool ? $pool->nama_pool : 'Pool Tidak Tersedia' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                      <div class="col-md-6">
                        <label class="form-label" for="pool_id">Pool</label>
                        <select id="pool_id" name="pool_id" class="select2 form-select">
                            <option value="">{{ __('Select Pool') }}</option>
                            @foreach($pools as $pool)
                                <option value="{{ $pool->id }}" {{ old('pool_id') == $pool->id ? 'selected' : '' }}>
                                    {{ $pool->nama_pool }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ old('tgl_masuk') }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tanggal_kp" class="form-label">Tanggal KP</label>
                        <input type="date" class="form-control" id="tanggal_kp" name="tanggal_kp" value="{{ old('tanggal_kp') }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="nojamsostek" class="form-label">Nomor Jamsostek</label>
                        <input type="text" class="form-control" id="nojamsostek" name="nojamsostek" value="{{ old('nojamsostek') }}"/>
                        @if ($errors->has('nojamsostek'))
                            <div class="text-danger">
                                {{ $errors->first('nojamsostek') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="select2 form-select" required>
                            <option value="[]">Select Status</option>
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>Disable</option>
                        </select>
                        @if ($errors->has('status'))
                            <div class="text-danger">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="ket_kondektur" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="ket_kondektur" name="ket_kondektur" value="{{ old('ket_kondektur') }}"/>
                    </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary" name="action">Submit</button>
                    <a href="{{ route('kondektur.index') }}" class="btn btn-warning">Kembali</a>
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
          $('#user_id').select2();
          $('#rute_id').select2();
          $('#pool_id').select2();

      });
  </script>
@endsection

@extends('main')
@section('content')
@if ($errors->has('nokondektur'))
    <div class="text-danger">
        {{ $errors->first('nokondektur') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
        <h5 class="card-header">Edit Kondektur</h5>
        <hr class="my-0" />
        <div class="card-body">
            <form action="{{ route('kondektur.update', $kondektur->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <input type="hidden" name="user_id" value="{{ $kondektur->user_id }}">
                    <div class="mb-3 col-md-6">
                        <label for="nokondektur" class="form-label">Nomor kondektur</label>
                        <input id="nokondektur" name="nokondektur" type="text" value="{{ old('nokondektur', $kondektur->nokondektur) }}" class="form-control">
                        @if ($errors->has('nokondektur'))
                            <div class="text-danger">
                                {{ $errors->first('nokondektur') }}
                            </div>
                        @endif
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
                                            {{ $kondektur->rute_id == $rute->id ? 'selected' : '' }}>
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
                                <option value="{{ $pool->id }}" {{ $kondektur->pool_id == $pool->id ? 'selected' : '' }}>
                                    {{ $pool->nama_pool }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ $kondektur->tgl_masuk }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="tanggal_kp" class="form-label">Tanggal KP</label>
                        <input type="date" class="form-control" id="tanggal_kp" name="tanggal_kp" value="{{ $kondektur->tanggal_kp }}" />
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="nojamsostek" class="form-label">Nomor Jamsostek</label>
                        <input type="text" class="form-control" id="nojamsostek" name="nojamsostek" value="{{ $kondektur->nojamsostek }}"/>
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
                            <option value="Active" {{ $kondektur->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $kondektur->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="Disable" {{ $kondektur->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                        </select>
                        @if ($errors->has('status'))
                            <div class="text-danger">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="ket_kondektur" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="ket_kondektur" name="ket_kondektur" value="{{ $kondektur->ket_kondektur }}"/>
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
          $('#rute_id').select2();
          $('#pool_id').select2();

      });
  </script>
@endsection

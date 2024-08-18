@extends('main')
@section('content')

<div class="container-xxl">
    <div class="card mb-4">
        <h5 class="card-header">Edit Kota</h5>
        <!-- Account -->
        <div class="card-body">
                <form action="{{ route('kota.update', $kota->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk armada -->
                        <div class="mb-3 col-md-6">
                            <label for="nama_kota" class="form-label">Nama kota</label>
                            <input id="nama_kota" type="text" name="nama_kota" class="form-control"
                                    value="{{ $kota->nama_kota }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="pool_id">Provinsi</label>
                            <select id="provinsi_id" name="provinsi_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Pool') }}</option>
                                    @foreach($provinsis as $data)
                                        <option value="{{ $data->id }}" {{ $kota->provinsi_id == $data->id ? 'selected' : '' }}>
                                            {{ $data->nama_provinsi }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('kota.index') }}" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

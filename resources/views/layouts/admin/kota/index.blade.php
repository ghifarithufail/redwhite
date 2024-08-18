@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('kota.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('kota.index') }}" class="m-0"><h5> Daftar kota</h5></a>
                <p class="m-0">Total : {{ App\Models\Admin\Kota::count() }} </p>
            </div>
            <div class="add-new-role">
                <button
                    class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#formkota" aria-controls="formkota">
                    + kota
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.85">
                <thead>
                    <tr>
                        <th>No.Urt</th>
                        <th>Nama Kota</th>
                        <th>Provinsi</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($kotas as $data)
                    <tr>
                        <td>{{ $kotas->firstItem() + $loop->index }}</td>
                        <td>{{ $data->nama_kota }}</td>
                        <td>{{ $data->provinsis->nama_provinsi }}</td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('kota.edit', $data->id) }}">
                                    <button type="button" class="btn rounded-pill btn-icon btn-warning">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </button>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="intro-y col-span-12">
            <div class="card-footer">
                {{ $kotas->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('kota.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
                id="formkota" aria-labelledby="formkotaLabel">
            <div class="offcanvas-header">
              <h5 id="formkotaLabel" class="offcanvas-title">Input Data kota</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto">
                <div class="form-group">
                    <label for="nama_kota" class="form-label">Nama kota</label>
                    <input id="nama_kota" type="text" name="nama_kota" class="form-control" value="{{ old('nama_kota') }}" minlength="3" required>
                    @error('nama_kota')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="pool_id">Pool</label>
                    <select id="provinsi_id" name="provinsi_id" class="select2 form-select" data-allow-clear="true">
                        <option value="">{{ __('Select Pool') }}</option>
                            @foreach($provinsis as $data)
                                <option value="{{ $data->id }}" {{ old('provinsi_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama_provinsi }}
                                </option>
                            @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" name="action">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas"> Cancel</button>
                </div>
            </div>
          </div>
        </form>
    </div>

@endsection

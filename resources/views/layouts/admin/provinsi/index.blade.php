@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('provinsi.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('provinsi.index') }}" class="m-0"><h5> Daftar Provinsi</h5></a>
                <p class="m-0">Total : {{ App\Models\Admin\Provinsi::count() }} </p>
            </div>
            <div class="add-new-role">
                <button
                    class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#formProvinsi" aria-controls="formProvinsi">
                    + Provinsi
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
                        <th>Nama Provinsi</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($provinsis as $data)
                    <tr>
                        <td>{{ $provinsis->firstItem() + $loop->index }}</td>
                        <td>{{ $data->nama_provinsi }}</td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('provinsi.edit', $data->id) }}">
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
                {{ $provinsis->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('provinsi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1"
                id="formProvinsi" aria-labelledby="formProvinsiLabel">
            <div class="offcanvas-header">
              <h5 id="formProvinsiLabel" class="offcanvas-title">Input Data Provinsi</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body my-auto">
                <div class="form-group">
                    <label for="nama_provinsi" class="form-label">Nama Provinsi</label>
                    <input id="nama_provinsi" type="text" name="nama_provinsi" class="form-control" value="{{ old('nama_provinsi') }}" minlength="3" required>
                    @error('nama_provinsi')
                    <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
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

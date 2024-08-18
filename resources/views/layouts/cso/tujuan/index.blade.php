@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('tujuan.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('tujuan.index') }}" class="m-0"><h5> Daftar Tujuan</h5></a>
                <p class="m-0">Total : {{ App\Models\Cso\tujuan::count() }} </p>
            </div>
            <div class="add-new-role">
                <button data-bs-target="#addUserModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                    + Tujuan
                </button>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.85">
                <thead>
                    <tr>
                        <th style="font-size: 14px"> No.</th>
                        <th style="font-size: 14px"> Tujuan</th>
                        <th style="font-size: 14px"> Pemakaian</th>
                        <th style="font-size: 14px"> Type Bus</th>
                        <th style="font-size: 14px"> Harga</th>
                        <th style="font-size: 14px"> Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($tujuans as $data)
                    <tr>
                        <td>{{ $tujuans->firstItem() + $loop->index }}</td>
                        <td>{{ $data->nama_tujuan }}</td>
                        <td>{{ $data->pemakaian }}</td>
                        <td>{{ $data->typearmadas->name }}</td>
                        <td>{{ number_format($data->harga_std, 0, ',', '.') }}</td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('tujuan.edit', $data->id) }}">
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
                {{ $tujuans->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4 p-md-4">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Tambah Tujuan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tujuan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="nama_tujuan">Nama Tujuan</label>
                                    <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan') }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="pemakaian">Pemakaian</label>
                                    <input type="text" class="form-control" id="pemakaian" name="pemakaian" value="{{ old('pemakaian') }}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="type_bus">Type Bus</label>
                                    <select id="type_bus" name="type_bus" class="form-select" data-allow-clear="true">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($typearmadas as $data)
                                            <option value="{{ $data->id }}" {{ old('type_bus', $data->type_bus) == $data->id ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="harga_std">Harga Standar</label>
                                    <input type="text" class="form-control" id="harga_std" name="harga_std"
                                    value="{{ old('harga_std') ? number_format((float) str_replace('.', '', old('harga_std')), 0, ',', '.') : '' }}"/>
                                </div>
                                <div class="pt-4">
                                    <button id="submit" type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <a href="{{ route('tujuan.index') }}" class="btn btn-warning">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hargaStdInput = document.getElementById('harga_std');

            hargaStdInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Hapus karakter selain angka
                e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        });
    </script>
@endsection

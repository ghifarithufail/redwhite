@extends('main')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($tujuan) ? route('tujuan.update', $tujuan->id) : route('tujuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($tujuan))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <h5>{{ isset($tujuan) ? 'EDIT TUJUAN' : 'CREATE TUJUAN' }}</h5>
                        <div class="col-md-6">
                            <label class="form-label" for="nama_tujuan">Nama tujuan</label>
                            <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan', isset($tujuan) ? $tujuan->nama_tujuan : '') }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pemakaian">Pemakaian</label>
                            <input type="text" class="form-control" id="pemakaian" name="pemakaian" value="{{ old('pemakaian', isset($tujuan) ? $tujuan->pemakaian : '') }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="type_bus">Type Bus</label>
                            <select id="type_bus" name="type_bus" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach($typearmadas as $data)
                                <option value="{{ $data->id }}" {{ old('type_bus', optional($tujuan)->type_bus) == $data->id ? 'selected' : '' }}>
                                    {{ $data->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="harga_std">Harga Standar</label>

                            <input type="text" class="form-control" id="harga_std" name="harga_std" value="{{ !empty(old('harga_std')) ? number_format(old('harga_std'), 0, ',', '.') : '' }}"/>
                        </div>
                        <div class="pt-4">
                            <button id="submit" type="submit" class="btn btn-primary mr-2">{{ isset($tujuan) ? 'Update' : 'Create' }}</button>
                            <a href="{{ route('tujuan.index') }}" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
       $(document).ready(function () {
            $('#type_bus').select2();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const hargaStdInput = document.getElementById('harga_std');

            hargaStdInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Hapus karakter selain angka
                e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        });
    </script>
@endsection

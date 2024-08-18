@extends('main')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tujuan.update', $tujuan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="nama_tujuan">Nama Tujuan</label>
                            <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan', $tujuan->nama_tujuan) }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pemakaian">Pemakaian</label>
                            <input type="text" class="form-control" id="pemakaian" name="pemakaian" value="{{ old('pemakaian', $tujuan->pemakaian) }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="type_bus">Type Bus</label>
                            <select id="type_bus" name="type_bus" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach($typearmadas as $data)
                                    <option value="{{ $data->id }}" {{ old('type_bus', $tujuan->type_bus) == $data->id ? 'selected' : '' }}>
                                        {{ $data->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-md-6">
                            <label class="form-label" for="type_bus">Type Bus</label>
                            <select id="type_bus" name="type_bus" class="select2 form-select" required>
                                <option value="">Select type_bus</option>
                                <option value="SINGEL GLASS" {{ old('type_bus', $tujuan->type_bus) == 'SINGEL GLASS' ? 'selected' : '' }}>SINGEL GLASS</option>
                                <option value="DOUBLE GLASS" {{ old('type_bus', $tujuan->type_bus) == 'DOUBLE GLASS' ? 'selected' : '' }}>DOUBLE GLASS</option>
                            </select>
                        </div> --}}
                        <div class="col-md-6">
                            <label class="form-label" for="harga_std">Harga Standar</label>
                            <input type="text" class="form-control" id="harga_std" name="harga_std"
                            value="{{ old('harga_std', number_format($tujuan->harga_std, 0, ',', '.')) }}"/>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hargaStdInput = document.getElementById('harga_std');

            hargaStdInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // Hapus karakter selain angka
                e.target.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format angka dengan titik
            });
        });
    </script>
@endsection

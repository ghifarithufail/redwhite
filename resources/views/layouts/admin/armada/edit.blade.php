@extends('main')

@section('content')
    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit armada</h5>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('armada.update', $armada->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk armada -->
                        <div class="mb-3 col-md-6">
                            <label for="nobody" class="form-label">Nomor Body</label>
                            <input id="nobody" type="text" name="nobody" class="form-control"
                                    value="{{ $armada->nobody }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="nochassis">Nomor Chassis</label>
                            <input type="text" class="form-control" id="nochassis" name="nochassis" value="{{ $armada->nochassis }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="nomesin">Nomor Mesin</label>
                            <input type="text" class="form-control" id="nomesin" name="nomesin" value="{{ $armada->nomesin }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nopolisi" class="form-label">Nomor Polisi</label>
                            <input id="nopolisi" type="text" name="nopolisi" class="form-control"
                                    value="{{ $armada->nopolisi }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="rute_id">Rute</label>
                            <select id="rute_id" name="rute_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Rute') }}</option>
                                @foreach($rutes as $rute)
                                    <option value="{{ $rute->id }}" {{ old('rute_id',$armada->rute_id) == $rute->id ? 'selected' : '' }}>
                                        {{ $rute->kode_rute }} - {{ $rute->nama_rute }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="merk">Merk</label>
                            <select id="merk" name="merk" class="select2 form-select" required>
                                <option value="">{{ __('Select Merk') }}</option>
                                <option value="HINO RG" {{ old('merk', $armada->merk) == 'HINO RG' ? 'selected' : '' }}>HINO RG</option>
                                <option value="HINO RJK" {{ old('merk', $armada->merk) == 'HINO RJK' ? 'selected' : '' }}>HINO RJK</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="tahun">Tahun</label>
                            <input type="number" class="form-control" id="tahun" name="tahun" value="{{ $armada->tahun }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="jenis">Jenis Layanan</label>
                            <select id="jenis" name="jenis" class="select2 form-select" required>
                                <option value="">Select Status</option>
                                <option value="EKONOMI" {{ $armada->jenis == 'EKONOMI' ? 'selected' : '' }}>EKONOMI</option>
                                <option value="AC EKONOMI" {{ $armada->jenis == 'AC EKONOMI' ? 'selected' : '' }}>AC EKONOMI</option>
                                <option value="AC BISNIS" {{ $armada->jenis == 'AC BISNIS' ? 'selected' : '' }}>AC BISNIS</option>
                                <option value="EXECUTIVE" {{ $armada->jenis == 'EXECUTIVE' ? 'selected' : '' }}>EXECUTIVE</option>
                                <option value="SUPER EXECUTIVE" {{ $armada->jenis == 'SUPER EXECUTIVE' ? 'selected' : '' }}>SUPER EXECUTIVE</option>
                                <option value="WISATA" {{ $armada->jenis == 'WISATA' ? 'selected' : '' }}>WISATA</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="seat">Seat</label>
                            <input type="number" class="form-control" id="seat" name="seat" value="{{ $armada->seat }}"/>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="kondisi">Kondisi</label>
                            <select id="kondisi" name="kondisi" class="select2 form-select" required>
                                <option value="">Select Kondisi</option>
                                <option value="Baik" {{ $armada->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Sedang" {{ $armada->kondisi == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Rusak" {{ $armada->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="type_id">Type Armada</label>
                            <select id="type_id" name="type_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach($typearmadas as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id', $armada->type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="keterangan">Keterangan</label>
                            <input type="keterangan" class="form-control" id="keterangan" name="keterangan" value="{{ $armada->keterangan }}"/>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('armada.index') }}" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

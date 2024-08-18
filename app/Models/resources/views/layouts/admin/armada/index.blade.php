@extends('main')
@section('content')

<div class="card text-center">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="search-field">
            <form action="{{ route('armada.index') }}" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search...">
            </form>
        </div>
        <div>
            <a href="{{ route('armada.index') }}" class="m-0"><h5> Daftar Armada</h5></a>
            <p class="m-0">Total : {{ App\Models\Armada::count() }} </p>
        </div>
        <div class="add-new-role">
            <!-- Tombol "Add New Role" -->
            <button data-bs-target="#addarmadaModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                + Armada
            </button>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" style="zoom: 0.85">
            <thead>
                <tr>
                    <th>No.Urt</th>
                    <th>Nomor Body</th>
                    <th>Nomor Chassis</th>
                    <th>Nomor Mesin</th>
                    <th>Nomor Polisi</th>
                    <th>Rute</th>
                    <th>Nama Rute</th>
                    <th>Pool</th>
                    <th>Merk</th>
                    <th>Tahun</th>
                    <th>Jenis</th>
                    <th>Seat</th>
                    <th>Type</th>
                    <th>Kondisi</th>
                    <th>Keterangan</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($armadas as $data)
                <tr>
                    <td style="font-size: 14px">{{ $armadas->firstItem() + $loop->index }}</td>
                    <td style="font-size: 14px">{{ $data->nobody }}</td>
                    <td style="font-size: 14px">{{ $data->nochassis }}</td>
                    <td style="font-size: 14px">{{ $data->nomesin }}</td>
                    <td style="font-size: 14px">{{ $data->nopolisi }}</td>
                    <td style="font-size: 14px">{{ $data->rutes->kode_rute ?? '-' }}</td>
                    <td style="font-size: 14px">{{ $data->rutes->nama_rute ?? '-' }}</td>
                    <td style="font-size: 14px">{{ $data->rutes->pools->nama_pool ?? '-' }}</td>
                    <td style="font-size: 14px">{{ $data->merk }}</td>
                    <td style="font-size: 14px">{{ $data->tahun }}</td>
                    <td style="font-size: 14px">{{ $data->jenis }}</td>
                    <td style="font-size: 14px">{{ $data->seat }}</td>
                    <td style="font-size: 14px">{{ $data->type_armada->name ?? '-'}}</td>
                    <td style="font-size: 14px">
                        @if ($data->kondisi == 'Baik')
                            <label class="flex items-center justify-center text-success">Baik</label>
                        @elseif ($data->kondisi =='Sedang')
                            <label class="flex items-center justify-center text-warning">Sedang</label>
                        @elseif ($data->kondisi =='Buruk')
                            <label class="flex items-center justify-center text-danger">Buruk</label>
                        @else
                        @endif
                    </td>
                    <td style="font-size: 14px">{{ $data->keterangan }}</td>
                    <td style="font-size: 14px">
                        <div class="dropdown text-center">
                            <a href="{{ route('armada.edit', $data->id) }}">
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
            {{ $armadas->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addarmadaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-armada">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                <div class="text-center mb-4">
                    <h3 class="mb-2">Tambah Armada</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('armada.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Input untuk armada -->
                            <div class="mb-3 col-md-6">
                                <label for="nobody" class="form-label">Nomor Body</label>
                                <input id="nobody" type="text" name="nobody" class="form-control" value="{{ old('nobody') }}" minlength="3" required>
                                @error('nobody')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="nopolisi" class="form-label">Nomor Polisi</label>
                                <input id="nopolisi" type="text" name="nopolisi" class="form-control" value="{{ old('nopolisi') }}" minlength="3" required>
                                @error('nopolisi')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="nochassis">Nomor Chassis</label>
                                <input type="text" class="form-control" id="nochassis" name="nochassis" value="{{ old('nochassis') }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="nomesin">Nomor Mesin</label>
                                <input type="text" class="form-control" id="nomesin" name="nomesin" value="{{ old('nomesin') }}"/>
                            </div>
                           <div class="mb-3 col-md-6">
                                <label class="form-label" for="rute_id">Rute</label>
                                <select id="rute_id" name="rute_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">{{ __('Select Rute') }}</option>
                                    @foreach($rutes as $rute)
                                        <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                            {{ $rute->kode_rute }} - {{ $rute->nama_rute }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="merk">Merk</label>
                                <select id="merk" name="merk" class="select2 form-select" required>
                                    <option value="">Select Merk</option>
                                    <option value="HINO RG" {{ old('merk') == 'HINO RG' ? 'selected' : '' }}>HINO RG</option>
                                    <option value="HINO RJK" {{ old('merk') == 'HINO RJK' ? 'selected' : '' }}>HINO RJK</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" value="{{ old('tahun') }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="jenis">Jenis Layanan</label>
                                <select id="jenis" name="jenis" class="select2 form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="EKONOMI" {{ old('jenis') == 'EKONOMI' ? 'selected' : '' }}>EKONOMI</option>
                                    <option value="AC EKONOMI" {{ old('jenis') == 'AC EKONOMI' ? 'selected' : '' }}>AC EKONOMI</option>
                                    <option value="AC BISNIS" {{ old('jenis') == 'AC BISNIS' ? 'selected' : '' }}>AC BISNIS</option>
                                    <option value="EXECUTIVE" {{ old('jenis') == 'EXECUTIVE' ? 'selected' : '' }}>EXECUTIVE</option>
                                    <option value="SUPER EXECUTIVE" {{ old('jenis') == 'SUPER EXECUTIVE' ? 'selected' : '' }}>SUPER EXECUTIVE</option>
                                    <option value="WISATA" {{ old('jenis') == 'WISATA' ? 'selected' : '' }}>WISATA</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="seat">Seat</label>
                                <input type="number" class="form-control" id="seat" name="seat" value="{{ old('seat') }}"/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="type_id">Type Armada</label>
                                <select id="type_id" name="type_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">{{ __('Select Type') }}</option>
                                    @foreach($typearmadas as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="kondisi">Kondisi</label>
                                <select id="kondisi" name="kondisi" class="select2 form-select" required>
                                    <option value="">Select Kondisi</option>
                                    <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Sedang" {{ old('kondisi') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <input type="keterangan" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan') }}"/>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary" name="action">Submit</button>
                                <a href="{{ route('armada.index') }}" class="btn btn-warning">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Jika terdapat pesan sukses dari server, tampilkan pesan toastr
    @if (session('success'))
    toastr.success("{{ session('success') }}");
    @endif

    // Jika terdapat pesan error dari server, tampilkan pesan toastr
    @if (session('error'))
    toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection

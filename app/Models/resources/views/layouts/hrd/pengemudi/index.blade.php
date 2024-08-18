@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('pengemudi.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('pengemudi.index') }}" class="m-0"><h5> Daftar pengemudi</h5></a>
                <p class="m-0">Total : {{ App\Models\Hrd\pengemudi::count() }} </p>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <a href="{{ route('pengemudi.create') }}" class="btn btn-primary mb-2 text-nowrap">
                    + pengemudi
                </a>
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
                        <th>No. KTP & Nama</th>
                        <th>No. Induk & Rute</th>
                        <th>Tgl KP & Masuk</th>
                        <th>No. & Jenis SIM</th>
                        <th>Tgl SIM</th>
                        <th>No. Jamsostek</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($pengemudis as $data)
                    <tr @if($data->users->biodata->nik == $data->nopengemudi) class="same-nik-nopengemudi" @endif>
                        <td>{{ $pengemudis->firstItem() + $loop->index }}</td>
                        <td class="w-20">
                            @if(isset($data->users->biodata))
                                @php
                                    $nik = $data->users->biodata->nik;
                                @endphp
                                <a href="{{ route('biodata.show', ['nik' => $nik]) }}" class="text-sm">
                                    {{ $nik }}
                                </a>
                            @else
                                <span>NIK Belum di Input</span>
                            @endif
                            <div>{{ $data->users->name }}</div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->nopengemudi }}</span>
                            <div>{{ $data->rutes->kode_rute }}</div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->tanggal_kp }}</span>
                            <div>{{ $data->tgl_masuk }}</div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->nosim }}</span>
                            <div>{{ $data->jenis_sim }}</div>
                        </td>
                        <td>{{ $data->tgl_sim }}</td>
                        <td>{{ $data->nojamsostek }}</td>
                        <td>
                            @if ($data->status == 'Active')
                                <label class="flex items-center justify-center text-success">Active</label>
                            @elseif ($data->status =='Inactive')
                                <label class="flex items-center justify-center text-warning">Inactive</label>
                            @elseif ($data->status =='Disable')
                                <label class="flex items-center justify-center text-danger">Disable</label>
                            @else
                            @endif
                        </td>
                        <td>{{ $data->ket_pengemudi }}</td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('pengemudi.edit', $data->id) }}">
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
                {{ $pengemudis->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    </div>

@endsection

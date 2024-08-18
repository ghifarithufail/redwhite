@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('biodata.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('biodata.index') }}" class="m-0"><h5> Daftar biodata</h5></a>
                <p class="m-0">Total : {{ App\Models\Hrd\Biodata::count() }} </p>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <a href="{{ route('biodata.create') }}" class="btn btn-primary mb-2 text-nowrap">
                    + Biodata
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
                        <th>Photo</th>
                        <th>Nomor KTP & KK</th>
                        <th>Nama & Email</th>
                        <th>Tempat & Tanggal Lahir</th>
                        <th>Jenis & Nikah </th>
                        <th>Agama</th>
                        <th>Alamat</th>
                        <th>RT/RW</th>
                        <th>Role</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($biodata as $data)
                    <tr>
                        <td>{{ $biodata->firstItem() + $loop->index }}</td>
                        <td>
                            @if(isset($data->users->biodata->image))
                            <img src="{{ asset('storage/images/' . $data->users->biodata->image) }}" style="width: 50px; height: 50px; border-radius: 100%;">
                        @else
                            <span>No Img</span>
                        @endif
                        </td>
                        <td class="w-20">
                            <span>{{ $data->nik }}</span>
                            <div>{{ $data->nokk }}</div>
                        </td>
                        <td class="w-20">
                            @if($data->users)
                                <span>{{ $data->users->name }}</span>
                                <div>{{ $data->users->email }}</div>
                            @endif
                        </td>
                        <td class="w-20">
                            <span>{{ $data->kotas->nama_kota }}</span>
                            <div>{{ $data->tgl_lahir }}</div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->jenis }}</span>
                            <div>{{ $data->nikah }}</div>
                        </td>
                        <td>{{ $data->agama }}</td>
                        <td>{{ $data->alamat }}</td>
                        <td class="w-20">
                            <span>{{ $data->rt }}</span>
                            <div>{{ $data->rw }}</div>
                        </td>
                        <td>
                            @isset($data->users->roles)
                                @foreach($data->users->roles as $role)
                                    {{ $role->name }}
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('biodata.edit', $data->id) }}">
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
                {{ $biodata->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    </div>

@endsection

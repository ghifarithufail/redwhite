@extends('main')
@section('content')

    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('karyawan.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('karyawan.index') }}" class="m-0"><h5> Daftar karyawan</h5></a>
                <p class="m-0">Total : {{ App\Models\Hrd\karyawan::count() }} </p>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <a href="{{ route('karyawan.create') }}" class="btn btn-primary mb-2 text-nowrap" target="_blank">
                    + karyawan
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
                        <th>No.Induk & No. KTP</th>
                        <th>Nama & Jabatan</th>
                        <th>Pool & Roles</th>
                        <th>Tgl KP & Masuk</th>
                        <th>Keterangan</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($karyawans as $data)
                    <tr>
                        <td>{{ $karyawans->firstItem() + $loop->index }}</td>
                        <td class="w-20">
                            <div>
                                @if(isset($data->users->biodata))
                                    @php
                                        $nik = $data->users->biodata->nik;
                                    @endphp
                                    <a href="#" class="text-sm">
                                        {{ $nik }}
                                    </a>
                                @else
                                    <span>NIK Belum di Input</span>
                                @endif
                            </div>
                            <div>{{ $data->users->name }}</div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->noinduk }}</span>
                            <span>{{ $data->jabatans->nama_jabatan }}</span>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->pools->nama_pool }}</span>
                            <div> @isset($data->users->roles)
                                    @foreach($data->users->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="w-20">
                            <span>{{ $data->tanggal_kp }}</span>
                            <div>{{ $data->tgl_masuk }}</div>
                        </td>
                        <td>{{ $data->keterangan }}</td>

                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('karyawan.edit', $data->id) }}">
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
                {{ $karyawans->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    </div>

@endsection

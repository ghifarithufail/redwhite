@extends('main')
@section('content')

<div class="card text-center">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="search-field">
            <form action="{{ route('rute.index') }}" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search...">
            </form>
        </div>
        <div>
            <a href="{{ route('rute.index') }}" class="m-0"><h5> Daftar rute</h5></a>
            <p class="m-0">Total : {{ App\Models\Admin\rute::count() }} </p>
        </div>
        <div class="add-new-role">
            <!-- Tombol "Add New Role" -->
            <button data-bs-target="#addUserModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                + rute
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
                    <th>Kode Rute</th>
                    <th>Nama Rute</th>
                    <th>Jenis</th>
                    <th>Ritase</th>
                    <th>Pool</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($rutes as $data)
                <tr>
                    <td>{{ $rutes->firstItem() + $loop->index }}</td>
                    <td>{{ $data->kode_rute }}</td>
                    <td>{{ $data->nama_rute }}</td>
                    <td>{{ $data->jenis }}</td>
                    <td>{{ $data->stdrit }}</td>
                    <td>{{ $data->pools->nama_pool }}</td>
                    <td>
                        @if ($data->status == 'Active')
                        <label class="flex items-center justify-center text-success">Active</label>
                        @elseif ($data->status =='Inactive')
                            <label class="flex items-center justify-center text-warning">Inactive</label>
                        @elseif ($data->status =='Disable')
                            <label class="flex items-center justify-center text-warning">Disable</label>
                        @else
                        @endif
                    </td>
                    <td>
                        <div class="dropdown text-center">
                            <a href="{{ route('rute.edit', $data->id) }}">
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
            {{ $rutes->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Add Role Modal -->

<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                <div class="text-center mb-4">
                    <h3 class="mb-2">Tambah Rute</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('rute.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Input untuk user -->

                            <div class="mb-3 col-md-6">
                                <label for="kode_rute" class="form-label">Kode Rute</label>
                                <input id="kode_rute" type="text" name="kode_rute" class="form-control" value="{{ old('kode_rute') }}" minlength="3" required>
                                @error('kode_rute')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="nama_rute" class="form-label">Nama rute</label>
                                <input id="nama_rute" type="text" name="nama_rute" class="form-control" value="{{ old('nama_rute') }}" minlength="3" required>
                                @error('nama_rute')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
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
                            <div class="col-md-6">
                                <label class="form-label" for="stdrit">Standar Rit</label>
                                <input type="number" class="form-control" id="stdrit" name="stdrit" value="{{ old('stdrit') }}"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="pool_id">Pool</label>
                                <select id="pool_id" name="pool_id" class="select2 form-select" data-allow-clear="true">
                                    <option value="">{{ __('Select Pool') }}</option>
                                        @foreach($pools as $data) <!-- Ubah variabel $pool menjadi $pools -->
                                            <option value="{{ $data->id }}" {{ old('pool_id') == $data->id ? 'selected' : '' }}>
                                                {{ $data->nama_pool }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="status">Status</label>
                                <select id="status" name="status" class="select2 form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary" name="action">Submit</button>
                                <a href="{{ route('rute.index') }}" class="btn btn-warning">Cancel</a>
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

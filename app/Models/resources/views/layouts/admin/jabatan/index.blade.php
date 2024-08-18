@extends('main')
@section('content')

<div class="card text-center">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="search-field">
            <form action="{{ route('jabatan.index') }}" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Search...">
            </form>
        </div>
        <div>
            <a href="{{ route('jabatan.index') }}" class="m-0"><h5> Jabatan</h5></a>
            <p class="m-0">Total : {{ App\Models\Admin\Jabatan::count() }} </p>
        </div>
        <div class="add-new-role">
            <!-- Tombol "Add New Role" -->
            <button data-bs-target="#addUserModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                + Jabatan
            </button>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" style="zoom: 0.85">
            <thead>
                <tr>
                    <th>Nama Jabatan</th>
                    <th>Kode Jabatan</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($jabatans as $data)
                <tr>
                    <td>{{ $data->nama_jabatan }}</td>
                    <td>{{ $data->kodejab }}</td>
                    <td>
                        <div class="dropdown text-center">
                            <a href="{{ route('jabatan.edit', $data->id) }}">
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
            {{ $jabatans->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                    <h3 class="mb-2">Tambah Jabatan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jabatan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Input untuk user -->
                            <div class="mb-3 col-md-6">
                                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                                <input id="nama_jabatan" type="text" name="nama_jabatan" class="form-control" value="{{ old('nama_jabatan') }}" minlength="3" required>
                                @error('nama_jabatan')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="kodejab" class="form-label">Kode Jabtan</label>
                                <input id="kodejab" type="text" name="kodejab" class="form-control" value="{{ old('kodejab') }}" minlength="3" required>
                                @error('kodejab')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary" name="action">Submit</button>
                                <a href="{{ route('jabatan.index') }}" class="btn btn-warning">Cancel</a>
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

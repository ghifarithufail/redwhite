@extends('main')

@section('content')
    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit Jabatan</h5>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk Jabatan -->
                        <div class="mb-3 col-md-6">
                            <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                            <input id="nama_jabatan" type="text" name="nama_jabatan" class="form-control"
                                    value="{{ $jabatan->nama_jabatan }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="kodejab" class="form-label">Kode Jabatan</label>
                            <input id="kodejab" type="text" name="kodejab" class="form-control"
                                    value="{{ $jabatan->kodejab }}" minlength="3" required>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('jabatan.index') }}" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </form>
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

@extends('main')

@section('content')
    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit rute</h5>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('rute.update', $rute->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk rute -->
                        <div class="mb-3 col-md-6">
                            <label for="kode_rute" class="form-label">Kode Rute</label>
                            <input id="kode_rute" type="text" name="kode_rute" class="form-control"
                                    value="{{ $rute->kode_rute }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nama_rute" class="form-label">Nama Rute</label>
                            <input id="nama_rute" type="text" name="nama_rute" class="form-control"
                                    value="{{ $rute->nama_rute }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="jenis">Jenis Layanan</label>
                            <select id="jenis" name="jenis" class="select2 form-select" required>
                                <option value="">Select Jenis Layanan</option>
                                <option value="EKONOMI" {{ $rute->jenis == 'EKONOMI' ? 'selected' : '' }}>EKONOMI</option>
                                 <option value="AC EKONOMI" {{ $rute->jenis == 'AC EKONOMI' ? 'selected' : '' }}>AC EKONOMI</option>
                                <option value="AC BISNIS" {{$rute->jenis == 'AC BISNIS' ? 'selected' : '' }}>AC BISNIS</option>
                                <option value="EXECUTIVE" {{ $rute->jenis == 'EXECUTIVE' ? 'selected' : '' }}>EXECUTIVE</option>
                                <option value="SUPER EXECUTIVE" {{$rute->jenis == 'SUPER EXECUTIVE' ? 'selected' : '' }}>SUPER EXECUTIVE</option>
                                <option value="WISATA" {{ $rute->jenis == 'WISATA' ? 'selected' : '' }}>WISATA</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="stdrit">Standar Rit</label>
                            <input type="number" class="form-control" id="stdrit" name="stdrit" value="{{ $rute->stdrit }}"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pool_id">Pool</label>
                            <select id="pool_id" name="pool_id" class="select2 form-select" data-allow-clear="true">
                                <option value="">{{ __('Select Pool') }}</option>
                                    @foreach($pools as $data) <!-- Ubah variabel $pool menjadi $pools -->
                                        <option value="{{ $data->id }}" {{ $rute->pool_id == $data->id ? 'selected' : '' }}>
                                            {{ $data->nama_pool }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="select2 form-select" required>
                                <option value="">Select Status</option>
                                <option value="Active" {{ $rute->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $rute->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Disable" {{ $rute->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('rute.index') }}" class="btn btn-warning">Back</a>
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

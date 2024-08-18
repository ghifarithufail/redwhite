@extends('main')
@section('content')

    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit Provinsi</h5>
            <div class="card-body">
                <form action="{{ route('provinsi.update', $provinsi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="nama_provinsi" class="form-label">Nama provinsi</label>
                            <input id="nama_provinsi" type="text" name="nama_provinsi" class="form-control"
                                    value="{{ $provinsi->nama_provinsi }}" minlength="3" required>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('provinsi.index') }}" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@extends('main')
@section('content')

    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit pool</h5>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('pool.update', $pool->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk pool -->
                        <div class="mb-3 col-md-6">
                            <label for="nama_pool" class="form-label">Nama pool</label>
                            <input id="nama_pool" type="text" name="nama_pool" class="form-control"
                                    value="{{ $pool->nama_pool }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="alamat" class="form-label">Alamat Pool</label>
                            <input id="alamat" type="text" name="alamat" class="form-control"
                                    value="{{ $pool->alamat }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Telephone Pool</label>
                            <input id="phone" type="text" name="phone" class="form-control"
                                    value="{{ $pool->phone }}" minlength="3" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="select2 form-select" required>
                                <option value="Active" {{ $pool->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $pool->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Disable" {{ $pool->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                            </select>
                            @error('status')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('pool.index') }}" class="btn btn-warning">Back</a>
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

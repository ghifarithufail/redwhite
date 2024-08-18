@extends('main')

@section('content')
    <div class="container-xxl">
        <div class="card mb-4">
            <h5 class="card-header">Edit User</h5>
            <!-- Account -->
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Input untuk user -->
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" name="name" class="form-control" value="{{ $user->name }}" minlength="3" required>
                            @error('name')
                                <div class="sm:ml-auto mt-1 sm:mt-0 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="username" class="form-label">User Name</label>
                            <input id="username" type="text" name="username" class="form-control" value="{{ $user->username }}" minlength="3" required>
                            @error('username')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            @error('email')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" type="text" name="phone" class="form-control" value="{{ $user->phone }}" minlength="8" required>
                            @error('phone')
                                <div class="sm:ml-auto mt-1 sm:mt-0 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control" minlength="6">
                            @error('password')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="roles" class="form-label">Role</label>
                            <select class="select2 form-select" name="roles[]" data-search="true" required >
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="select2 form-select" required>
                                <option value="Active" {{ $user->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $user->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Disable" {{ $user->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                            </select>
                            @error('status')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary" name="action">Update</button>
                            <a href="{{ route('users') }}" class="btn btn-warning">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

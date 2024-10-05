@extends('main')
@section('content')
    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('users') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('users') }}"  class="m-0"><h5> DaftarPemakai</h5></a>
                <p class="m-0">Total : {{ App\Models\User::count() }} </p>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <button data-bs-target="#addUserModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                    + Pemakai
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.85">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($users as $data)
                        <tr>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->username }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->phone }}</td>
                            <td> @foreach($data->roles as $role)
                                    {{ $role->name }}
                                @endforeach
                            </td>
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

                            <td>
                                <div class="dropdown text-center">
                                    <a href="{{ route('users.edit', $data->id) }}">
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
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                        <h3 class="mb-2">Tambah Pemakai</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Input untuk user -->
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" minlength="3" required>
                                    @error('name')
                                        <div class="sm:ml-auto mt-1 sm:mt-0 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">User Name</label>
                                    <input id="username" type="text" name="username" class="form-control" value="{{ old('username') }}" minlength="3" required>
                                    @error('username')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone') }}" minlength="8" required>
                                    @error('phone')
                                        <div class="sm:ml-auto mt-1 sm:mt-0 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" name="password" class="form-control" minlength="6" required>
                                    @error('password')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="roles" class="form-label">Role</label>
                                    <select class="select2 form-select" name="roles[]" data-search="true" required >
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="status">Status</label>
                                    <select id="status" name="status" class="select2 form-select" required>
                                        <option value="[]">Select Status</option>
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>Disable</option>
                                    </select>
                                    @error('status')
                                        <span class="text-red-400 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary" name="action">Submit</button>
                                    <a href="{{ route('users') }}" class="btn btn-warning">Cancel</a>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

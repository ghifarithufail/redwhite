@extends('main')
@section('content')
    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('roles.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('roles.index') }}" class="m-0">Total Roles :
                    {{ \Spatie\Permission\Models\Role::count() }}
                </a>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <button data-bs-target="#addRoleModal" data-bs-toggle="modal" class="btn btn-primary mb-2 text-nowrap">
                    Tambah Role
                </button>
            </div>
        </div>
    </div>

    <div class="col-12">
        <!-- Role Table -->
        <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">No.</th>
                    <th class="whitespace-nowrap">Name</th>
                    <th class="whitespace-nowrap">Permission</th>
                    <th class="whitespace-nowrap"><i class="ti ti-dots-vertical"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->permissions as $permission)
                                <span class="whitespace-nowrap">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="dropdown text-center">
                                <a href="{{ route('roles.edit', $role->id) }}">
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
                {{ $roles->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
        </div>
    </div>
    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Create Roles</h3>
                    </div>
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Permissions</label><br>
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-warning">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

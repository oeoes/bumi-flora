@extends('layouts.master')

@section('title', 'Bumi Flora - Access Management')

@section('custom-css')
<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
        border-radius: 5px
    }

    legend.scheduler-border {
        font-size: 100%;
        width: inherit;
        /* Or auto */
        padding: 0 10px;
        /* To give a bit of padding on the left and right */
        border-bottom: none;
    }
</style>
@endsection

@section('custom-js')
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/access-roles.js') }}"></script>
@endsection

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Administrator</h1>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Admin</h6>
                    <div class="dropdown no-arrow">
                        <button class="btn btn-sm btn-outline-primary rounded-pill mb-1" data-toggle="modal" data-target="#user-modal"><i class="fas fa-fw fa-user-plus"></i> Add User</button>
                        <button class="btn btn-sm btn-outline-info rounded-pill mb-1" data-toggle="modal" data-target="#role-modal"><i class="fas fa-fw fa-users-cog"></i> Add Role</button>
                        <button class="btn btn-sm btn-outline-success rounded-pill mb-1" data-toggle="modal" data-target="#permission-modal"><i class="fas fa-fw fa-user-lock"></i> Add Permission</button>

                        <!-- Invite User -->
                        <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Invite User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="invite-user-form" action="{{ route('access.invite_user') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input id="user-name" type="text" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input id="user-email" type="email" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input id="user-phone" type="text" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Jenis User</label>
                                                <select id="user-role" class="form-control">
                                                    <option value="admin">Administrator</option>
                                                    <option value="user">Common User</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input id="user-password" type="password" class="form-control form-control-sm" required>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input id="invite-user" type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Add User">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Invite User -->

                        <!-- Create Role -->
                        <div class="modal fade" id="role-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Create Role</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('access.create_role') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>Role Name</label>
                                                    <input type="text" name="role_name" class="form-control" placeholder="Type role name">
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Add role">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Create Role -->

                        <!-- Add Permission -->
                        <div class="modal fade" id="permission-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('access.add_permission') }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label>Permission Name</label>
                                                <input type="text" name="permission_name" class="form-control" placeholder="Type your permision">
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Add permission">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add Permission -->
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="access-tab" data-toggle="pill" href="#access" role="tab" aria-controls="access" aria-selected="true">Access</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="user-tab" data-toggle="pill" href="#user" role="tab" aria-controls="user" aria-selected="true">User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="role-tab" data-toggle="pill" href="#role" role="tab" aria-controls="role" aria-selected="false">Roles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="permission-tab" data-toggle="pill" href="#permission" role="tab" aria-controls="permission" aria-selected="false">Permissions</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="access" role="tabpanel" aria-labelledby="access-tab">
                            <div class="table-responsive table-borderless table-hover">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Assign Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                {{ $user->getRoleNames()->first() }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-success rounded-pill mb-1 pr-3 pl-3" data-toggle="modal" data-target="#assign-role{{ $key }}"><i data-feather="user-plus"></i></button>
                                            </td>

                                            <!-- Assign Role -->
                                            <div class="modal fade" id="assign-role{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Assign Role</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('access.assign_role', ['user' => $user->id]) }}" method="POST">
                                                                @method('PUT')
                                                                @csrf
                                                                @foreach($roles as $key => $role)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="assignrole" id="assignrole{{ $key }}" value="{{ $role->name }}">
                                                                    <label class="form-check-label" for="assignrole{{ $key }}">
                                                                        {{ ucwords($role->name) }}
                                                                    </label>
                                                                </div>
                                                                @endforeach
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Assign">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Assign Role -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="user" role="tabpanel" aria-labelledby="user-tab">
                            <div class="table-responsive table-borderless table-hover">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger rounded-pill mb-1 pr-3 pl-3" data-toggle="modal" data-target="#delete-user{{ $key }}"><i data-feather="trash"></i></button>
                                            </td>

                                            <!-- Delete user -->
                                            <div class="modal fade" id="delete-user{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus User</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('access.destroy', ['id' => $user->id]) }}" method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                Hapus akun user pada sistem bumiflora80.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Hapus">
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Delete role -->
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
                            <div class="table-responsive table-borderless table-hover">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Role Name</th>
                                            <th scope="col">Invoke/Revoke Permission</th>
                                            <th scope="col">Action</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $key => $role)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td><button class="btn btn-sm btn-outline-success rounded-pill mb-1" data-toggle="modal" data-target="#assign-permission{{ $key }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-toggle-right">
                                                        <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                        <circle cx="16" cy="12" r="3"></circle>
                                                    </svg></i></button></td>
                                            <td><button class="btn btn-outline-primary btn-sm rounded-pill pr-3 pl-3" data-toggle="modal" data-target="#edit-role{{$key}}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                                    </svg></button></td>
                                        </tr>

                                        <!-- Edit Role -->
                                        <div class="modal fade" id="edit-role{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('roles.update', ['role' => $role->id]) }}" method="post">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="form-group">
                                                                <label>Role Name</label>
                                                                <input type="text" name="role_name" class="form-control" placeholder="Type role" value="{{ $role->name }}">
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Update">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit Role -->

                                        <!-- Invoke/Revoke Permission -->
                                        <div class="modal fade" id="assign-permission{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Invoke/Revoke Permission</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{ route('access.assign_permission', ['role' => $role->id]) }}" method="POST">
                                                            @method('PUT')
                                                            @csrf
                                                            @foreach($permissions as $key => $permission)
                                                            @if(count(\Spatie\Permission\Models\Role::findByName($role->name)->permissions) > 0)
                                                            @if(in_array($permission->name, \Spatie\Permission\Models\Role::findByName($role->name)->permissions->pluck('name')->toArray()))
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="assignpermission[]" id="assignpermission{{ $key }}" value="{{ $permission->name }}" checked>
                                                                <label class="form-check-label" for="assignpermission{{ $key }}">
                                                                    {{ ucwords($permission->name) }}
                                                                </label>
                                                            </div>
                                                            @else
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="assignpermission[]" id="assignpermission{{ $key }}" value="{{ $permission->name }}">
                                                                <label class="form-check-label" for="assignpermission{{ $key }}">
                                                                    {{ ucwords($permission->name) }}
                                                                </label>
                                                            </div>
                                                            @endif
                                                            @else
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="assignpermission[]" id="assignpermission{{ $key }}" value="{{ $permission->name }}">
                                                                <label class="form-check-label" for="assignpermission{{ $key }}">
                                                                    {{ ucwords($permission->name) }}
                                                                </label>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Assign">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Invoke/Revoke Permission -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="permission" role="tabpanel" aria-labelledby="permission-tab">
                            <div class="table-responsive table-borderless table-hover">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Permission Name</th>
                                            <th scope="col">Action</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $key => $permission)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $permission->name }}</td>
                                            <td><button class="btn btn-outline-primary btn-sm rounded-pill pr-3 pl-3" data-toggle="modal" data-target="#edit-permission{{$key}}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                                        <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                                                    </svg></button></td>
                                        </tr>

                                        <!-- Edit Permission -->
                                        <div class="modal fade" id="edit-permission{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Permission</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('permissions.update', ['permission' => $permission->id]) }}" method="post">
                                                            @method('PUT')
                                                            @csrf
                                                            <div class="form-group">
                                                                <label>Permission Name</label>
                                                                <input type="text" name="permission_name" class="form-control" placeholder="Type your permision" value="{{ $permission->name }}">
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-4 pr-4" value="Update">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit Permission -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->

@endsection
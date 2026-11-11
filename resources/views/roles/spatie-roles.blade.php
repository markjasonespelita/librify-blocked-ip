@extends('layouts.main')

@section('content')
    {{-- Header --}}
    <div class="mb-3 row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <h1>All Roles</h1>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <a href="{{ url('trash-roles') }}">
                <button class="btn btn-danger">
                    <i class="fas fa-trash"></i> Trash
                    <span class="text-warning">
                        {{-- {{ \App\Models\Roles::where('isTrash', 1)->count() }} --}}
                    </span>
                </button>
            </a>

            @can('can create role')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRole">
                    <i class="fas fa-plus"></i> Add Role
                </button>
            @endcan
        </div>
    </div>

    {{-- Add Role Modal --}}
    <div class="modal fade modal-lg" id="addRole" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ url('/store-spatie-role') }}">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($roles as $role)
                            <tr>

                                <td>{{ ucwords($role->name) }}</td>

                                <td>
                                    @if($role->permissions->count())
                                        @foreach($role->permissions as $permission)
                                            <span class="badge rounded-pill bg-dark me-1 mb-1">
                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted fst-italic">No permissions</span>
                                    @endif
                                </td>

                                <td>

                                    @can('can add permission to role')
                                        {{-- Permission Button --}}
                                        <a href="#" class="text-primary me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionRole{{ $role->id }}">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    @endcan

                                    @can('can edit role')
                                        {{-- Edit Button --}}
                                        <a href="#" class="text-primary me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRole{{ $role->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('can delete role')
                                        {{-- Delete Button --}}
                                        <a href="#" class="text-danger me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteRole{{ $role->id }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @endcan

                                    {{-- Permission Modal --}}
                                    <div class="modal fade modal-lg" id="permissionRole{{ $role->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Permission For {{ ucwords($role->name) }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form method="POST" action="{{ url('/update-permission-for-spatie-role/' . $role->id) }}">
                                                    @csrf

                                                    <div class="modal-body">
                                                        <!-- Permissions checklist -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Assign Permission For {{ ucwords($role->name) }}</label>
                                                            <div class="row">
                                                                @php
                                                                    $permissions = Spatie\Permission\Models\Permission::all();
                                                                    $half = ceil($permissions->count() / 2);
                                                                    $_role = Spatie\Permission\Models\Role::findOrFail($role->id);
                                                                @endphp

                                                                <div class="col-md-6">
                                                                    @foreach($permissions->slice(0, $half) as $permission)
                                                                        <div class="form-check">
                                                                            <input
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="role_{{ $permission->id }}"
                                                                                name="spatie_permissions[]"
                                                                                value="{{ $permission->name }}"
                                                                                {{ ($_role->hasPermissionTo($permission->name)) ? "checked" : "" }}
                                                                            >
                                                                            <label class="form-check-label" for="role_{{ $permission->id }}">
                                                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <div class="col-md-6">
                                                                    @foreach($permissions->slice($half) as $permission)
                                                                        <div class="form-check">
                                                                            <input
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="role_{{ $permission->id }}"
                                                                                name="spatie_permissions[]"
                                                                                value="{{ $permission->name }}"
                                                                                {{ ($_role->hasPermissionTo($permission->name)) ? "checked" : "" }}
                                                                            >
                                                                            <label class="form-check-label" for="role_{{ $permission->id }}">
                                                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Assign</button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade modal-lg" id="editRole{{ $role->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Role</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form method="POST" action="{{ url('/update-spatie-role/' . $role->id) }}">
                                                    @csrf

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Role Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                   required value="{{ $role->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade modal-lg" id="deleteRole{{ $role->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Role: {{ $role->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <b>Are you sure you want to delete this role?</b>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="{{ url('/destroy-spatie-role/' . $role->id) }}">
                                                        <button class="btn btn-primary">Yes</button>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No Records Found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination (optional) --}}
            {{-- {{ $roles->links('pagination::bootstrap-5') }} --}}

        </div>
    </div>

    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script>

        $(document).ready(function () {

            // Select/Deselect all checkboxes
            $('#checkAll').on('change', function() {
                $('.check').prop('checked', $(this).is(':checked'));
            });

            // Bulk Delete action
            $('.bulk-delete').click(function(e) {
                e.preventDefault();
                let ids = $('.check:checked').map(function() { return $(this).val(); }).get();

                if(ids.length === 0) {
                    alert('Please select at least one role to delete.');
                    return;
                }

                if(confirm('Are you sure you want to delete selected roles permanently? This action cannot be undone.')) {
                    $.ajax({
                        url: '{{ url('/roles-delete-all-bulk-data') }}',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Bulk Move to Trash action
            $('.bulk-move-to-trash').click(function(e) {
                e.preventDefault();
                let ids = $('.check:checked').map(function() { return $(this).val(); }).get();

                if(ids.length === 0) {
                    alert('Please select at least one role to move to trash.');
                    return;
                }

                if(confirm('Are you sure you want to move selected roles to trash?')) {
                    $.ajax({
                        url: '{{ url('/roles-move-to-trash-all-bulk-data') }}',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });

        });
    </script>
@endsection

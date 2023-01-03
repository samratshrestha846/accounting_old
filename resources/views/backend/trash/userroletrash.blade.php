@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Users and Roles (Trashed) </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('user.index') }}" class="global-btn">View Users</a> <a
                            href="{{ route('roles.index') }}" class="global-btn">View Roles</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h2>Users</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive noscroll">
                            <table class="table table-bordered data-table-1 text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Name</th>
                                        <th class="text-nowrap">Email</th>
                                        <th class="text-nowrap">Role</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->users_roles->role->name }}</td>
                                            <td>
                                                @php
                                                    $restoreurl = route('restoreusers', $user->id);
                                                    $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#restoreuser$user->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                <div class='modal fade text-left' id='restoreuser$user->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                    <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                        <div class='modal-content'>
                                                                                                                                                                                                                                            <div class='modal-header'>
                                                                                                                                                                                                                                            <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                                                                                                                                                                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                            <div class='modal-body text-center'>
                                                                                                                                                                                                                                                <label for='reason'>Are you sure you want to restore??</label><br>
                                                                                                                                                                                                                                                <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                            ";
                                                    echo $btn;
                                                @endphp
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">No users trashed yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-sm">
                                            Showing <strong>{{ $users->firstItem() }}</strong> to
                                            <strong>{{ $users->lastItem() }} </strong> of <strong>
                                                {{ $users->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="pagination-sm m-0 float-right">{{ $users->links() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h2>Roles</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive noscroll">
                            <table class="table table-bordered data-table-2 text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Name</th>
                                        <th class="text-nowrap">Permissions</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @php
                                                    $badge = '';
                                                    foreach ($role->permissions as $permission) {
                                                        $badge .= "<span class='badge bg-green mx-1'>" . $permission->name . '</span>';
                                                    }
                                                    echo $badge;
                                                @endphp
                                            </td>

                                            <td>
                                                @php
                                                    $restoreurl = route('restoreroles', $roles->id);
                                                    $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#restoreaccount$roles->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                <div class='modal fade text-left' id='restoreaccount$roles->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                    <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                        <div class='modal-content'>
                                                                                                                                                                                                                                            <div class='modal-header'>
                                                                                                                                                                                                                                            <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                                                                                                                                                                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                            <div class='modal-body text-center'>
                                                                                                                                                                                                                                                <label for='reason'>Are you sure you want to restore??</label><br>
                                                                                                                                                                                                                                                <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                            ";
                                                    return $btn;
                                                @endphp
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No roles trashed yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $roles->firstItem() }}</strong> to
                                            <strong>{{ $roles->lastItem() }} </strong> of <strong>
                                                {{ $roles->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $roles->links() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')

@endpush

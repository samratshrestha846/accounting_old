@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="SEC-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Roles</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('roles.create') }}" class="global-btn">Create Role</a>
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
                    <div class="card-body">
                        <div class="m-0 d-flex justify-content-end">
                            <form class="form-inline" action="{{ route('role.search') }}" method="POST">
                                @csrf
                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive mt noscroll">
                            <table class="table table-bordered data-table text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Permissions</th>
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
                                                <div class="btn-bulk">
                                                    @php
                                                    $editurl = route('roles.edit', $role->id);
                                                    // $deleteurl = route('roles.destroy', $role->id);
                                                    // $csrf_token = csrf_token();
                                                    $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm icon-btn' title='Edit'><i class='fa fa-edit'></i></a>";
                                                    echo $btn;
                                                @endphp
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No roles yet.</td></tr>
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

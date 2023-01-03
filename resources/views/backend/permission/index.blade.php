@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>View Permissions</h1>
                        {{-- <a href="{{ route('permission.create') }}" class="btn btn-sm btn-primary">Create Permission</a> --}}
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Permission</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 ">
                            <div class="m-0 float-right">
                                <form class="form-inline" action="{{route('permission.search')}}" method="POST">
                                    @csrf
                                    <div class="form-group mx-sm-3 mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Column Name</th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{$permission->column_name}}</td>
                                        <td>{{$permission->name}}</td>
                                        <td>{{$permission->slug}}</td>
                                        <td>
                                            @php
                                            $editurl = route('permission.edit', $permission->id);
                                            $deleteurl = route('permission.destroy', $permission->id);
                                            $csrf_token = csrf_token();
                                            $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                <input type='hidden' name='_method' value='DELETE' />
                                                    <button type='submit' class='btn btn-danger btn-sm' title='Delete'><i class='fa fa-trash'></i></button>
                                                </form>
                                            ";
                                            echo $btn;
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $permissions->firstItem() }}</strong> to
                                            <strong>{{ $permissions->lastItem() }} </strong> of <strong>
                                                {{ $permissions->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $permissions->links() }}</span>
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

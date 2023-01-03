@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Outlet Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('outlet.create') }}"
                            class="global-btn">Add New Outlet</a>
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
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('outlet.search') }}" method="POST">
                                        @csrf
                                        @method("POST")
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm icon-btn mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Outlet Name</th>
                                            <th class="text-nowrap">Province</th>
                                            <th class="text-nowrap">District</th>
                                            <th class="text-nowrap">Local Address</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($outlets as $outlet)
                                            <tr>
                                                <td>{{ $outlet->name }}</td>
                                                <td>{{ $outlet->province->eng_name }}</td>
                                                <td>{{ $outlet->district->dist_name }}</td>
                                                <td>{{ $outlet->local_address }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        @php
                                                            $editurl = route('outlet.edit', $outlet->id);
                                                            $deleteurl = route('outlet.destroy', $outlet->id);
                                                            $csrf_token = csrf_token();
                                                            $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletionservice$outlet->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                        <!-- Modal -->
                                                                            <div class='modal fade text-left' id='deletionservice$outlet->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                <div class='modal-dialog' role='document'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                        <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                            <span aria-hidden='true'>&times;</span>
                                                                                        </button>
                                                                                        </div>
                                                                                        <div class='modal-body text-center'>
                                                                                            <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <label for='reason'>Are you sure you want to delete??</label><br>
                                                                                            <input type='hidden' name='_method' value='DELETE' />
                                                                                                <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            ";

                                                            echo $btn;
                                                        @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No outlets yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $outlets->firstItem() }}</strong> to
                                                <strong>{{ $outlets->lastItem() }} </strong> of <strong>
                                                    {{ $outlets->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $outlets->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

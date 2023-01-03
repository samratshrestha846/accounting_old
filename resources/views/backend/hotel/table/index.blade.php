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
                    <h1>Hotel Tables </h1>
                    <div class="btn-bulk">
                        @can('hotel-table-create')
                            <a href="{{ route('hotel-table.create') }}" class="global-btn">Add New Table</a>
                        @endcan
                    </div>
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
                            <div class="col-md-12">
                                <div class="m-0 float-right">

                                    <form class="form-inline" action="" method="">
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered yajra-datatable text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Code</th>
                                            <th class="text-center">Room</th>
                                            <th class="text-center">Floor</th>
                                            <th class="text-center">Max Capacity</th>
                                            <th class="text-center">Is Cabin</th>
                                            <th class="text-center">Cabin Type</th>
                                            <th class="text-center">Cabin Charge</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tables as $table)
                                            <tr>
                                                <td>{{ $table->name }}</td>
                                                <td>{{ $table->code }}</td>
                                                <td>{{ $table->room ? $table->room->name : '-' }}</td>
                                                <td>{{ $table->floor ? $table->floor->name : '-' }}</td>
                                                <td>{{ $table->max_capacity }}</td>
                                                <td>{{ $table->is_cabin ? 'Yes' : 'No' }}</td>
                                                <td>{{ $table->cabin_type ? $table->cabin_type->name : '-' }}</td>
                                                <td>{{ $table->cabin_charge }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        @can('hotel-table-edit')
                                                            <a href='{{ route('hotel-table.edit', $table->id) }}'
                                                                class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                    class='fa fa-edit'></i></a>
                                                        @endcan
                                                        @can('hotel-table-delete')
                                                            <form action='{{ route('hotel-table.destroy', $table->id) }}'
                                                                method='POST' style='display:inline;padding:0;' class='btn'>
                                                                @csrf
                                                                <input type='hidden' name='_method' value='DELETE' />
                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm'
                                                                    title='Delete'
                                                                    onclick="return confirm('Are you sure you want to delete table?')"><i
                                                                        class='fa fa-trash'></i></button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9">No Table yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="position row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $tables->firstItem() }}</strong> to
                                                <strong>{{ $tables->lastItem() }} </strong> of <strong>
                                                    {{ $tables->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $tables->links() }}</span>
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

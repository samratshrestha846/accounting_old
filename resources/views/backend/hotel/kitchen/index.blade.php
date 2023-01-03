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
                    <h1>Hotel Kitchens </h1>
                    <div class="btn-bulk">
                        @can('hotel-kitchen-create')
                            <a href="{{ route('hotel-kitchen.create') }}" class="global-btn">Add New Kitchen</a>
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
                                            <th class="text-center">Room</th>
                                            <th class="text-center">Floor</th>
                                            <th class="text-center">Remarks</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kitchens as $kitchen)
                                            <tr>
                                                <td>{{ $kitchen->kitchen_name }}</td>
                                                <td>{{ $kitchen->room ? $kitchen->room->name : '-' }}</td>
                                                <td>{{ $kitchen->floor ? $kitchen->floor->name : '-' }}</td>
                                                <td>{{ $kitchen->remarks }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        @can('hotel-kitchen-edit')
                                                            <a href='{{ route('hotel-kitchen.edit', $kitchen->id) }}'
                                                                class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                    class='fa fa-edit'></i></a>
                                                        @endcan
                                                        @can('hotel-kitchen-edit')
                                                            <form action='{{ route('hotel-kitchen.destroy', $kitchen->id) }}'
                                                                method='POST' style='display:inline;padding:0;' class='btn'>
                                                                @csrf
                                                                <input type='hidden' name='_method' value='DELETE' />
                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm'
                                                                    title='Delete'
                                                                    onclick="return confirm('Are you sure you want to delete kitchen?')"><i
                                                                        class='fa fa-trash'></i></button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">No Kitchen yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="position row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $kitchens->firstItem() }}</strong> to
                                                <strong>{{ $kitchens->lastItem() }} </strong> of <strong>
                                                    {{ $kitchens->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $kitchens->links() }}</span>
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

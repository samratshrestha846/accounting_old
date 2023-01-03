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
                    <h1>Cabin Type </h1>
                    <div class="btn-bulk">
                        @can('cabin-type-create')
                            <a href="{{ route('cabintype.create') }}" class="global-btn">Add New Cabin Type</a>
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
                                            <th class="text-center">Remarks</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cabin_types as $cabinType)
                                            <tr>
                                                <td>{{ $cabinType->name }}</td>
                                                <td>{{ $cabinType->remarks }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        @can('cabin-type-edit')
                                                            <a href='{{ route('cabintype.edit', $cabinType->id) }}'
                                                                class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                    class='fa fa-edit'></i></a>
                                                        @endcan
                                                        @can('cabin-type-delete')
                                                            <form action='{{ route('cabintype.destroy', $cabinType->id) }}'
                                                                method='POST' style='display:inline;padding:0;' class='btn'>
                                                                @csrf
                                                                <input type='hidden' name='_method' value='DELETE' />
                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm'
                                                                    title='Delete'
                                                                    onclick="return confirm('Are you sure you want to delete floor?')"><i
                                                                        class='fa fa-trash'></i></button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">No Cabin Type yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="position row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $cabin_types->firstItem() }}</strong> to
                                                <strong>{{ $cabin_types->lastItem() }} </strong> of <strong>
                                                    {{ $cabin_types->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $cabin_types->links() }}</span>
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

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
                    <h1>Search Unit </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('unit.create') }}" class="global-btn">Add New Unit</a>
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
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('unit.search') }}" method="POST">
                                        @csrf
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
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Unit Name</th>
                                            <th>Unit Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($units as $unit)
                                            <tr>
                                                <td>{{ $unit->unit }}({{ $unit->short_form }})</td>
                                                <td>{{ $unit->unit_code }}</td>
                                                <td style="width: 120px;">
                                                   <div class="btn-bulk justify-content-center">
                                                    @php
                                                    $editurl = route('unit.edit', $unit->id);
                                                    $deleteurl = route('unit.destroy', $unit->id);
                                                    $csrf_token = csrf_token();
                                                    $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>";
                                                    echo $btn;
                                                @endphp
                                                   </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3">No search results.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $units->firstItem() }}</strong> to
                                                <strong>{{ $units->lastItem() }} </strong> of <strong>
                                                    {{ $units->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $units->links() }}</span>
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

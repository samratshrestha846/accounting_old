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
                    <h1>doctors </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('doctors.create') }}" class="global-btn">Add New doctors</a>
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
                                    <form class="form-inline" action="" method="">
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.n</th>
                                            <th class="text-nowrap">Title</th>
                                            <th class="text-nowrap" colspan="2">Incharge</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                        <tr>
                                            <th class="text-nowrap"></th>
                                            <th class="text-nowrap"></th>
                                            <th class="text-nowrap">Name</th>
                                            <th class="text-nowrap">Email</th>

                                            <th class="text-nowrap"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ '1' }}</td>
                                            <td>{{ $lab->title }}</td>
                                            <td>{{ $lab->incharge->name }}</td>
                                            <td>{{ $lab->incharge->email }}</td>
                                            <td style="width: 120px;">
                                                <div class="btn-bulk justify-content-center">
                                                    <a href='{{ route('lab.edit', $lab->id) }}'
                                                        class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                            class='fa fa-edit'></i></a>
                                                    @include('lab.includes._modal',['id'=>$lab->id,'route'=>route('lab.destroy',$lab->id)])
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

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

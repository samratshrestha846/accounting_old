@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .submit.disabled {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: black;
        }
        .off{
            display: none;
        }
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Add Stock Report of {{$product->product_name}}</h1>
                    <div class="btn-bulk">
                        <a href="javascript:void(0)" onclick="goBack()" class="global-btn">Go Back</a>
                        <a href="{{route('product.addstockcreate', $product->id)}}" class="global-btn">Add New Stocks</a>
                    </div>
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
                @if (session('error'))
                    <div class="col-sm-12">
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">

                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap" scope="col">Godown</th>
                                            <th class="text-nowrap" scope="col">No. of Stock Added
                                            </th>
                                            <th class="text-nowrap" scope="col">Stock Added Date
                                            </th>
                                            <th class="text-nowrap" scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($productstocks as $pstock)
                                       
                                            <tr>
                                                <th scope="row">{{ $pstock->godown->godown_name }}
                                                </th>
                                                <td>{{ $pstock->added_stock }}</td>
                                                <td>{{ $pstock->added_date }}</td>
                                                <td><a href='{{ route('addstock.edit', $pstock->id) }}'
                                                        class='edit btn btn-primary btn-sm mt-1'
                                                        data-toggle='tooltip' data-placement='top'
                                                        title='Edit'><i class='fa fa-edit'></i></a></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">No products.</td></tr>
                                        @endforelse
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
    <script>
        function goBack() {
           window.history.back();
        }
    </script>
@endpush

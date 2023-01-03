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
                    <h1>View Food </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-food.index') }}" class="global-btn">Back</a>&nbsp;
                        @can('hotel-food-edit')
                            <a href="{{ route('hotel-food.edit', $foodDetails) }}" class="btn btn-primary">Edit</a>&nbsp;
                        @endcan
                        @can('hotel-order-sales')
                            <a href="{{ route('hotel-sales-report.sales_report', $foodDetails->id) }}"
                                class="btn btn-secondary">Sales Report</a>
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
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-image-top">
                                        <img src="{{ Storage::disk('uploads')->url($foodDetails->food_image) }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <p><b>Food Name: </b> {{ $foodDetails->food_name }}</p>
                                <p><b>Food Category: </b>{{ $foodDetails->category->category_name }}</p>
                                <p><b>Kitchen :
                                    </b>{{ $foodDetails->kitchen ? $foodDetails->kitchen->kitchen_name : '-' }}</p>
                                <p><b>Price : </b>Rs. {{ $foodDetails->food_price ?? '0' }}</p>
                                <p><b>Cooking Time : </b> {{ date('H:i', strtotime($foodDetails->cooking_time)) }} Hrs
                                </p>
                                <p><b>Status :
                                    </b>{{ $foodDetails->status ? 'Active' : 'Inactive' }}</p>
                            </div>
                            <div class="col-4">
                                <p><b>Component : </b>{{ $foodDetails->component }}</p>
                                <p><b>Description : </b>{{ $foodDetails->description }}</p>
                                <p><b>Notes : </b>{{ $foodDetails->notes }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

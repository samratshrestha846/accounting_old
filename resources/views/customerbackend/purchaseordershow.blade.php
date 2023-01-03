@extends('customerbackend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Purchase Order</h1>
                    <div class="bulk-btn">
                        <a href="{{ route('purchaseOrder.customerindex') }}" class="global-btn">All Purchase Orders</a>
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

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Purchase Order No: {{$clientOrder->order_no}}</h2>
                            </div>
                            <div class="card-body">
                                <p><b>Date: </b>{{ $clientOrder->nep_date }} (in B.S) <br>
                                    {{ $clientOrder->eng_date }} (in A.D)</p>
                                <p><b>Total Qty: </b>{{ $clientOrder->quantity }}</p>
                                <p><b>Remarks: </b>{{$clientOrder->remarks}}</p>
                                <span class="badge badge-{{$clientOrder->is_notified == '1' ? 'success' : "danger"}}">{{$clientOrder->is_notified == '1' ? 'Notified' : "Yet To Mail"}}</span>
                                <a href="{{route('clientpurchaseorder.print', $clientOrder->id)}}" class='edit btn btn-primary icon-btn btn-sm btnprn'  data-toggle='tooltip' data-placement='top' title='Print'><i class='fa fa-print'></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <h2>Lists of Orders</h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clientOrder->client_order_extras as $extra)
                                        <tr>
                                            <td>{{$extra->particulars}}</td>
                                            <td>{{$extra->quantity}}</td>
                                            <td>{{$extra->unit}}</td>
                                        </tr>
                                        @endforeach
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

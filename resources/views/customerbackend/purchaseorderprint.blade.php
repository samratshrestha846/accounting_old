@extends('customerbackend.layouts.app')
@push('styles')
    <style>
        .full {
            width: 100%;
            margin-bottom: 5px;
            height: 200px;
        }

        .left {
            width: 10%;
            float: left;
            height: 100%;
        }

        .right {
            width: 90%;
            float: right;
            height: 100%;
        }

    </style>
@endpush

@section('content')

    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content p-5">
            <div class="container-fluid">
                <div class="ibox">
                    <div class="full">
                        <div class="left">
                            <img src="{{ Storage::disk('uploads')->url($client->logo) }}" alt="{{ $client->name }}"
                                style="height: 120px;">
                        </div>

                        <div class="right">
                            <h1 class="m-0 text-center" style="color: #583949;">{{ $client->name }} </h1><br>
                            <p class="text-center">{{ $client->local_address }}, {{ $client->provinces->eng_name }}
                            </p>
                            <p class="text-center">{{ $client->email }}</p>
                            <p class="text-center">{{ $client->phone }}</p>
                            <b class="text-center" style="background: #583949;
                                            text-align: center;
                                            color: #fff;
                                            display: block;
                                            padding: 5px 8px;
                                            font-size: 9px;
                                            font-weight: 500;
                                            letter-spacing: .3px;
                                            border-radius: 4px;
                                            text-transform: uppercase;
                                            max-width: 93px;
                                            margin-left: auto;
                                            margin-right: auto;">
                            </b>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p><b>Order No: </b>{{ $clientOrder->order_no }}</p>
                                    </div>
                                    <div class="col-4 offset-2">
                                        <p class="text-left"><b>Fiscal-Year: </b>{{ $fiscal_year }}</p>
                                        <p class="text-left"><b>Date:
                                            </b>{{ $clientOrder->eng_date }}/{{ $clientOrder->nep_date }}</p>
                                        <p class="text-left"><b>Printed By: </b>{{ Auth::user()->name }}</p>
                                        <p class="text-left"><b>Printed On: </b>{{ date('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap" style="width: 50%;">Particulars</th>
                                                    <th class="text-nowrap" style="width: 25%;">Quantity</th>
                                                    <th class="text-nowrap" style="width: 25%;">Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($clientOrder->client_order_extras as $extra)
                                                    <tr>
                                                        <td>{{ $extra->particulars }}</td>
                                                        <td>{{ $extra->quantity }}</td>
                                                        <td>{{ $extra->unit }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><b>Remarks: </b>{{ $clientOrder->remarks }}</p>
                                    </div>
                                    <div class="col-3 offset-9">
                                        <p class="text-center p-0 m-0">.......................................</p>
                                        <p class="text-center p-0 m-0">Signature</p>
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
@endsection
@push('scripts')
@endpush

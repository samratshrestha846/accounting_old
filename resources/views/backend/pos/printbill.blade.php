@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .bold{
            font-weight: 400;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <center><img src="{{asset('uploads/logo.png')}}" alt="LekhaBidhi"></center>
            <h1 class="text-center">{{$currentcomp->company->name}}</h1>
            <p class="bold text-center">{{$currentcomp->company->local_address}}, {{$currentcomp->company->districts->dist_name}}, Nepal</p>
            <p class="bold text-center">{{$currentcomp->company->phone}}, {{$currentcomp->company->email}}</p>
            <p class="bold">Date: {{$datas['eng_date']}}/{{$datas['nep_date']}} </p>
            <p class="bold">Customer Name: {{$datas['customer']}}</p>
            <table class="table">
                <thead>
                    <tr>
                        <td>Particulars</td>
                        <td>Rate</td>
                        <td>Qty</td>
                        <td>Discount</td>
                        <td>Tax</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas['billingextras'] as $products)
                    <tr>
                        <td>{{$products['particulars']}}</td>
                        <td>{{$products['rate']}}</td>
                        <td>{{$products['quantity']}}</td>
                        <td>{{$products['dtamt']}}</td>
                        <td>{{$products['itemtax']}}</td>
                        <td>{{$products['total']}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-left">Total</td>
                        <td>{{$datas['totalQty']}}</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>{{$datas['subtotal']}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-left">Bulk Discount</td>
                        <td>{{$datas['bulkDiscount']}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-left">Bulk Tax ({{$datas['bulkTaxType']}})</td>
                        <td>{{$datas['allTaxAmt']}}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-left">Grand Total</td>
                        <td>{{$datas['totalGrossTotal']}}</td>
                    </tr>
                </tfoot>
            </table>
            <p class="text-center">Electronically generated copy</p>
        </div>
        <div class="col-4"></div>
    </div>
@endsection
@push('scripts')
@endpush

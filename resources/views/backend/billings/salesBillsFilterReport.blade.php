<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $billing_type->billing_types }} Bills</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-1" style="float: left;">
                        <img src="{{ $path_img }}" alt="" style="height: 100px;">
                    </div>
                    <div class="col-md-11" style="text-align: center;">
                        <b style="font-size: 30px;display: block;font-weight: 600;margin-bottom: -10px;color: #583949;">{{ $currentcomp->company->name }}</b> <br>
                        {{ $currentcomp->company->local_address }}, {{ $currentcomp->company->provinces->eng_name }} <br>
                        {{$currentcomp->company->email}}<br>
                        {{$currentcomp->company->phone}}
                    </div>
                </div>
                <br><br>

                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <h2>{{ $billing_type->billing_types }} Bills</h2>
                        <h4>For the fiscal year {{ $current_fiscal_year->fiscal_year }} ({{ $starting_date }} to {{ $ending_date }})</h4>
                    </div>
                </div>
                <br>

                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table style="width: 100%">
                            <thead style="background-color: #343a40; color: #ffffff;">
                                <tr>
                                    <th>Date</th>
                                    <th>Reference No.</th>
                                    <th>Transaction No.</th>
                                    @if ($billing_type->id == 1 || $billing_type->id == 6 || $billing_type->id == 7)
                                        <th>Customer</th>
                                    @endif
                                    @if ($billing_type->id == 2 || $billing_type->id == 3 || $billing_type->id == 4 || $billing_type->id == 5)
                                        <th>Supplier</th>
                                    @endif
                                    <th>Payment Mode</th>
                                    <th>Sub Total</th>
                                    <th>Tax Amount</th>
                                    <th>Discount Amount</th>
                                    <th>Shipping</th>
                                    <th>Grand Total</th>
                                    <th>Entried By</th>
                                    <th>Approved by</th>
                                    <th>Edited By</th>
                                    <th>Cancelled By</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($billings as $billing)
                                    <tr>
                                        <td>{{$billing->nep_date}}</td>
                                        <td>
                                            {{$billing->reference_no}}
                                        </td>
                                        <td>
                                            {{$billing->transaction_no}}
                                        </td>
                                        @if ($billing_type->id == 1 || $billing_type->id == 6 || $billing_type->id == 7)
                                            <td>{{ $billing->client_id == null ? '-' : $billing->client->name }}</td>
                                        @endif
                                        @if ($billing_type->id == 2 || $billing_type->id == 3 || $billing_type->id == 4 || $billing_type->id == 5)
                                            <td>{{ $billing->vendor_id == null ? '-' : $billing->suppliers->company_name }}</td>
                                        @endif
                                        <td>
                                            @if ($billing->payment_method == 2)
                                                Cheque ({{ $billing->bank->bank_name }} / Cheque no.: {{ $billing->cheque_no }})
                                            @elseif($billing->payment_method == 3)
                                                Bank Deposit ({{ $billing->bank->bank_name }})
                                            @elseif($billing->payment_method == 4)
                                                Online Portal ({{ $billing->online_portal->name }} / Portal Id: {{ $billing->customer_portal_id }})
                                            @else
                                                Cash
                                            @endif
                                        </td>
                                        <td>Rs. {{$billing->subtotal}}</td>
                                        <td>Rs. {{$billing->taxamount}}</td>
                                        <td>Rs. {{$billing->discountamount}}</td>
                                        <td>Rs. {{$billing->shipping}}</td>
                                        <td>Rs. {{$billing->grandtotal}}</td>
                                        <td>{{$billing->user_entry->name}}</td>
                                        <td>
                                            @if (!$billing->approved_by == null)
                                                {{$billing->user_approve->name}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$billing->edited_by == null)
                                                {{$billing->user_edit->name}}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($billing->is_cancelled == 0)
                                                -
                                            @else
                                                {{$billing->user_cancel->name}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$billing->remarks}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </section>
    <!-- /.content -->

    </body>
</html>

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
                    <h1>{{ $client->name }} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.create') }}" class="global-btn">Add New Customers</a>
                        <a href="{{ route('client.index') }}" class="global-btn">View All Customers</a>
                        <a href="{{ route('client.products', $client->id) }}" class="global-btn">Customers Product</a>
                        <a href="{{ route('clientuser.create', $client->id) }}" class="global-btn">Users Create</a>
                        <a href="{{ route('clientuser.index', $client->id) }}" class="global-btn">Dealers Users</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $client->name }} ({{ ucfirst($client->client_type) }})</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>
                                            <img src="{{ Storage::disk('uploads')->url($client->logo) }}" alt="{{ $client->dealertype->title }}" style="max-height: 100px;" class="img-circle elevation-2">
                                        </p>
                                        <p><b>Dealer Type: </b>{{$client->dealertype->title}}</p>
                                        <p><b>E-mail:</b> {{ $client->email == null ? 'Not Provided' : $client->email }}
                                        </p>
                                        <p><b>Phone:</b> {{ $client->phone == null ? 'Not Provided' : $client->phone }}
                                        </p>
                                        <p><b>Code:</b>
                                            {{ $client->client_code == null ? 'Not Provided' : $client->client_code }}</p>
                                        <p><b>PAN/VAT:</b>
                                            @if ($client->pan_vat == null)
                                                Not Provided
                                            @else
                                                {{ $client->pan_vat }}
                                            @endif
                                        </p>

                                        <p><b>Province:</b>
                                            {{ $client->province == null ? 'Not Provided' : $client->provinces->eng_name }}</p>
                                        <p><b>District:</b>
                                            {{ $client->district == null ? 'Not Provided' : $client->districts->dist_name }}</p>
                                        <p><b>Local Address:</b>
                                            {{ $client->local_address == null ? 'Not Provided' : $client->local_address }}</p>


                                        <a href="{{ route('client.edit', $client->id) }}" class="btn btn-secondary">Edit Customer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist" style="border : 1px solid #e1e6eb; border-bottom : none;">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-1" data-toggle="tab" href="#tab1" role="tab"
                                    aria-controls="tab1" aria-selected="true">Concerned Persons</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-2" data-toggle="tab" href="#tab2" role="tab"
                                    aria-controls="tab2" aria-selected="false">Journals</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-3" data-toggle="tab" href="#tab3" role="tab"
                                    aria-controls="tab3" aria-selected="false">Sales</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-4" data-toggle="tab" href="#tab4" role="tab"
                                    aria-controls="tab4" aria-selected="false">Credit Notes</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-5" data-toggle="tab" href="#tab5" role="tab"
                                    aria-controls="tab5" aria-selected="false">Products</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab-1">
                                <table class="table table-bordered text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone No.</th>
                                            <th>Email</th>
                                            <th>Designation</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($client->clientconcerns as $concern)
                                        <tr>
                                            <td>{{$concern->concerned_name}}</td>
                                            <td>{{$concern->concerned_phone}}</td>
                                            <td>{{$concern->concerned_email}}</td>
                                            <td>{{$concern->designation}}</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  value="{{ $concern->id }}"  class="custom-control-input" id="customSwitch{{ $concern->id }}" @if($concern->default) checked disabled @else  onclick="checkAsDefault(this)" @endif>
                                                    <label class="custom-control-label" for="customSwitch{{ $concern->id }}"></label>
                                                  </div>
                                            </td>
                                            {{-- <td>{{$concern->default == 1 ? '<span class="badge badge-primary">Default</span>' : '<a href="" class="btn btn-warning btn-sm">Make Default</a>'}}</td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab-2">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">JV no.</th>
                                            <th class="text-nowrap">Entry Date</th>
                                            <th class="text-nowrap">Particulars</th>
                                            <th class="text-nowrap">Debit Amount</th>
                                            <th class="text-nowrap">Credit Amount</th>
                                            <th class="text-nowrap">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($journalvouchers as $journalvoucher)
                                            <tr>
                                                <td class="text-nowrap"><a href="{{route('journals.show', $journalvoucher->id)}}">
                                                    {{ $journalvoucher->journal_voucher_no }}</a></td>
                                                <td class="text-nowrap">
                                                    {{ $journalvoucher->entry_date_nepali }}</td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $particulars = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            $particulars = $particulars . $jextra->child_account->title . '<br>';
                                                        }
                                                        echo $particulars;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $debit_amounts = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            if ($jextra->debitAmount == 0) {
                                                                $debit_amounts = $debit_amounts . '-' . '<br>';
                                                            } else {
                                                                $debit_amounts = $debit_amounts . 'Rs. ' . $jextra->debitAmount . '<br>';
                                                            }
                                                        }
                                                        echo $debit_amounts;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        $credit_amounts = '';
                                                        foreach ($journalvoucher->journal_extras as $jextra) {
                                                            if ($jextra->creditAmount == 0) {
                                                                $credit_amounts = $credit_amounts . '-' . '<br>';
                                                            } else {
                                                                $credit_amounts = $credit_amounts . 'Rs. ' . $jextra->creditAmount . '<br>';
                                                            }
                                                        }
                                                        echo $credit_amounts;
                                                    @endphp
                                                </td>
                                                <td class="text-nowrap">
                                                    @php
                                                        if ($journalvoucher->status == '1') {
                                                            $status = 'Approved';
                                                        } else {
                                                            $status = 'Awaiting for Approval';
                                                        }
                                                        echo $status;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8">No journals yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $journalvouchers->firstItem() }}</strong>
                                                to
                                                <strong>{{ $journalvouchers->lastItem() }} </strong> of
                                                <strong>
                                                    {{ $journalvouchers->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $journalvouchers->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab-3">
                                @php
                                    $allsales = [];
                                    $totalpaid = [];
                                    foreach ($salesBillings as $salesBilling) {
                                        $gtotal = round($salesBilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($salesBilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allsales, $gtotal);
                                    }
                                    $totalsales = array_sum($allsales);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalsales - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Sales: Rs.{{ $totalsales }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mt text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Bill Ref. No.</th>
                                                <th class="text-nowrap">Grand Total</th>
                                                <th class="text-nowrap">Total Paid</th>
                                                <th class="text-nowrap">Payment Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($salesBillings as $salesBilling)
                                                <tr>
                                                    <td scope="row">{{ $salesBilling->reference_no }}</td>
                                                    <td>Rs.{{ $salesBilling->grandtotal }}</td>
                                                    @php
                                                        $paid_amount = [];
                                                        $payments = $salesBilling->payment_infos;
                                                        $paymentcount = count($payments);
                                                        for ($x = 0; $x < $paymentcount; $x++) {
                                                            $payment_amount = round($payments[$x]->payment_amount, 2);
                                                            array_push($paid_amount, $payment_amount);
                                                        }
                                                        $totpaid = array_sum($paid_amount);

                                                        $dueamt = round($salesBilling->grandtotal, 2) - $totpaid;
                                                    @endphp
                                                    <td>Rs.{{ $totpaid }}</td>
                                                    <td>RS.{{ $dueamt }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4">No sales yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $salesBillings->firstItem() }}</strong>
                                                to
                                                <strong>{{ $salesBillings->lastItem() }} </strong> of
                                                <strong>
                                                    {{ $salesBillings->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $salesBillings->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('client.sales', $client->id) }}" class="btn btn-primary">View All Sales</a>
                                </h5>
                            </div>

                            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab-4">
                                @php
                                    $allsalesreturn = [];
                                    $totalpaid = [];
                                    foreach ($creditNoteBillings as $creditNoteBilling) {
                                        $gtotal = round($creditNoteBilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($creditNoteBilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allsalesreturn, $gtotal);
                                    }
                                    $totalsalesreturn = array_sum($allsalesreturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalsalesreturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Credit Note:
                                    Rs.{{ $totalsalesreturn }}</span>
                                    <span class="btn global-btn">Total
                                    Paid:
                                    Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due:
                                    Rs.{{ $totaldue }}</span>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mt text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Bill Ref. No.</th>
                                                <th class="text-nowrap">Grand Total</th>
                                                <th class="text-nowrap">Total Paid</th>
                                                <th class="text-nowrap">Payment Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($creditNoteBillings as $creditNoteBilling)
                                                <tr>
                                                    <td scope="row">{{ $creditNoteBilling->reference_no }}</td>
                                                    <td>Rs.{{ $creditNoteBilling->grandtotal }}</td>
                                                    @php
                                                        $paid_amount = [];
                                                        $payments = $creditNoteBilling->payment_infos;
                                                        $paymentcount = count($payments);
                                                        for ($x = 0; $x < $paymentcount; $x++) {
                                                            $payment_amount = round($payments[$x]->payment_amount, 2);
                                                            array_push($paid_amount, $payment_amount);
                                                        }
                                                        $totpaid = array_sum($paid_amount);

                                                        $dueamt = round($creditNoteBilling->grandtotal, 2) - $totpaid;
                                                    @endphp
                                                    <td>Rs.{{ $totpaid }}</td>
                                                    <td>RS.{{ $dueamt }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4">No credit notes yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $creditNoteBillings->firstItem() }}</strong>
                                                to
                                                <strong>{{ $creditNoteBillings->lastItem() }} </strong> of
                                                <strong>
                                                    {{ $creditNoteBillings->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $creditNoteBillings->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('client.creditnote', $client->id) }}" class="btn btn-primary">View All Credit Notes</a>
                                </h5>
                            </div>

                            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab-5">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Product</th>
                                                <th class="text-nowrap">Godown</th>
                                                <th class="text-nowrap">Bill Type</th>
                                                <th class="text-nowrap">Ref.No</th>
                                                <th class="text-nowrap">Quantity</th>
                                                <th class="text-nowrap">Date(Eng)</th>
                                                <th class="text-nowrap">Date(Nep)</th>
                                                <th class="text-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($clientproducts as $product)
                                                <tr>
                                                    <td>{{$product->product_name}}</td>
                                                    <td>{{$product->godown_name}}</td>
                                                    <td>{{$product->billing_types}}</td>
                                                    <td><a href="{{route('billings.show', $product->billing_id)}}">{{$product->reference_no}}</a></td>
                                                    <td>{{$product->quantity}}</td>
                                                    <td>{{$product->eng_date}}</td>
                                                    <td>{{$product->nep_date}}</td>
                                                    <td><span class="badge badge-{{$product->status == 1 ? 'success' : 'danger'}}">{{$product->status == 1 ? 'Approved' : 'Waiting for Approval'}}</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">No products yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <p class="text-sm">
                                                Showing <strong>{{ $clientproducts->firstItem() }}</strong> to
                                                <strong>{{ $clientproducts->lastItem() }} </strong> of <strong>
                                                    {{ $clientproducts->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-5">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $clientproducts->links() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="btn-bulk">
                                    <a href="{{ route('client.products', $client->id) }}" class="btn btn-primary">View All Products</a>
                                </h5>
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
    function checkAsDefault(that)
    {
        var id = $(that).val();
        var client_id = "{{ $client->id }}";

        $.ajax({
          url: "{{ route('customer.makedefault') }}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            concern_id:id,
            client_id:client_id,
          },
          success:function(response){
            console.log(response);
            location.reload();

          },
         });

    }
</script>
@endpush

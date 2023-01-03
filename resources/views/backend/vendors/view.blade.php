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
                    <h1>{{ $vendor->company_name }} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.create') }}" class="global-btn">Add New Suppliers</a>
                        <a href="{{ route('vendors.index') }}" class="global-btn">View All Suppliers</a>
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
                                <h2>{{ $vendor->company_name }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <p>
                                            <img src="{{ Storage::disk('uploads')->url($vendor->logo) }}" alt="{{ $vendor->company_name }}" style="max-height: 100px;" class="img-circle elevation-2">
                                        </p>
                                        <p>
                                            <b>E-mail:</b>
                                            {{ $vendor->company_email == null ? 'Not Provided' : $vendor->company_email }}
                                        </p>
                                        <p>
                                            <b>Phone:</b>
                                            {{ $vendor->company_phone == null ? 'Not Provided' : $vendor->company_phone }}
                                        </p>
                                        <p>
                                            <b>PAN/VAT:</b>
                                            @if ($vendor->pan_vat == null)
                                                Not Provided
                                            @else
                                                {{ $vendor->pan_vat }}
                                            @endif
                                        </p>

                                        <p><b>Province:</b>
                                            {{ $vendor->province_id == null ? 'Not Provided' : $vendor->province->eng_name }}</p>
                                        <p><b>District:</b>
                                            {{ $vendor->district_id == null ? 'Not Provided' : $vendor->district->dist_name }}
                                        </p>
                                        <p><b>Local Address:</b>
                                            {{ $vendor->company_address == null ? 'Not Provided' : $vendor->company_address }}
                                        </p>

                                        {{-- <div class="btn-bulk"> --}}
                                            <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-secondary">Edit Supplier</a>
                                        {{-- </div> --}}
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
                                    aria-controls="tab3" aria-selected="false">Purchase</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-service" data-toggle="tab" href="#tabservice" role="tab"
                                    aria-controls="tabservice" aria-selected="false">Service Purchase</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-4" data-toggle="tab" href="#tab4" role="tab"
                                    aria-controls="tab4" aria-selected="false">Debit Notes</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-5" data-toggle="tab" href="#tab5" role="tab"
                                    aria-controls="tab5" aria-selected="false">Products</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab-1">
                                <table class="table table-bordered">
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
                                        @forelse ($vendor->vendorconcerns as $concern)
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
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No concerned persons.</td>
                                            </tr>
                                        @endforelse
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
                                    $allpurchase = [];
                                    $totalpaid = [];
                                    foreach ($billings as $billing) {
                                        $gtotal = round($billing->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($billing->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchase, $gtotal);
                                    }
                                    $totalpurchase = array_sum($allpurchase);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchase - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Purchase: Rs.{{ $totalpurchase }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
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
                                        @forelse ($billings as $billing)
                                            <tr>
                                                <td scope="row">{{ $billing->reference_no }}</td>
                                                <td>Rs.{{ $billing->grandtotal }}</td>
                                                @php
                                                    $paid_amount = [];
                                                    $payments = $billing->payment_infos;
                                                    $paymentcount = count($payments);
                                                    for ($x = 0; $x < $paymentcount; $x++) {
                                                        $payment_amount = round($payments[$x]->payment_amount, 2);
                                                        array_push($paid_amount, $payment_amount);
                                                    }
                                                    $totpaid = array_sum($paid_amount);

                                                    $dueamt = round($billing->grandtotal, 2) - $totpaid;
                                                @endphp
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No purchase bills.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $billings->firstItem() }}</strong> to
                                                <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                                    {{ $billings->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.purchase', $vendor->id) }}" class="btn btn-primary">View All Purchases</a>
                                </h5>
                            </div>

                            <div class="tab-pane fade" id="tabservice" role="tabpanel" aria-labelledby="tab-3">
                                @php
                                    $allpurchase = [];
                                    $totalpaid = [];
                                    foreach ($billings as $billing) {
                                        $gtotal = round($billing->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($billing->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchase, $gtotal);
                                    }
                                    $totalpurchase = array_sum($allpurchase);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchase - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Purchase: Rs.{{ $totalpurchase }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
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
                                        @forelse ($servicebillings as $billing)
                                            <tr>
                                                <td scope="row">{{ $billing->reference_no }}</td>
                                                <td>Rs.{{ $billing->grandtotal }}</td>

                                                <td>Rs.{{ $billing->payment_amount }}</td>
                                                <td>RS.{{ $billing->grandtotal - $billing->payment_amount }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No purchase bills.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $servicebillings->firstItem() }}</strong> to
                                                <strong>{{ $servicebillings->lastItem() }} </strong> of <strong>
                                                    {{ $servicebillings->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.servicepurchase', $vendor->id) }}" class="btn btn-primary">View All Service Purchases</a>
                                </h5>
                            </div>


                            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab-4">
                                @php
                                    $allpurchasereturn = [];
                                    $totalpaid = [];
                                    foreach ($debitNoteBillings as $debitNotebilling) {
                                        $gtotal = round($debitNotebilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($debitNotebilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchasereturn, $gtotal);
                                    }
                                    $totalpurchasereturn = array_sum($allpurchasereturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchasereturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Debit Note:
                                        Rs.{{ $totalpurchasereturn }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
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
                                        @forelse ($debitNoteBillings as $debitNoteBilling)
                                            <tr>
                                                <td scope="row">{{ $debitNoteBilling->reference_no }}</td>
                                                <td>Rs.{{ $debitNoteBilling->grandtotal }}</td>
                                                @php
                                                    $paid_amount = [];
                                                    $payments = $debitNoteBilling->payment_infos;
                                                    $paymentcount = count($payments);
                                                    for ($x = 0; $x < $paymentcount; $x++) {
                                                        $payment_amount = round($payments[$x]->payment_amount, 2);
                                                        array_push($paid_amount, $payment_amount);
                                                    }
                                                    $totpaid = array_sum($paid_amount);

                                                    $dueamt = round($debitNoteBilling->grandtotal, 2) - $totpaid;
                                                @endphp
                                                <td>Rs.{{ $totpaid }}</td>
                                                <td>RS.{{ $dueamt }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No debit notes yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $debitNoteBillings->firstItem() }}</strong> to
                                                <strong>{{ $debitNoteBillings->lastItem() }} </strong> of <strong>
                                                    {{ $debitNoteBillings->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $debitNoteBillings->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('supplier.debitnote', $vendor->id) }}" class="btn btn-primary">View All Debit Notes</a>
                                </h5>
                            </div>

                            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab-5">
                                @php
                                    $allpurchasereturn = [];
                                    $totalpaid = [];
                                    foreach ($debitNoteBillings as $debitNotebilling) {
                                        $gtotal = round($debitNotebilling->grandtotal, 2);
                                        $singlebillpayments = [];
                                        foreach ($debitNotebilling->payment_infos as $paymentinfo) {
                                            $payments = round($paymentinfo->payment_amount, 2);
                                            array_push($singlebillpayments, $payments);
                                        }
                                        array_push($totalpaid, array_sum($singlebillpayments));
                                        array_push($allpurchasereturn, $gtotal);
                                    }
                                    $totalpurchasereturn = array_sum($allpurchasereturn);
                                    $totalpayment = array_sum($totalpaid);
                                    $totaldue = $totalpurchasereturn - $totalpayment;
                                @endphp
                                <h5 class="btn-bulk">
                                    <span class="btn global-btn">Total Debit Note:
                                        Rs.{{ $totalpurchasereturn }}</span>
                                    <span class="btn global-btn">Total Paid: Rs.{{ $totalpayment }}</span>
                                    <span class="btn global-btn">Total Due: Rs.{{ $totaldue }}</span>
                                </h5>
                                <table class="table table-bordered text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Product</th>
                                            <th class="text-nowrap">Bill Type</th>
                                            <th class="text-nowrap">Ref.No</th>
                                            <th class="text-nowrap">Quantity</th>
                                            <th class="text-nowrap">Date(Eng)</th>
                                            <th class="text-nowrap">Date(Nep)</th>
                                            <th class="text-nowrap">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($vendorproducts as $product)
                                        <tr>
                                            <td>{{$product->product_name}}</td>
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
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $vendorproducts->firstItem() }}</strong> to
                                                <strong>{{ $vendorproducts->lastItem() }} </strong> of <strong>
                                                    {{ $vendorproducts->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $vendorproducts->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="btn-bulk">
                                    <a href="{{ route('vendors.products', $vendor->id) }}" class="btn btn-primary">View All Products</a>
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
        var vendor_id = "{{ $vendor->id }}";

        $.ajax({
          url: "{{ route('supplier.makedefault') }}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            concern_id:id,
            vendor_id:vendor_id,
          },
          success:function(response){
            console.log(response);
            location.reload();

          },
         });

    }
</script>
@endpush

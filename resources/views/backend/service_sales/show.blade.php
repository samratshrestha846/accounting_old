@extends('backend.layouts.app')
@push('styles')

@endpush
{{-- @php
    dd($serviceSaleBill);
@endphp --}}
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    @if($serviceSaleBill->billing_type_id == 1)
                        @php
                            $viewurl =  route('service_sales.index');
                        @endphp
                        <h1>Service Sale Bills => {{ $serviceSaleBill->transaction_no }} </h1>
                    @elseif($serviceSaleBill->billing_type_id == 2)
                        @php
                        $viewurl =  route('service_sales.index',['billing_type_id=2']);
                        @endphp
                        <h1>Service Purchase Bills => {{ $serviceSaleBill->transaction_no }} </h1>
                    @else
                        @php
                        $viewurl =  route('service_sales.index');
                        @endphp
                    @endif
                    <div class="btn-bulk">
                        <a href="{{ $viewurl}}"
                            class="global-btn">View Service Bills</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
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

                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Bill Reference No: {{ $serviceSaleBill->reference_no }} </h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>Transaction No: </b>{{ $serviceSaleBill->transaction_no }}</p>
                                            <p><b>Reference No: </b>{{ $serviceSaleBill->reference_no }}</p>
                                            @if(!$serviceSaleBill->client_id==null)
                                                <p><b>Customer: </b>{{ $serviceSaleBill->client->name ?? '' }}</p>
                                            @endif
                                            @if(!$serviceSaleBill->vendor_id==null)
                                                <p><b>Supplier: </b>{{ $serviceSaleBill->suppliers->company_name ?? '' }}</p>
                                            @endif
                                            <p><b>VAT Bill No.: </b>{{ $serviceSaleBill->ledger_no }}</p>
                                            <p><b>File No: </b>{{ $serviceSaleBill->file_no == null ? "Not Given" : $serviceSaleBill->file_no }}</p>
                                            <p><b>Payment Mode: </b>
                                                @if ($serviceSaleBill->payment_method == 2)
                                                    Cheque ({{ $serviceSaleBill->bank->bank_name ?? '' }} / Cheque no.: {{ $serviceSaleBill->cheque_no }})
                                                @elseif($serviceSaleBill->payment_method == 3)
                                                    Bank Deposit ({{ $serviceSaleBill->bank->bank_name ?? '' }})
                                                @elseif($serviceSaleBill->payment_method == 4)
                                                    Online Portal ({{ $serviceSaleBill->online_portal->name ?? '' }} / Portal Id: {{ $serviceSaleBill->customer_portal_id }})
                                                @else
                                                    Cash
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-right"><b>Fiscal-Year:
                                                </b>{{ $serviceSaleBill->fiscal_year->fiscal_year }}</p>
                                            <p class="text-right"><b>English Date: </b>{{ $serviceSaleBill->eng_date }}</p>
                                            <p class="text-right"><b>Nepali Date: </b>{{ $serviceSaleBill->nep_date }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="text-nowrap">Particulars</th>
                                                        <th class="text-nowrap">Quantity</th>
                                                        <th class="text-nowrap">Rate</th>
                                                        <th class="text-nowrap">Discount(Per Unit)</th>
                                                        <th class="text-nowrap">Tax (Per Unit)</th>
                                                        <th class="text-nowrap">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($serviceSaleBill->serviceSalesExtra as $serviceSaleBillextra)
                                                        <tr>
                                                            <td>
                                                                {{ $serviceSaleBillextra->service->service_name }}
                                                            </td>
                                                            <td>{{ $serviceSaleBillextra->quantity }}</td>
                                                            <td>Rs. {{ $serviceSaleBillextra->rate }}</td>
                                                            <td>
                                                                @if ($serviceSaleBillextra->discountamt == 0)
                                                                    -
                                                                @else
                                                                    Rs. {{ $serviceSaleBillextra->discountamt }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($serviceSaleBillextra->taxamt == 0)
                                                                    -
                                                                @else
                                                                    Rs. {{ $serviceSaleBillextra->taxamt }}
                                                                @endif
                                                            </td>
                                                            <td>Rs. {{ $serviceSaleBillextra->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Sub Total</b></td>
                                                        <td>Rs. {{ $serviceSaleBill->subtotal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>

                                                            <b>Discount Amount {{$serviceSaleBill->alldiscounttype == "percent" ? '('. $serviceSaleBill->alldtamt . '%)' : ''}}</b>
                                                        </td>
                                                        <td>
                                                            @if ($serviceSaleBill->alldiscounttype == "fixed")
                                                                Rs. {{ $serviceSaleBill->discountamount }}
                                                            @elseif ($serviceSaleBill->alldiscounttype == "percent")
                                                                Rs. {{ $serviceSaleBill->discountpercent }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Service Charge</b></td>
                                                        <td>

                                                                Rs. {{ $serviceSaleBill->service_charge }}

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Tax Amount({{ $serviceSaleBill->taxpercent == null ? '0%' : $serviceSaleBill->taxpercent }})</b>
                                                        </td>

                                                        <td>
                                                            @if ($serviceSaleBill->taxamount == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $serviceSaleBill->taxamount }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Shipping</b></td>
                                                        <td>
                                                            @if ($serviceSaleBill->shipping == 0)
                                                                -
                                                            @else
                                                                Rs. {{ $serviceSaleBill->shipping }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Grand Total</b></td>
                                                        <td>Rs. {{ $serviceSaleBill->grandtotal }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><b>Remarks: </b>{{ $serviceSaleBill->remarks }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>Payment Type:
                                                </b>{{ ucwords($serviceSaleBill->payment_type) }}
                                            </p>
                                            <p><b>Paid Amount:
                                                </b>Rs. {{ $serviceSaleBill->payment_amount }}
                                            </p>
                                            <p><b>Entry By: </b>{{ $serviceSaleBill->user_entry->name }}</p>
                                            @if (!$serviceSaleBill->edited_by == null)
                                                <p><b>Edited By:
                                                    </b>{{ $serviceSaleBill->user_edit->name }}</p>
                                            @endif
                                        </div>
                                        <div class="col-6">
                                            @if ($serviceSaleBill->is_cancelled == 0)
                                                <p class="text-right"><b>Status:
                                                    </b>{{ $serviceSaleBill->status == 1 ? 'Approved' : 'Waiting For Approval' }}
                                                </p>
                                                @if (!$serviceSaleBill->approved_by == null)
                                                    <p class="text-right"><b>Approved By:
                                                        </b>{{ $serviceSaleBill->user_approve->name }}</p>
                                                @endif
                                            @else
                                                <p class="text-right"><b>Status: </b>Cancelled</p>
                                                <p class="text-right"><b>Cancelled By:
                                                    </b>{{ $serviceSaleBill->user_cancel->name }}
                                                </p>
                                                @php
                                                    $cancelled_bills = App\Models\CancelledServiceBills::where('sales_bills_id', $serviceSaleBill->id)->first();
                                                @endphp
                                                <p class="text-right">
                                                    <b>Reason for Cancellation:</b> {{ $cancelled_bills->reason }}
                                                </p>

                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <div class="btn-bulk">
                                                <a href="{{ route('service_sales.edit', $serviceSaleBill->id) }}" class="btn btn-primary">Edit</a>
                                                <a href="{{ route('serviceSalesBillPrint', $serviceSaleBill->id) }}" class="btn btn-secondary btnprn">Print</a>
                                                <a href="{{ route('pdf.generateServiceSalesBilling', $serviceSaleBill->id) }}" class="btn btn-primary">Export PDF</a>
                                                <a href="{{ route('sendServiceSaleEmail', $serviceSaleBill->id) }}" class="btn btn-secondary">Send Mail</a>
                                            </div>
                                        </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btnprn').printPage();
        });
    </script>
    <script>
        $('#payment_amount').change(function() {
            var payment_amount = $(this).val();
            var dueamount = $('.dueamount').data('dueamount');
            console.log(dueamount);

            if (parseFloat(payment_amount) > parseFloat(dueamount)) {
                $(this).parent().find('.text-danger').removeClass('off');
                $('.submit').addClass("disabled");
            } else {
                $(this).parent().find('.text-danger').addClass('off');
                $('.submit').removeClass("disabled");
            }
        });
    </script>
@endpush

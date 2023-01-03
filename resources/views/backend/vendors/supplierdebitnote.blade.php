@extends('backend.layouts.app')
@push('styles')
<style>
    * {
      box-sizing: border-box;
    }

    #myInput {
      background-image: url('/uploads/search.png');
      background-position: 10px 10px;
      background-repeat: no-repeat;
      width: 100%;
      font-size: 13px;
      padding: 12px 20px 12px 40px;
      border: 1px solid #e1e6eb;
      margin-bottom: 12px;
    }
</style>
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Debit Note from Supplier Report </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.index') }}" class="global-btn">View
                            All Suppliers</a> <a href="{{ route('supplier.purchase', $supplier->id) }}"
                            class="global-btn">Purchase from
                            Suppliers</a>
                            <a href="{{ route('vendors.show',$supplier->id) }}" class="global-btn">Back</a>
                    </div><!-- /.col -->
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
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $supplier->company_name }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        @php
                                            $allpurchasereturn = [];
                                            $totalpaid = [];
                                            foreach ($billings as $billing) {
                                                $gtotal = round($billing->grandtotal, 2);
                                                $singlebillpayments = [];
                                                foreach ($billing->payment_infos as $paymentinfo) {
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
                                            <span class="btn btn-primary">Total Debit Note:
                                                Rs.{{ $totalpurchasereturn }}</span>
                                            <span class="btn btn-secondary">Total Paid: Rs.{{ $totalpayment }}</span>
                                            <span class="btn btn-primary">Total Due: Rs.{{ $totaldue }}</span>
                                        </h5>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Reference No." title="Type in a name">
                                    </div>
                                </div>

                                <table class="table table-bordered mt text-center" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Bill Ref. No.</th>
                                            <th class="text-nowrap">Grand Total</th>
                                            <th class="text-nowrap">Total Paid</th>
                                            <th class="text-nowrap">Payment Due</th>
                                            <th class="text-nowrap">Action</th>
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
                                                <td>
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('billings.show', $billing->id) }}"
                                                            class="btn btn-primary icon-btn" target="_blank"><i
                                                                class="fas fa-file-invoice-dollar" data-toggle='tooltip'
                                                                data-placement='top' title='View Bill'></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-secondary icon-btn"
                                                            data-toggle="modal"
                                                            data-target="#payment_details{{ $billing->id }}"
                                                            data-toggle='tooltip' data-placement='top' title='View Payments'><i
                                                                class="fas fa-money-bill"></i></a>
                                                    </div>
                                                    <div class="modal fade" id="payment_details{{ $billing->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="payment_details{{ $billing->id }}Label"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="payment_details{{ $billing->id }}Label">
                                                                        {{ $billing->reference_no }}</h5>

                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
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
                                                                    <p><span class="badge badge-primary mr-2 p-1"><b>Total
                                                                                Amount:
                                                                            </b>Rs.{{ $billing->grandtotal }}</span>
                                                                        <span class="badge badge-success mr-2 p-1"><b>Paid
                                                                                Amount:
                                                                            </b>Rs.{{ $totpaid }}</span><span
                                                                            class="badge badge-danger dueamount mr-2 p-1"
                                                                            data-dueamount="{{ $dueamt }}"><b>Due
                                                                                Amount: </b>Rs.{{ $dueamt }}</span>
                                                                    </p>
                                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                        <li class="nav-item">
                                                                            <a class="nav-link active"
                                                                                id="details{{ $billing->id }}-tab"
                                                                                data-toggle="tab"
                                                                                href="#details{{ $billing->id }}"
                                                                                role="tab"
                                                                                aria-controls="details{{ $billing->id }}"
                                                                                aria-selected="true">Details</a>
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                id="create{{ $billing->id }}-tab"
                                                                                data-toggle="tab"
                                                                                href="#create{{ $billing->id }}"
                                                                                role="tab"
                                                                                aria-controls="create{{ $billing->id }}"
                                                                                aria-selected="false">Create Payment</a>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="tab-content" id="myTabContent">
                                                                        <div class="tab-pane fade show active"
                                                                            id="details{{ $billing->id }}"
                                                                            role="tabpanel"
                                                                            aria-labelledby="details{{ $billing->id }}-tab">
                                                                            <div class="container p-3">
                                                                                <table class="table table-bordered">
                                                                                    <thead class="thead-light">
                                                                                        <tr>
                                                                                            <th scope="col">Payment Date
                                                                                            </th>
                                                                                            <th scope="col">Payment Type
                                                                                            </th>
                                                                                            <th scope="col">Amount</th>
                                                                                            <th scope="col">Paid To</th>
                                                                                            <th scope="col">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @forelse ($billing->payment_infos as $pinfo)
                                                                                            <tr>
                                                                                                <th scope="row">
                                                                                                    {{ $pinfo->payment_date }}
                                                                                                </th>
                                                                                                <td>
                                                                                                    @php
                                                                                                        if ($pinfo->payment_type == 'paid') {
                                                                                                            echo 'Paid';
                                                                                                        } elseif ($pinfo->payment_type == 'unpaid') {
                                                                                                            echo 'Unpaid';
                                                                                                        } elseif ($pinfo->payment_type == 'partially_paid') {
                                                                                                            echo 'Partially Paid';
                                                                                                        }
                                                                                                    @endphp
                                                                                                </td>
                                                                                                <td>{{ $pinfo->payment_amount }}
                                                                                                </td>
                                                                                                <td>{{ $pinfo->user_entry->name }}
                                                                                                </td>
                                                                                                <td><a href='{{ route('paymentinfo.edit', $pinfo->id) }}'
                                                                                                        class='edit btn btn-primary btn-sm mt-1'
                                                                                                        data-toggle='tooltip'
                                                                                                        data-placement='top'
                                                                                                        title='Edit'><i
                                                                                                            class='fa fa-edit'></i></a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @empty
                                                                                            <tr><td colspan="5">No any data.</td></tr>
                                                                                        @endforelse
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane fade"
                                                                            id="create{{ $billing->id }}" role="tabpanel"
                                                                            aria-labelledby="create{{ $billing->id }}-tab">
                                                                            <div class="container p-3">
                                                                                @if ($dueamt <= 0)
                                                                                    <p class="bold">Fully Paid</p>
                                                                                @else
                                                                                    <form
                                                                                        action="{{ route('paymentinfo.store') }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        <div class="row">
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <input type="hidden"
                                                                                                        name="billing_id"
                                                                                                        value="{{ $billing->id }}">
                                                                                                    <label
                                                                                                        for="payment_type">Payment
                                                                                                        Type</label>
                                                                                                    <select
                                                                                                        name="payment_type"
                                                                                                        id="payme€nt_type"
                                                                                                        class="form-control"
                                                                                                        required>
                                                                                                        <option
                                                                                                            value="paid">
                                                                                                            Paid</option>
                                                                                                        <option
                                                                                                            value="partially_paid">
                                                                                                            Partially Paid
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="unpaid">
                                                                                                            Unpaid</option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label
                                                                                                        for="payment_date">Payment
                                                                                                        Date</label>
                                                                                                    <input type="date"
                                                                                                        value="{{ date('Y-m-d') }}"
                                                                                                        id="payment_date"
                                                                                                        name="payment_date"
                                                                                                        class="form-control"
                                                                                                        required>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="form-group">
                                                                                                    <label
                                                                                                        for="payment_amount">Payment
                                                                                                        Amount</label>
                                                                                                    <input type="text"
                                                                                                        name="payment_amount"
                                                                                                        id="payment_amount"
                                                                                                        class="form-control"
                                                                                                        placeholder="Enter Paid Amount"
                                                                                                        required>
                                                                                                    <p
                                                                                                        class="off text-danger">
                                                                                                        Payment can't be
                                                                                                        more than that of
                                                                                                        Due Amount</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary submit">Submit</button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5">No debit notes yet.</td></tr>
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
    <script>
        function myFunction()
        {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++)
            {
                td = tr[i].getElementsByTagName("td")[0];
                if (td)
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1)
                    {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

@endpush

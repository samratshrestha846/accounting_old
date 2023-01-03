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
                    <h1>Sales Invoice Bills </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('salesInvoiceCreate') }}" class="global-btn">Create Sales
                            Invoice Bills</a>
                    </div>
                    <!-- /.col -->
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Sales Invoice Bills</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered data-table text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Billing Type</th>
                                                    <th class="text-nowrap">Customer</th>
                                                    <th class="text-nowrap">Reference No</th>
                                                    <th class="text-nowrap">VAT Bill No</th>
                                                    <th class="text-nowrap">Due Date</th>
                                                    <th class="text-nowrap">Paid Amount</th>
                                                    <th class="text-nowrap">Grand Total</th>
                                                    <th class="text-nowrap">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($billings as $billing)
                                                    <tr>
                                                        <td>{{ $billing->billing_types->billing_types }} Invoice</td>
                                                        <td>{{ $billing->client_id == null ? '-' : $billing->client->name }}
                                                        </td>
                                                        <td>{{ $billing->reference_no }}</td>
                                                        <td>{{ $billing->ledger_no }}</td>
                                                        <td>{{ $billing->payment_infos[0]->due_date }}</td>
                                                        <td>Rs. {{ $billing->payment_infos[0]->total_paid_amount }}</td>
                                                        <td>Rs.{{ $billing->grandtotal }}</td>
                                                        <td style="width: 120px;">
                                                            <div class="btn-bulk justify-content-center">
                                                                <a href="{{ route('pdf.generateSalesInvoice', $billing->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                                @php
                                                                    $showurl = route('showSalesInvoice', $billing->id);
                                                                    $editurl = route('salesInvoiceEdit', $billing->id);
                                                                    $csrf_token = csrf_token();

                                                                    $changetoBill = route('convertToBill', $billing->id);
                                                                    $btn = "
                                                                                <a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                <a href='$changetoBill' class='edit btn btn-primary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Change this to Bill'><i class='fas fa-exchange-alt'></i></a>
                                                                            ";
                                                                    echo $btn;
                                                                @endphp
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8">No invoices yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
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
    <script>
        $(function() {
            var all = $('input.select_all');
            all.change(function() {
                var select = $('input.select');
                if (this.checked) {
                    select.prop('checked', true);
                } else {
                    select.prop('checked', false);
                }

            })
            var selectval = [];
            $('.clicked').click(function(event) {
                event.preventDefault();
                $('.select:checked').each(function(i) {
                    selectval[i] = $(this).val();
                })

                $.ajax({
                    url: "{{ route('billings.unapprove') }}",
                    type: 'POST',
                    dataType: 'json',
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/json',
                    data: {
                        id: selectval,
                    },
                    beforeSend: function(xmlhttp) {
                        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xmlhttp.setRequestHeader('Content-type',
                            'application/x-www-form-urlencoded; charset=UTF-8');
                    },
                    success: function(response) {
                        alert("You will now be redirected.");
                        window.location = "{{ route('billings.unapproved', $billingtype) }}";
                    },
                });
            })

            $('.pdfbilled').click(function(event) {
                var billedval = [];
                event.preventDefault();
                $('.select:checked').each(function(i) {
                    billedval[i] = $(this).val();
                })
                $('input.selectedid').val(billedval);
                $("#exportpdf").submit();
            })
            $('.csvbilled').click(function(event) {
                var billedval = [];
                event.preventDefault();
                $('.select:checked').each(function(i) {
                    billedval[i] = $(this).val();
                })
                $('input.selectedcsvid').val(billedval);
                $("#exportcsv").submit();
            })
        })
    </script>

    <script>
        $(function() {
            $('.fiscal').change(function() {
                var fiscal_year = $(this).children("option:selected").val();
                var array_date = fiscal_year.split("/");

                var starting_date = document.getElementById("starting_date");
                var starting_full_date = array_date[0] + '-04-01';
                starting_date.value = starting_full_date;
                starting_date.nepaliDatePicker();

                var ending_date = document.getElementById("ending_date");
                var ending_year = array_date[1];
                var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
                var ending_full_date = ending_year + '-03-' + days_count;
                ending_date.value = ending_full_date;

                ending_date.nepaliDatePicker();
            })
        })
    </script>

    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            var ending_year = "{{ $actual_year[1] }}";

            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            starting_date.nepaliDatePicker();

            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;

            ending_date.nepaliDatePicker();
        };
    </script>
@endpush

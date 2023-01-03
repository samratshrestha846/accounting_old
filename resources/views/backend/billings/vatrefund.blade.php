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
                    <h1>VAT Refund View  </h1>

                    <div class="btn-bulk">
                        <a  class="btn btn-primary mr-1">Total Vat Refund:{{$grandtotal}}</a>
                        <a href="{{ route('billings.report', 1) }}" class="global-btn">View Sales</a>
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

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('billings.vatrefundsearch') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Customer's PAN/VAT</th>
                                            <th class="text-nowrap">Invoice No</th>
                                            <th class="text-nowrap">Payment Date</th>
                                            <th class="text-nowrap">Total Amount</th>
                                            <th class="text-nowrap">VAT Amount</th>
                                            <th class="text-nowrap">10% of VAT</th>
                                            <th class="text-nowrap">Customer Id</th>
                                            <th class="text-nowrap">Transaction No</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($billings as $billing)
                                            <tr>
                                                <td>{{ $billing->client->pan_vat == null ? 'Not Provided' : $billing->client->pan_vat }}
                                                </td>
                                                <td>{{ $billing->reference_no }}</td>
                                                <td>{{ $billing->nep_date }}</td>
                                                <td>{{ $billing->grandtotal }}</td>
                                                <td>{{ $billing->vat_refundable }}</td>
                                                <td>{{ $billing->taxamount * 0.1 }}</td>
                                                <td>{{ $billing->client->client_code == null ? 'Not Provided' : $billing->client->client_code }}
                                                </td>
                                                <td>{{ $billing->transaction_no }}</td>
                                                <td>
                                                    @php
                                                        $showurl = route('billings.show', $billing->id);
                                                        $btn = "<a href='$showurl' class='edit btn btn-success btn-sm mt-1'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";

                                                        echo $btn;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="9">No data yet.</td></tr>
                                        @endforelse
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

    {{-- <script>
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
</script> --}}
@endpush

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
              @if ($billingtype->slug == "debit-note" || $billingtype->slug == "credit-note")
              <h1>CBMS Materialized {{$billingtype->billing_types}} View </h1>
                @else
                @php
                $slug = $billingtype->slug;
                $explode = explode('-', $slug);
                //   dd($explode);
                $combine = '';
                $count = count($explode);
                for($x = 0; $x<$count; $x++){
                    $combine = $combine.$explode[$x];
                }
                $url = 'billings.'.$combine.'create';


            //   dd($url);
              @endphp
            <h1>CBMS Materialized {{$billingtype->billing_types}} View </h1>
              @endif
          <div class="btn-bulk">
            @if ($billingtype->slug == "debit-note" || $billingtype->slug == "credit-note")
              <button onclick="goBack()" class="global-btn">Go Back</button>
                @else
                @php
                $slug = $billingtype->slug;
                $explode = explode('-', $slug);
                //   dd($explode);
                $combine = '';
                $count = count($explode);
                for($x = 0; $x<$count; $x++){
                    $combine = $combine.$explode[$x];
                }
                $url = 'billings.'.$combine.'create';


            //   dd($url);
              @endphp
            <button onclick="goBack()" class="global-btn">Go Back</button>
              @endif
          </div><!-- /.col -->
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
                            <form class="form-inline" action="{{route('billings.cbmssearch', $billingtype->id)}}" method="POST">
                                @csrf
                                <div class="form-group mx-sm-3">
                                <label for="search" class="sr-only">Search</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive mt">
                        <table class="table table-bordered data-table text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-nowrap">Fiscal Year</th>
                                    <th class="text-nowrap">Reference No</th>
                                    @if ($billingtype->id == 2 || $billingtype->id == 5)
                                        <th class="text-nowrap">Party Bill No</th>
                                    @else
                                        <th class="text-nowrap">VAT Bill No</th>
                                    @endif
                                    @if ($billingtype->id == 1 || $billingtype->id == 6 || $billingtype->id == 7)
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Customer PAN/VAT</th>
                                    @endif
                                    @if ($billingtype->id == 2 || $billingtype->id == 3 || $billingtype->id == 4 || $billingtype->id == 5 )
                                    <th class="text-nowrap">Supplier</th>
                                    <th class="text-nowrap">Supplier PAN/VAT</th>
                                    @endif
                                    <th class="text-nowrap">Bill Miti</th>
                                    <th class="text-nowrap">Amount</th>
                                    <th class="text-nowrap">Discount</th>
                                    <th class="text-nowrap">Taxable Amount</th>
                                    <th class="text-nowrap">Tax Amount</th>
                                    <th class="text-nowrap">Shipping Cost</th>
                                    <th class="text-nowrap">Total Amount</th>
                                    <th class="text-nowrap">Sync with IRD</th>
                                    <th class="text-nowrap">Is Printed</th>
                                    <th class="text-nowrap">Is Active</th>
                                    <th class="text-nowrap">Printed Time</th>
                                    <th class="text-nowrap">Entry By</th>
                                    <th class="text-nowrap">Printed By</th>
                                    <th class="text-nowrap">Is Realtime</th>
                                    <th class="text-nowrap">Payment Mode</th>
                                    <th class="text-nowrap">VAT Refund Amount(if Any)</th>
                                    <th class="text-nowrap">Transaction ID(If Any)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($billings as $billing)
                                <tr>
                                    <td>{{$billing->fiscal_years->fiscal_year}}</td>
                                    <td>{{$billing->reference_no}}</td>
                                    <td>{{ $billing->ledger_no }}</td>
                                    @if ($billing->billing_type_id == 1 || $billing->billing_type_id == 6 || $billing->billing_type_id == 7)
                                    <td>{{$billing->client_id == null ? 'Not Indicated' : $billing->client->name}}</td>
                                    <td>{{$billing->client_id == null ? 'Not Indicated' : $billing->client->pan_vat}}</td>
                                    @endif
                                    @if ($billing->billing_type_id == 2 || $billing->billing_type_id == 3 || $billing->billing_type_id == 4 || $billing->billing_type_id == 5 )
                                    <td>{{$billing->vendor_id == null ? "Not Indicated" : $billing->suppliers->company_name}}</td>
                                    <td>{{$billing->vendor_id == null ? "Not Indicated" : $billing->suppliers->pan_vat}}</td>
                                    @endif
                                    <td>{{$billing->nep_date}}</td>
                                    <td>{{$billing->subtotal}}</td>
                                    <td>{{$billing->discountamount}}</td>
                                    <td>
                                        @php
                                            $billing_extras = $billing->billingextras;
                                            $taxable_amount = 0;
                                            foreach($billing_extras as $extra){
                                                if(!$extra->itemtax == 0 || !$extra->itemtax == null){
                                                    $taxable_amount += $extra->total;
                                                }
                                            }
                                            echo $taxable_amount;
                                        @endphp
                                    </td>
                                    <td>{{$billing->taxamount}}</td>
                                    <td>{{$billing->shipping}}</td>
                                    <td>{{$billing->grandtotal}}</td>
                                    <td>{{$billing->sync_ird == 1 ? "Yes" : "No"}}</td>
                                    <td>{{$billing->is_printed == 1 ? "Yes" : "No"}}</td>
                                    <td>{{$billing->is_active == 1 ? "Yes" : "No"}}</td>
                                    <td>
                                        @php
                                            if(count($billing->billprint) > 0){
                                                $count = count($billing->billprint) - 1;
                                                echo date('Y-m-d', strtotime($billing->billprint[$count]->print_time));
                                            }else{
                                                echo "Not Printed";
                                            }
                                        @endphp
                                    </td>
                                    <td>{{$billing->user_entry->name}}</td>
                                    <td>
                                        @php
                                            if(count($billing->billprint) > 0){
                                                $count = count($billing->billprint) - 1;
                                                echo $billing->billprint[$count]->print_by->name;
                                            }else{
                                                echo "Not Printed";
                                            }
                                        @endphp
                                    </td>
                                    <td>{{$billing->is_realtime == 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$billing->payment_modes->payment_mode}}</td>
                                    <td>{{$billing->vat_refundable}}</td>
                                    <td>{{$billing->transaction_no}}</td>
                                </tr>
                                @empty
                                    <tr><td colspan="21">No bills yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-sm">
                                        Showing <strong>{{ $billings->firstItem() }}</strong> to
                                        <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                            {{ $billings->total() }}</strong>
                                        entries
                                        <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                            render</span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <span class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
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
    function goBack() {
      window.history.back();
    }
    </script>
  @endpush

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
                <h1>Search {{$billingtype->billing_types}} Return Register Bills </h1>
                <div class="btn-bulk">
                    <a href="{{route('billings.report', $billing_type_id)}}" class="global-btn">View {{$billingtype->billing_types}}</a>
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
                <div class="col-sm-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="col-sm-12">
                    <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-0 float-right">
                                <form class="form-inline" action="{{route('salesReturnRegister.search')}}" method="POST">
                                    @csrf
                                    <div class="form-group mx-sm-3 mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered data-table text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Bill Date</th>
                                        <th class="text-nowrap">Supplier</th>
                                        <th class="text-nowrap">Supplier Adress</th>
                                        <th class="text-nowrap">PAN / VAT</th>
                                        <th class="text-nowrap">Reference No</th>
                                        <th class="text-nowrap">VAT Bill No</th>
                                        <th class="text-nowrap">Transaction No</th>
                                        <th class="text-nowrap">Tax Amount</th>
                                        <th class="text-nowrap">Grand Total</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($billings as $billing)
                                    <tr>
                                        <td class="text-nowrap">
                                            {{$billing->nep_date}}(in B.S) <br>
                                            {{$billing->eng_date}}(in A.D)
                                        </td>
                                        <td class="text-nowrap">{{ $billing->client->name }}</td>
                                        <td class="text-nowrap">{{ $billing->client->local_address }}</td>
                                        <td class="text-nowrap">
                                            @if ($billing->client->pan_vat == null)
                                                Not Provided
                                            @else
                                                {{ $billing->client->pan_vat }}
                                            @endif
                                        </td>
                                        <td class="text-nowrap">{{$billing->reference_no}}</td>
                                        <td class="text-nowrap">{{$billing->ledger_no}}</td>
                                        <td class="text-nowrap">{{$billing->transaction_no}}</td>
                                        <td class="text-nowrap">Rs. {{$billing->taxamount}}</td>
                                        <td class="text-nowrap">Rs. {{$billing->grandtotal}}</td>
                                        <td class="text-nowrap">
                                            @php
                                                $showurl = route('billings.show', $billing->id);
                                                $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>";

                                                echo $btn;
                                            @endphp
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No data yet.</td>
                                        </tr>
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
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-7">
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

  @endpush

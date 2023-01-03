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
                    <h1>Transfer Products Report</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('product.index') }}" class="global-btn">View Products</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="col-sm-12">
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
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
                <div class="card-header text-center">
                    <h2>Generate Report</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('generateTransferReport') }}" method="POST">
                        @csrf
                        @method("POST")
                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Starting date</label>
                                    <input type="text" name="starting_date" class="form-control startdate" id="starting_date" value="{{ $nepali_date }}">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Ending date</label>
                                    <input type="text" name="ending_date" class="form-control enddate" id="ending_date" value="{{ $nepali_date }}">
                                </div>
                            </div>

                            <div class="col-md-2 mt-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-center">
                    <h2>Transfer Report</h2>
                    <h2>From {{ $starting_date }} to {{ $ending_date }}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-0 float-right">
                                <form class="form-inline" action="{{route('transfer.search')}}" method="POST">
                                    @csrf
                                    <div class="form-group mx-sm-3 mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by date.." required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-12 table-responsive mt-3">
                            <table class="table table-bordered data-table text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Date</th>
                                        <th class="text-nowrap">Product Transfered</th>
                                        <th class="text-nowrap">Transfered From</th>
                                        <th class="text-nowrap">Transfered To</th>
                                        <th class="text-nowrap">Transfered By</th>
                                        <th class="text-nowrap">Stock Transfered</th>
                                        <th class="text-nowrap">Remarks</th>
                                        <th class="text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transfered_products as $transfered_product)
                                        @php
                                            $product = \App\Models\Product::findorFail($transfered_product->id);
                                            $transfered_from_godown = \App\Models\Godown::findorFail($transfered_product->transfer_from);
                                            $transfered_to_godown = \App\Models\Godown::findorFail($transfered_product->transfer_to);
                                            $transfered_by = \App\Models\User::findorFail($transfered_product->transfered_by);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $transfered_product->transfered_nep_date }} (in B.S) <br>
                                                {{ $transfered_product->transfered_eng_date }} (in A.D) <br>
                                            </td>
                                            <td>
                                                {{ $product->product_name }} <br>
                                                (Code: {{ $product->product_code }})
                                            </td>
                                            <td>
                                                {{ $transfered_from_godown->godown_name }}
                                            </td>
                                            <td>
                                                {{ $transfered_to_godown->godown_name }}
                                            </td>
                                            <td>
                                                {{ $transfered_by->name }}
                                            </td>
                                            <td>
                                                {{ $transfered_product->stock }} {{ $product->primary_unit }} <br>
                                                ({{ $product->primary_number }} {{ $product->primary_unit }} = {{ $product->secondary_number }} {{ $product->secondary_unit }})
                                            </td>
                                            <td>{{ $transfered_product->remarks }}</td>
                                            <td>
                                                @php
                                                    $showurl = route('product.show', $product->id);
                                                    $btn = "<a href='$showurl' class='edit btn btn-primary btn-sm' title='View Product' target='_blank'><i class='fa fa-eye'></i></a>";

                                                    echo $btn;
                                                @endphp
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8">No any products.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-sm">
                                            Showing <strong>{{ $transfered_products->firstItem() }}</strong> to
                                            <strong>{{ $transfered_products->lastItem() }} </strong> of <strong>
                                                {{ $transfered_products->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="pagination-sm m-0 float-right">{{ $transfered_products->links() }}</span>
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
      document.getElementById('starting_date').nepaliDatePicker();
      document.getElementById('ending_date').nepaliDatePicker();
  </script>
  @endpush

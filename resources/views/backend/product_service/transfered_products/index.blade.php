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
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
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
                <div class="card-header">
                    <h2>Generate Report</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('generateTransferReport') }}" method="POST">
                        @csrf
                        @method("POST")
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Starting date</label>
                                    <input type="text" name="starting_date" class="form-control startdate" id="starting_date" value="{{ old('starting_date', $nepali_date) }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Ending date</label>
                                    <input type="text" name="ending_date" class="form-control enddate" id="ending_date" value="{{ old('ending_date', $nepali_date) }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                            <div style="display: flex;justify-content:flex-end;">
                                <form class="form-inline" action="{{route('transfer.search')}}" method="POST">
                                    @csrf
                                    <div class="form-group mx-sm-3 mb-2">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by date.." required>
                                    </div>
                                    <button type="submit" class="btn btn-primary icon-btn mb-2"><i class="fa fa-search"></i></button>
                                </form>
                            </div>

                        <div class="table-responsive mt-2 noscroll">
                            <table class="table table-bordered data-table text-center global-table">
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
                                            <td class="text-nowrap">
                                                {{ $transfered_product->transfered_nep_date }} (in B.S) <br>
                                                {{ $transfered_product->transfered_eng_date }} (in A.D) <br>
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $product->product_name }} <br>
                                                (Code: {{ $product->product_code }})
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $transfered_from_godown->godown_name }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $transfered_to_godown->godown_name }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $transfered_by->name }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ $transfered_product->stock }} {{ $product->primary_unit }} <br>
                                                ({{ $product->primary_number }} {{ $product->primary_unit }} = {{ $product->secondary_number }} {{ $product->secondary_unit }})
                                            </td>
                                            <td class="text-nowrap">{{ $transfered_product->remarks }}</td>
                                            <td class="text-nowrap">
                                                @php
                                                    $showurl = route('product.show', $product->id);
                                                    $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn' title='View Product' target='_blank'><i class='fa fa-eye'></i></a>";

                                                    echo $btn;
                                                @endphp
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No any products.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $transfered_products->firstItem() }}</strong> to
                                            <strong>{{ $transfered_products->lastItem() }} </strong> of <strong>
                                                {{ $transfered_products->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $transfered_products->links() }}</span>
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

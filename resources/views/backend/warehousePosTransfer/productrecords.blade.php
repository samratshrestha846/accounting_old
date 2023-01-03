@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Products Records of {{$outlet}} </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('outlettransfer.create') }}" class="global-btn">Create Transfers</a>
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
                    <div class="card-body table-responsive">
                        {{-- <div class="col-md-12 ">
                            <div class="m-0 float-right">
                                <form class="form-inline" action="{{ route('user.search') }}" method="POST">
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
                        </div> --}}
                        <table class="table table-bordered data-table text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Godown</th>
                                    <th>Transferred Stock</th>
                                    <th>Transferred English Date</th>
                                    <th>Transferred Nepali Date</th>
                                    <th>Transferred By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($outletproducts as $outletproduct)
                                    <tr>
                                        <td>{{ $outletproduct->product->product_name }}</td>
                                        <td>{{ $outletproduct->godown->godown_name }}</td>
                                        <td>{{ $outletproduct->stock }}</td>
                                        <td>{{ $outletproduct->transfer_eng_date }}</td>
                                        <td>{{ $outletproduct->transfer_nep_date }}</td>
                                        <td>{{ $outletproduct->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">Products not transferred yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-sm">
                                        Showing <strong>{{ $outletproducts->firstItem() }}</strong> to
                                        <strong>{{ $outletproducts->lastItem() }} </strong> of <strong>
                                            {{ $outletproducts->total() }}</strong>
                                        entries
                                        <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                            render</span>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <span class="pagination-sm m-0 float-right">{{ $outletproducts->links() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
@endpush

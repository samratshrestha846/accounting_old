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
                    <h1>All Offers</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('offer.create') }}" class="global-btn">Create New Offer</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">

                            <div class="card">
                                <div class="card-body">
                                    {{-- <div class="m-0 d-flex justify-content-end">
                                        <form class="form-inline" action="#" method="POST">
                                            @csrf
                                            <div class="form-group mx-sm-3">
                                                <label for="search" class="sr-only">Search</label>
                                                <input type="text" class="form-control" id="search" name="search"
                                                    placeholder="Search">
                                            </div>
                                            <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                    class="fa fa-search"></i></button>
                                        </form>
                                    </div> --}}
                                    <div class="table-responsive mt noscroll">
                                        <table class="table table-bordered data-table text-center global-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Offer Name</th>
                                                    <th class="text-nowrap">Offered Percent</th>
                                                    <th class="text-nowrap">Price Range</th>
                                                    <th class="text-nowrap">Offer Start Date</th>
                                                    <th class="text-nowrap">Offer End Date</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap" style="width: 15%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($offers as $offer)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $offer->offer_name }}</td>
                                                        <td class="text-nowrap">{{ $offer->offer_percent }} %</td>
                                                        <td class="text-nowrap">Rs.
                                                            {{ $offer->range_min == null ? '-' : $offer->range_min }} to
                                                            Rs. {{ $offer->range_max == null ? '-' : $offer->range_max }}
                                                        </td>
                                                        <td class="text-nowrap">{{ $offer->offer_start_eng_date }} A.D
                                                            <br> {{ $offer->offer_start_nep_date }} B.S
                                                        </td>
                                                        <td class="text-nowrap">{{ $offer->offer_end_eng_date }} A.D
                                                            <br> {{ $offer->offer_end_nep_date }} B.S
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $offer->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                        <td class="text-nowrap">
                                                            <div class="btn-bulk">
                                                                @php
                                                                $editurl = route('offer.edit', $offer->id);
                                                                $deleteurl = route('offer.destroy', $offer->id);
                                                                $csrf_token = csrf_token();
                                                                $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                                                                                                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deleteoffer$offer->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                                                                                                                                                                    <!-- Modal -->
                                                                                                                                                                                                                                                                                                                                                                                                        <div class='modal fade text-left' id='deleteoffer$offer->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                                                                                                                                                                            <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                                                                                                                                                                                <div class='modal-content'>
                                                                                                                                                                                                                                                                                                                                                                                                                    <div class='modal-header'>
                                                                                                                                                                                                                                                                                                                                                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                                                                                                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                    <div class='modal-body text-center'>
                                                                                                                                                                                                                                                                                                                                                                                                                        <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                                                                                                                                                                                                                                                                                                                                                                                        <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                                                                                                                                                                        <label for='reason'>Are you sure you want to delete??</label><br>
                                                                                                                                                                                                                                                                                                                                                                                                                        <input type='hidden' name='_method' value='DELETE' />
                                                                                                                                                                                                                                                                                                                                                                                                                            <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                                                                                                                                                                                                                                                                                                                                                                                        </form>
                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                        ";

                                                                echo $btn;
                                                            @endphp
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="7">No offers yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $offers->firstItem() }}</strong> to
                                                        <strong>{{ $offers->lastItem() }} </strong> of <strong>
                                                            {{ $offers->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-7">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $offers->links() }}</span>
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

@endpush

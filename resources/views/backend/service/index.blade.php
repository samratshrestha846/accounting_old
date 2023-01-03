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
                    <h1>All Services</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service.create') }}" class="global-btn">Create New Item</a>
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

                <div class="card">
                    <div class="card-body">
                        <div class="ibox">
                            <div class="row ibox-body">
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                                <div class="m-0 d-flex justify-content-end">
                                                    <form class="form-inline" action="{{ route('searchservice') }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group mx-sm-3">
                                                            <label for="search" class="sr-only">Search</label>
                                                            <input type="text" class="form-control" id="search" name="search"
                                                                placeholder="Search">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                                class="fa fa-search"></i></button>
                                                    </form>
                                                </div>
                                            <div class="table-responsive mt noscroll">
                                                <table class="table table-bordered data-table text-center global-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-nowrap">Service Code</th>
                                                            <th class="text-nowrap">Service Name</th>
                                                            <th class="text-nowrap">Service Category</th>
                                                            <th class="text-nowrap">Cost Price</th>
                                                            <th class="text-nowrap">Selling Price</th>
                                                            <th class="text-nowrap">Status</th>
                                                            <th class="text-nowrap">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($services as $service)
                                                            <tr>
                                                                <td class="text-nowrap">{{ $service->service_code }}</td>
                                                                <td class="text-nowrap">{{ $service->service_name }}</td>
                                                                <td class="text-nowrap">{{ $service->category->category_name }}
                                                                </td>
                                                                <td class="text-nowrap">Rs. {{ $service->cost_price }}</td>
                                                                <td class="text-nowrap">Rs. {{ $service->sale_price }}</td>
                                                                <td class="text-nowrap">
                                                                    {{ $service->status == 1 ? 'Approved' : 'Unapproved' }}</td>
                                                                <td class="text-nowrap">
                                                                    <div class="btn-bulk">
                                                                        @php
                                                                        $editurl = route('service.edit', $service->id);
                                                                        $showurl = route('service.show', $service->id);
                                                                        $deleteurl = route('service.destroy', $service->id);
                                                                        $csrf_token = csrf_token();
                                                                        $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm'  data-toggle='tooltip' data-placement='top' title='View'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                                                                                                                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                                                            <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteservice$service->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='deleteservice$service->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                            <tr><td colspan="7">No services yet.</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <p class="text-sm">
                                                                Showing <strong>{{ $services->firstItem() }}</strong> to
                                                                <strong>{{ $services->lastItem() }} </strong> of <strong>
                                                                    {{ $services->total() }}</strong>
                                                                entries
                                                                <span> | Takes
                                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                                    seconds to
                                                                    render</span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <span
                                                                class="pagination-sm m-0 float-right">{{ $services->links() }}</span>
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
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

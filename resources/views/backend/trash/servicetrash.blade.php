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
                    <h1>Services (Trashed) </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service.index') }}" class="global-btn">View All Services</a>
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
                                    <div class="table-responsive noscroll">
                                        <table class="table table-bordered data-table-3 text-center" style="width: 100%;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Service Code</th>
                                                    <th class="text-nowrap">Service Name</th>
                                                    <th class="text-nowrap">Service Category</th>
                                                    <th class="text-nowrap">Sale Price</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th width="100px">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($services as $service)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $service->service_code }}</td>
                                                        <td class="text-nowrap">{{ $service->service_name }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $service->category->category_name }}</td>
                                                        <td class="text-nowrap">Rs. {{ $service->sale_price }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $service->status == 1 ? 'Approved' : 'Unapproved' }}</td>
                                                        <td class="text-nowrap">
                                                            @php
                                                                $restoreurl = route('restoreservice', $service->id);
                                                                $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#cancellation$service->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='cancellation$service->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                                                    <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                                                        <div class='modal-content'>
                                                                                                                                                                                                                                                                                            <div class='modal-header'>
                                                                                                                                                                                                                                                                                            <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                                                                                                                                                                                                                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                                                <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                            <div class='modal-body text-center'>
                                                                                                                                                                                                                                                                                                <label for='reason'>Are you sure you want to restore??</label><br>
                                                                                                                                                                                                                                                                                                <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                            ";

                                                                echo $btn;
                                                            @endphp

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="6">No service trashed yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-4">
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
                                                <div class="col-md-8">
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
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

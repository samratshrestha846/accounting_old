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
                    <h1>Suppliers (Trashed)</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.index') }}" class="global-btn">View All Supplier</a>
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
                            <div class="table-responsive noscroll">
                                <table class="table table-bordered data-table-1 text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Company Name</th>
                                            <th class="text-nowrap">Contact Person</th>
                                            <th class="text-nowrap">Company Email</th>
                                            <th class="text-nowrap">Comapny Phone</th>
                                            {{-- <th class="text-nowrap">Company Address</th> --}}
                                            <th class="text-nowrap">Pan No./Vat No.</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($vendors as $vendor)
                                            <tr>
                                                <td>{{ $vendor->company_name }}</td>
                                                <td>{{ $vendor->supplier_code }}</td>
                                                <td>{{ $vendor->concerned_name }}</td>
                                                <td>{{ $vendor->company_email }}</td>
                                                <td>{{ $vendor->company_phone }}</td>
                                                <td>{{ $vendor->pan_vat == null ? 'Not Provided' : $vendor->pan_vat }}
                                                </td>
                                                <td>
                                                    @php
                                                        $restoreurl = route('restorevendor', $vendor->id);
                                                        $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#cancellation$vendor->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                                                                                    <!-- Modal -->
                                                                                                                                                                                                                                                                                        <div class='modal fade text-left' id='cancellation$vendor->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                            <tr><td colspan="6">No suppliers trashed yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $vendors->firstItem() }}</strong> to
                                                <strong>{{ $vendors->lastItem() }} </strong> of <strong>
                                                    {{ $vendors->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $vendors->links() }}</span>
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

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
                    <h1>Our Customers </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.create') }}" class="global-btn">Add New Customer</a>
                        <a href="{{ route('customersLedgers') }}" class="global-btn">View Customer Ledgers</a>
                        <a href="#" data-toggle='modal' data-target='#update_csv_non_importer' data-toggle='tooltip'
                            data-placement='top' class="global-btn" title="Update Product from CSV">Import Customers</a>
                        <a href="{{ route('customersExports') }}" class="global-btn">Export Customer</a>
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
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="" method="POST">
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
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Customer Type</th>
                                            <th class="text-nowrap">Dealer Type</th>
                                            <th class="text-nowrap">Customer Name</th>
                                            <th class="text-nowrap">Customer Code</th>
                                            <th class="text-nowrap">Contact Person</th>
                                            <th class="text-nowrap">Customer Email</th>
                                            <th class="text-nowrap">Customer Phone</th>
                                            <th class="text-nowrap">Pan No./Vat No.</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($clients as $client)
                                            <tr>
                                                <td class="text-nowrap">{{ ucfirst($client->client_type) }}</td>
                                                <td class="text-nowrap">{{ $client->dealertype->title }}</td>
                                                <td class="text-nowrap">{{ $client->name }}</td>
                                                <td class="text-nowrap">
                                                    {{ $client->client_code == null ? 'Not Provided' : $client->client_code }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $client->concerned_name == null ? 'Not Provided' : $client->concerned_name }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $client->email == null ? 'Not Provided' : $client->email }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $client->phone == null ? 'Not Provided' : $client->phone }}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{ $client->pan_vat == null ? 'Not Provided' : $client->pan_vat }}
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="btn-bulk">
                                                        @php
                                                            $showurl = route('client.show', $client->id);
                                                            $editurl = route('client.edit', $client->id);
                                                            $deleteurl = route('client.destroy', $client->id);
                                                            $csrf_token = csrf_token();
                                                            $btn = "<a href='$showurl' class='edit btn btn-primary icon-btn btn-sm' title='View'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href='$editurl' class='edit btn btn-secondary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deleteclient$client->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='deleteclient$client->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                            <tr>
                                                <td colspan="9">No customers yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $clients->firstItem() }}</strong> to
                                                <strong>{{ $clients->lastItem() }} </strong> of <strong>
                                                    {{ $clients->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="pagination-sm m-0 float-right">{{ $clients->links() }}</span>
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
        <div class='modal fade text-left' id='update_csv_non_importer' tabindex='-1' role='dialog'
            aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document' style="max-width: 800px;">
                <div class='modal-content'>
                    <div class='modal-header text-center'>
                        <h2 class='modal-title' id='exampleModalLabel'>Update product from CSV</h2>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <form action="{{ route('customers-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method("POST")
                            <div class="row">

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="update_csv_file">CSV file<i class="text-danger">*</i> </label>
                                        <input type="file" name="excelFile" class="form-control" required>
                                        <p class="text-danger">
                                            {{ $errors->first('excelFile') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-bulk">
                                <button type="submit" class="btn btn-primary btn-sm" name="modal_button">Save</button>
                            </div>
                        </form>

                        <form action="{{ route('customers-import-demo') }}" method="post">
                            @csrf
                            <div class="btn-bulk">
                                <button type="submit" class="btn btn-secondary btn-sm mt-2" name="modal_button"><i class="fas fa-download "> </i> Demo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
@endpush

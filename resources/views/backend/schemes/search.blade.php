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
                    <h1>Searched Schemes</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('scheme.create') }}" class="global-btn">Create New Scheme</a>
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
                                    <div class="m-0 d-flex justify-content-end">
                                        <form class="form-inline" action="{{ route('searchscheme') }}" method="POST">
                                            @csrf
                                            <div class="form-group mx-sm-3">
                                                <label for="search" class="sr-only">Search</label>
                                                <input type="text" class="form-control" id="search" name="search"
                                                    placeholder="Search">
                                            </div>
                                            <button type="submit" class="btn btn-primary icon-btnbtn-sm"><i
                                                    class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                    <div class="table-responsive mt">
                                        <table class="table table-bordered data-table text-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-nowrap">Scheme Code</th>
                                                    <th class="text-nowrap">Scheme Name</th>
                                                    <th class="text-nowrap">Start Date</th>
                                                    <th class="text-nowrap">End Date</th>
                                                    <th class="text-nowrap">Based on</th>
                                                    <th class="text-nowrap">Offer</th>
                                                    <th class="text-nowrap">Status</th>
                                                    <th class="text-nowrap" style="width: 15%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($schemes as $scheme)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $scheme->code }}</td>
                                                        <td class="text-nowrap">{{ $scheme->name }}</td>
                                                        <td class="text-nowrap">{{ $scheme->start_date }}</td>
                                                        <td class="text-nowrap">{{ $scheme->end_date }}</td>
                                                        <td class="text-nowrap">
                                                            {{ $scheme->based_on == 0 ? 'Product Based' : 'Service Based' }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $scheme->percent_fixed == 0 ? $scheme->amount . '%' : 'Rs. ' . $scheme->amount }}
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $scheme->status == 1 ? 'Approved' : 'Unapproved' }}</td>
                                                        <td class="text-nowrap" style="width: 120px;">
                                                            <div class="btn-bulk justify-content-center">
                                                                @php
                                                                $editurl = route('scheme.edit', $scheme->id);
                                                                $deleteurl = route('scheme.destroy', $scheme->id);
                                                                $csrf_token = csrf_token();
                                                                $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                                                                                                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletescheme$scheme->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                                                                                                                                                                    <!-- Modal -->
                                                                                                                                                                                                                                                                                                                                                                                                        <div class='modal fade text-left' id='deletescheme$scheme->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                    <tr><td colspan="8">No search results.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <p class="text-sm">
                                                        Showing <strong>{{ $schemes->firstItem() }}</strong> to
                                                        <strong>{{ $schemes->lastItem() }} </strong> of <strong>
                                                            {{ $schemes->total() }}</strong>
                                                        entries
                                                        <span> | Takes
                                                            <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                            seconds to
                                                            render</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-7">
                                                    <span
                                                        class="pagination-sm m-0 float-right">{{ $schemes->links() }}</span>
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

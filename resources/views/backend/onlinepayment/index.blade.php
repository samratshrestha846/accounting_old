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
                    <h1>Online payment portal </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('onlinepayment.create') }}"
                            class="global-btn">Add New Payment</a>
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
                                    <form class="form-inline" action="#" method="POST">
                                        @csrf
                                        @method("POST")
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn btn-sm mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Portal Name</th>
                                            <th class="text-nowrap">Portal ID</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($portals as $portal)
                                            <tr>
                                                <td>{{ $portal->name }}</td>
                                                <td>{{ $portal->payment_id }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        @php
                                                            $editurl = route('onlinepayment.edit', $portal->id);
                                                            $deleteurl = route('onlinepayment.destroy', $portal->id);
                                                            $csrf_token = csrf_token();
                                                            $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletionservice$portal->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                        <!-- Modal -->
                                                                            <div class='modal fade text-left' id='deletionservice$portal->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                <td colspan="5">No portals yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $portals->firstItem() }}</strong> to
                                                <strong>{{ $portals->lastItem() }} </strong> of <strong>
                                                    {{ $portals->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $portals->links() }}</span>
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

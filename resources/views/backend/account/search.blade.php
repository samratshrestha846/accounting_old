@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Search Accounts </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('account.create') }}" class="global-btn">Add Account Types</a>
                        <a href="{{ route('sub_account.index') }}" class="global-btn">View Sub-Account
                            Types</a> <a href="{{ route('child_account.index') }}" class="global-btn">View
                            Child Account Types</a> <a href="{{ route('account.index') }}" class="global-btn">Go
                            Back</a>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('accounts.search') }}" method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>

                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Title</th>
                                            <th class="text-nowrap">Slug</th>
                                            <th class="text-nowrap" width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accounts as $account)
                                            <tr>
                                                <td>{{ $account->title }}</td>
                                                <td>{{ $account->slug }}</td>
                                                <td style="width: 120px;">
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href='{{ route('account.edit', $account->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal'
                                                            data-target='#deleteaccount{{ $account->id }}'
                                                            data-toggle='tooltip' data-placement='top' title='Delete'><i
                                                                class='fa fa-trash'></i></button>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class='modal fade text-left'
                                                        id='deleteaccount{{ $account->id }}' tabindex='-1' role='dialog'
                                                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                        <div class='modal-dialog' role='document'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='exampleModalLabel'>Delete
                                                                        Confirmation</h5>
                                                                    <button type='button' class='close' data-dismiss='modal'
                                                                        aria-label='Close'>
                                                                        <span aria-hidden='true'>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class='modal-body text-center'>
                                                                    <form action='$deleteurl' method='POST'
                                                                        style='display:inline-block;'>
                                                                        <input type='hidden' name='_token'
                                                                            value='$csrf_token'>
                                                                        <label for='reason'>Are you sure you want to
                                                                            delete??</label><br>
                                                                        <input type='hidden' name='_method'
                                                                            value='DELETE' />
                                                                        <button type='submit' class='btn btn-danger'
                                                                            title='Delete'>Confirm Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3">No search results.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $accounts->firstItem() }}</strong> to
                                                <strong>{{ $accounts->lastItem() }} </strong> of <strong>
                                                    {{ $accounts->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $accounts->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')


@endpush

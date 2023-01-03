@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Account Types (Trashed) </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('account.index') }}" class="global-btn">View Main Account
                            Types</a> <a href="{{ route('sub_account.index') }}" class="global-btn">View
                            Sub-Account
                            Types</a> <a href="{{ route('child_account.index') }}" class="global-btn">View
                            Child Account Types</a>
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
                    <div class="card-header">
                        <h2>Main Account Types</h2>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered data-table text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Title</th>
                                        <th class="text-nowrap">Slug</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($accounts as $account)
                                        <tr>
                                            <td>{{ $account->title }}</td>
                                            <td>{{ $account->slug }}</td>
                                            <td>
                                                <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal'
                                                    data-target='#restoreaccount{{ $account->id }}' data-toggle='tooltip'
                                                    data-placement='top' title='Restore'><i
                                                        class='fa fa-trash-restore'></i></button>
                                                <!-- Modal -->
                                                <div class='modal fade text-left' id='restoreaccount{{ $account->id }}'
                                                    tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
                                                    aria-hidden='true'>
                                                    <div class='modal-dialog' role='document'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h5 class='modal-title' id='exampleModalLabel'>Restore
                                                                    Confirmation</h5>
                                                                <button type='button' class='close' data-dismiss='modal'
                                                                    aria-label='Close'>
                                                                    <span aria-hidden='true'>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class='modal-body text-center'>
                                                                <label for='reason'>Are you sure you want to
                                                                    restore??</label><br>
                                                                <a href='{{ route('restore', $account->id) }}'
                                                                    class='edit btn btn-primary btn-sm'
                                                                    title='Restore'>Confirm Restore</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No main accounts trahsed yet.</td></tr>
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
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
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

                <div class="card mt-4">
                    <div class="card-header">
                        <h2>Sub Account Types</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table-2 text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Title</th>
                                            <th class="text-nowrap">Embedded Account</th>
                                            <th class="text-nowrap">Slug</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subaccounts as $subaccount)
                                            <tr>
                                                <td>{{ $subaccount->title }}</td>
                                                <td>{{ $subaccount->account->title }}</td>
                                                <td>{{ $subaccount->slug }}</td>
                                                <td>
                                                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal'
                                                        data-target='#restoresubaccount{{ $subaccount->id }}'
                                                        data-toggle='tooltip' data-placement='top' title='Restore'><i
                                                            class='fa fa-trash-restore'></i></button>
                                                    <!-- Modal -->
                                                    <div class='modal fade text-left'
                                                        id='restoresubaccount{{ $subaccount->id }}' tabindex='-1'
                                                        role='dialog' aria-labelledby='exampleModalLabel'
                                                        aria-hidden='true'>
                                                        <div class='modal-dialog' role='document'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='exampleModalLabel'>Restore
                                                                        Confirmation</h5>
                                                                    <button type='button' class='close' data-dismiss='modal'
                                                                        aria-label='Close'>
                                                                        <span aria-hidden='true'>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class='modal-body text-center'>
                                                                    <label for='reason'>Are you sure you want to
                                                                        restore??</label><br>
                                                                    <a href='{{ route('restoresubaccount', $subaccount->id) }}'
                                                                        class='edit btn btn-primary btn-sm'
                                                                        title='Restore'>Confirm Restore</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No sub accounts trashed yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $subaccounts->firstItem() }}</strong> to
                                                <strong>{{ $subaccounts->lastItem() }} </strong> of <strong>
                                                    {{ $subaccounts->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $subaccounts->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h2>Child Account Types</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table-3 text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Title</th>
                                            <th class="text-nowrap">Embedded Sub Account</th>
                                            <th class="text-nowrap">Slug</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($childaccounts as $childaccount)
                                            <tr>
                                                <td>{{ $childaccount->title }}</td>
                                                <td>{{ $childaccount->subaccount->title }}</td>
                                                <td>{{ $childaccount->slug }}</td>
                                                <td>{{ $childaccount->opening_balance == null ? '-' : $childaccount->opening_balance }}
                                                </td>
                                                <td>
                                                    <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal'
                                                        data-target='#restorechildaccount{{ $childaccount->id }}'
                                                        data-toggle='tooltip' data-placement='top' title='Restore'><i
                                                            class='fa fa-trash-restore'></i></button>
                                                    <!-- Modal -->
                                                    <div class='modal fade text-left'
                                                        id='restorechildaccount{{ $childaccount->id }}' tabindex='-1'
                                                        role='dialog' aria-labelledby='exampleModalLabel'
                                                        aria-hidden='true'>
                                                        <div class='modal-dialog' role='document'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='exampleModalLabel'>Restore
                                                                        Confirmation</h5>
                                                                    <button type='button' class='close' data-dismiss='modal'
                                                                        aria-label='Close'>
                                                                        <span aria-hidden='true'>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class='modal-body text-center'>
                                                                    <label for='reason'>Are you sure you want to
                                                                        restore??</label><br>
                                                                    <a href='{{ route('restorechildaccount', $childaccount->id) }}'
                                                                        class='edit btn btn-primary btn-sm'
                                                                        title='Restore'>Confirm Restore</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5">No child accounts trashed yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $childaccounts->firstItem() }}</strong> to
                                                <strong>{{ $childaccounts->lastItem() }} </strong> of <strong>
                                                    {{ $childaccounts->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $childaccounts->links() }}</span>
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

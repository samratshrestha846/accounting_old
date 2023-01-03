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
                    <h1>Bank Account Types </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('bankAccountType.create') }}" class="global-btn">Create Bank
                            Account Type</a>
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
                                    <form class="form-inline" action="{{ route('bank.search') }}" method="POST">
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
                                            <th class="text-nowrap">Account Type</th>
                                            <th class="text-nowrap">Status</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bankAccountTypes as $bankAccountType)
                                            <tr>
                                                <td>{{ $bankAccountType->account_type_name }}</td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" value="{{ $bankAccountType->id }}"
                                                            class="custom-control-input"
                                                            id="customSwitch{{ $bankAccountType->id }}"
                                                            onclick="changeStatus({{ $bankAccountType->id }})"
                                                            {{ $bankAccountType->status == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="customSwitch{{ $bankAccountType->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-bulk">
                                                        <a href='{{ route('bankAccountType.edit', $bankAccountType->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                        <button type='button' class='btn btn-secondary icon-btn btn-sm'
                                                            data-toggle='modal'
                                                            data-target='#deletechild{{ $bankAccountType->id }}'
                                                            data-toggle='tooltip' data-placement='top' title='Delete'><i
                                                                class='fa fa-trash'></i></button>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class='modal fade text-left'
                                                        id='deletechild{{ $bankAccountType->id }}' tabindex='-1'
                                                        role='dialog' aria-labelledby='exampleModalLabel'
                                                        aria-hidden='true'>
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
                                                                    <form
                                                                        action='{{ route('bankAccountType.destroy', $bankAccountType->id) }}'
                                                                        method='POST' style='display:inline-block;'>
                                                                        @csrf
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
                                            <tr>
                                                <td colspan="3">No Account Type information yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $bankAccountTypes->firstItem() }}</strong> to
                                                <strong>{{ $bankAccountTypes->lastItem() }} </strong> of <strong>
                                                    {{ $bankAccountTypes->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $bankAccountTypes->links() }}</span>
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
    <script>
        function changeStatus(bankAccountTypeId) {
            var bankAccountType = bankAccountTypeId;

            uri = "{{ route('changeStatus', ':id') }}";
            uri = uri.replace(':id', bankAccountType);
            $.ajax({
                url: uri,
                type: 'PUT',
                dataType: 'json',
                data:{
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    location.reload();
                }
            });
        }
    </script>
@endpush

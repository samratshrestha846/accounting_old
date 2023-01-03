
@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View {{$client->name}}'s Users</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.create') }}" class="global-btn">Add New Customers</a>
                        <a href="{{ route('client.index') }}" class="global-btn">View All Customers</a>
                        <a href="{{ route('client.products', $client->id) }}" class="global-btn">Customers Product</a>
                        <a href="{{ route('clientuser.create', $client->id) }}" class="global-btn">Create {{$client->name}}'s User</a>
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
                        <div class="table-responsive mt" id="refresh">
                            <table class="table table-bordered data-table text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->dealer_users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td style="width:120px;">
                                                <div class="btn-bulk justify-content-center">
                                                    @php
                                                    $editurl = route('clientuser.edit', $user->id);
                                                    $deleteurl = route('clientuser.destroy', $user->id);
                                                    $csrf_token = csrf_token();
                                                    $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                            <form action='$deleteurl' method='POST' style='display:inline-block;padding:0; ' class='btn'>
                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                            <input type='hidden' name='_method' value='DELETE' />
                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm' title='Delete'><i class='fa fa-trash'></i></button>
                                                            </form>
                                                        ";
                                                    echo $btn;
                                                @endphp
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center">No Dealer User yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')

@endpush

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
                    <h1>Search result => Staffs </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('staff.create') }}" class="global-btn">Add
                            New Staff</a>
                    </div>
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
                            <div class="col-md-12">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('staff.search') }}" method="POST">
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
                            <div class="col-md-12">
                                <div class="table-responsive mt">
                                    <table class="table table-bordered yajra-datatable text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center">Image</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Position</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($staffs as $staff)
                                                <tr>
                                                    <td style="width: 100px;"><img src="{{ Storage::disk('uploads')->url($staff->image) }}"
                                                            style="max-height: 50px;"></td>
                                                    <td>{{ $staff->name }}</td>
                                                    <td>{{ $staff->position->name }}</td>
                                                    <td>
                                                        @if ($staff->status == 0)
                                                            Inactive
                                                        @else
                                                            Active
                                                        @endif
                                                    </td>
                                                    <td style="width: 120px;">
                                                        <div class="btn-bulk justify-content-center">
                                                            @php
                                                            $editurl = route('staff.edit', $staff->id);
                                                            $showurl = route('staff.show', $staff->id);
                                                            $disableurl = route('staff.disable', $staff->id);
                                                            $enableurl = route('staff.enable', $staff->id);
                                                            $csrf_token = csrf_token();
                                                            if ($staff->status == 0) {
                                                                $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                            <a href='$showurl' class='show btn btn-secondary icon-btn btn-sm' title='Show'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                                                                                                <form action='$enableurl' method='POST' style='display:inline;padding:0;' class='btn'>
                                                                                                                                                                                                                                                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                                                                <input type='hidden' name='_method' value='PUT' />
                                                                                                                                                                                                                                                                                                                    <button type='submit' class='btn btn-primary icon-btn btn-sm' title='Enable'><i class='fa fa-thumbs-up'></i></button>
                                                                                                                                                                                                                                                                                                                </form>
                                                                                                                                                                                                                                                                                                                ";
                                                            } else {
                                                                $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                                                                            <a href='$showurl' class='show btn btn-secondary icon-btn btn-sm' title='Show'><i class='fa fa-eye'></i></a>
                                                                                                                                                                                                                                                                                                                <form action='$disableurl' method='POST' style='display:inline;padding:0;' class='btn'>
                                                                                                                                                                                                                                                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                                                                <input type='hidden' name='_method' value='PUT' />
                                                                                                                                                                                                                                                                                                                    <button type='submit' class='btn btn-primary icon-btn btn-sm' title='Disable'><i class='fa fa-thumbs-down'></i></button>
                                                                                                                                                                                                                                                                                                                </form>
                                                                                                                                                                                                                                                                                                                ";
                                                            }

                                                            echo $btn;
                                                        @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5">No search results.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="mt-3">
                                        <div class="position">
                                            <div class="col-md-8">
                                                <p class="text-sm">
                                                    Showing <strong>{{ $staffs->firstItem() }}</strong> to
                                                    <strong>{{ $staffs->lastItem() }} </strong> of <strong>
                                                        {{ $staffs->total() }}</strong>
                                                    entries
                                                    <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                        seconds to
                                                        render</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <span class="pagination-sm m-0 float-right">{{ $staffs->links() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@push('scripts')

@endpush

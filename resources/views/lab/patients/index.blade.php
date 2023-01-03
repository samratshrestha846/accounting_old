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
                    <h1>Patients </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('patients.create') }}" class="global-btn">Add New patients</a>
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
                                    <form class="form-inline" action="" method="">
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Name or patient code or phone" value="{{ request('search') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.N</th>
                                            <th class="text-nowrap">Code</th>
                                            <th class="text-nowrap">Fullname</th>
                                            <th class="text-nowrap">Address</th>
                                            <th class="text-nowrap">Email</th>
                                            <th class="text-nowrap">Phone</th>
                                            <th class="text-nowrap">Action</th>
                                            <th class="text-nowrap">Reason For Visit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($patients as $key=>$patient)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $patient->patientCode }}</td>
                                                <td><a
                                                        href="{{ route('patients.show', $patient->id) }}">{{ $patient->name }}</a>
                                                </td>
                                                <td>{{ $patient->address }}</td>
                                                <td>{{ $patient->email }}</td>
                                                <td>{{ $patient->number }}</td>
                                                <td>{{ $patient->description }}</td>
                                                <td style="width: 120px;">
                                                    <div class="btn-bulk justify-content-center">
                                                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-primary icon-btn btn-sm" title='View'><i class='fa fa-eye'></i></a>
                                                        <a href='{{ route('patients.edit', $patient->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                        @include('lab.includes._modal',['id'=>$patient->id,'route'=>route('patients.destroy',$patient->id)])
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10"> No Patients Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $patients->firstItem() }}</strong> to
                                                <strong>{{ $patients->lastItem() }} </strong> of <strong>
                                                    {{ $patients->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $patients->links() }}</span>
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

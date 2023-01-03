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
                    <h1>Appointments </h1>

                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="" method="">
                                        <div class="form-group mx-sm-3">
                                            <input type="date" class="form-control" id="date" name="date"
                                                placeholder="Date" value="{{ request('date') }}">
                                        </div>
                                        {{-- <div class="form-group mx-sm-3">
                                            <select name="status" class="form-control" id="" placeholder="Status">
                                                <option value="0">Pending</option>
                                                <option value="1">Completed</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-group mx-sm-3">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search" value="{{ request('search') }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                                class="fa fa-search"></i></button>
                                        {{-- <a class="btn btn-primary icon-btn btn-sm" href="{{route('allAppointments')}}"><i
                                                    class="fas fa-sync"></i></a> --}}
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive mt">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">S.N</th>
                                            <th class="text-nowrap">Patient Name</th>
                                            <th class="text-nowrap">Test Type</th>
                                            <th class="text-nowrap">Assigned To</th>
                                            <th class="text-nowrap">Date Time</th>
                                            <th class="text-nowrap">Status</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($appointments as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><a
                                                href="{{ route('patients.show', $item->patient->id) }}" title="click to see the details">{{$item->patient->name }}</a></td>
                                            <td>@foreach ($item->testTypes as $test)
                                                <span class="badge badge-primary">{{ $test->title }}</span>
                                            @endforeach</td>
                                            <td>@foreach ($item->staffs as $staff)
                                                <span class="badge badge-primary">{{ $staff->name }}</span>
                                            @endforeach</td>
                                            <td>{{ Carbon\Carbon::parse($item->startTime)->format('F d, Y h:i a')}} - {{ Carbon\Carbon::parse($item->endTime)->format('F d, Y h:i a')}}</td>
                                            <td>{{ $item->status == 0 ? 'pending' : 'completed' }}</td>
                                            <td>
                                                <div class="btn-bulk">
                                                    <a href='{{ route('appointment.show', $item->id) }}'
                                                        class='edit btn btn-primary icon-btn btn-sm' title='View Details'><i
                                                            class='fa fa-eye'></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No Appointments Yet</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $appointments->firstItem() }}</strong> to
                                                <strong>{{ $appointments->lastItem() }} </strong> of <strong>
                                                    {{ $appointments->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $appointments->links() }}</span>
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
            $("select.form-control").select2();
    </script>
@endpush

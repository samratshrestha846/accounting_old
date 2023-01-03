@extends('backend.layouts.app')
@push('styles')
    <style>
        form .cabin-data {
            display: none;
        }

        .lh-0-6 {
            line-height: 0.6 !important;
        }

    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Hotel Table Reservation </h1>
                    <div class="btn-bulk">
                        @can('hotel-reservation-create')
                            <a href="{{ route('hotel-reservation.create') }}" class="global-btn">New Hotel Table
                                Reservation</a>
                        @endcan
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
                <div class="row">
                    <div class="col-md-12">
                        <form method="get" name="{{ route('hotel-reservation.index') }}">
                            <div class="row card-header">
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text lh-0-6" id="basic-addon1">Start Date Time</span>
                                        </div>
                                        <input autocomplete="off" type="text" name="date_time_start" id="datetimepicker_start"
                                            value="{{ isset($filterArray) ? $filterArray['date_time_start'] : '' }}"
                                            class="form-control">
                                        @if ($errors->first('date_time_start'))
                                            <p class="text-danger">
                                                {{ $errors->first('date_time_start') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text lh-0-6" id="basic-addon1">End Date Time</span>
                                        </div>
                                        <input autocomplete="off" type="text" name="date_time_end" id="datetimepicker_end"
                                            value="{{ isset($filterArray) ? $filterArray['date_time_end'] : '' }}"
                                            class="form-control">
                                        @if ($errors->first('date_time_end'))
                                            <p class="text-danger">
                                                {{ $errors->first('date_time_end') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="m-0 form-inline">
                                        <button type="submit" class="btn btn-primary icon-btn"><i
                                                class="fa fa-search"></i> </button>
                                                &nbsp;
                                                @if ($filterArray)
                                                <a href="{{ route('hotel-reservation.index') }}" class="btn btn-secondary icon-btn" title="Cancel Search"><i
                                                    class="fa fa-times"></i> </a>
                                                @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 table-responsive mt">
                        <table class="table table-bordered yajra-datatable text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Floor</th>
                                    <th class="text-center">Room</th>
                                    <th class="text-center">Table Code</th>
                                    <th class="text-center">No. of Person</th>
                                    <th class="text-center">From</th>
                                    <th class="text-center">To</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->client->name }}</td>
                                        <td>{{ $reservation->table->floor ? $reservation->table->floor->name : '-' }}</td>
                                        <td>{{ $reservation->table->room ? $reservation->table->room->name : '-' }}</td>
                                        <td><b>#{{ $reservation->table->code }}</b></td>
                                        <td>{{ $reservation->number_of_people }}</td>
                                        <td>
                                            {{ date('d M Y H:i A', strtotime($reservation->date_time_start)) }}
                                        </td>
                                        <td>
                                            {{ date('d M Y H:i A', strtotime($reservation->date_time_end)) }}
                                        </td>
                                        <td>
                                            @switch($reservation->status)
                                                @case(1)
                                                    <badge class="text-white badge badge-success">Booked</badge>
                                                @break
                                                @case(2)
                                                    <badge class="text-white badge badge-danger">Cancelled</badge>
                                                @break
                                                @default
                                                    <badge class="text-white badge badge-info">Free</badge>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-bulk justify-content-start">
                                                @can('hotel-reservation-edit')
                                                    @if ($reservation->status != 2)
                                                        <a href="{{ route('hotel-reservation.cancel', $reservation) }}"
                                                            onclick="return confirm('Are you Sure you want to cancelled Reservation?')"
                                                            class="btn btn-primary icon-btn btn-sm"
                                                            title="Cancell Reservation"><i class='fa fa-ban'></i></a>
                                                    @endif
                                                    <a href='{{ route('hotel-reservation.edit', $reservation->id) }}' class='edit btn btn-primary
                                                                                    icon-btn btn-sm' title='Edit'><i
                                                            class='fa fa-edit'></i></a>
                                                @endcan
                                                @can('hotel-reservation-delete')
                                                    <form action='{{ route('hotel-reservation.destroy', $reservation->id) }}'
                                                        method='POST' style='display:inline;padding:0;' class='btn'>
                                                        @csrf
                                                        <input type='hidden' name='_method' value='DELETE' />
                                                        <button type='submit' class='btn btn-secondary icon-btn btn-sm'
                                                            title='Delete'
                                                            onclick="return confirm('Are you sure you want to delete Reservation?')"><i
                                                                class='fa fa-trash'></i></button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">No Reservation yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="position row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $reservations->firstItem() }}</strong> to
                                            <strong>{{ $reservations->lastItem() }} </strong> of <strong>
                                                {{ $reservations->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $reservations->links() }}</span>
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
        <script src="{{ asset('backend/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script>
            $(function() {
                $('#datetimepicker_start').datetimepicker({
                    icons: {
                        time: 'fas fa-clock',
                        date: 'fas fa-calendar',
                        up: 'fas fa-arrow-up',
                        down: 'fas fa-arrow-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'fas fa-calendar-check-o',
                        clear: 'fas fa-trash',
                        close: 'fas fa-times'
                    }
                });
                $('#datetimepicker_end').datetimepicker({
                    icons: {
                        time: 'fas fa-clock',
                        date: 'fas fa-calendar',
                        up: 'fas fa-arrow-up',
                        down: 'fas fa-arrow-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'fas fa-calendar-check-o',
                        clear: 'fas fa-trash',
                        close: 'fas fa-times'
                    },
                    useCurrent: false, //Important! See issue #1075

                });
                @if ($filterArray)
                    $('#datetimepicker_end').data("DateTimePicker").minDate("{{ $filterArray['date_time_start'] }}");
                    $('#datetimepicker_start').data("DateTimePicker").maxDate("{{ $filterArray['date_time_end'] }}");
                @endif
                $("#datetimepicker_start").on("dp.change", function(e) {
                    $('#datetimepicker_end').data("DateTimePicker").minDate(e.date);
                });
                $("#datetimepicker_end").on("dp.change", function(e) {
                    $('#datetimepicker_start').data("DateTimePicker").maxDate(e.date);
                });
            });
        </script>
    @endpush

@extends('backend.layouts.app')
@push('styles')
    <style>
        form .cabin-data {
            display: none;
        }

        div.card-header {
            background-color: #ebf3fb !important;
        }

        .float-right {
            float: right !important;
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
                    <h1>Edit Table Reservation </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-reservation.index') }}" class="global-btn">View Hotel Table
                            Reservation</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form method="post"
                    action="{{ route('hotel-reservation.update', ['hotel_reservation' => $hotelReservation]) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12 card pt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="list-group">
                                    <div class="list-group-item card-header text-bold">Table Information</div>
                                    <li class="list-group-item d-flex justify-content-between">
                                        Floor <span id="Floor"> {{ $hotelReservation->table->floor->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        Room <span id="Room"> {{ $hotelReservation->table->room->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        Table Name <span id="TableName"> {{ $hotelReservation->table->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        Table Code <span id="TableName"> {{ $hotelReservation->table->code }}</span>
                                    </li>
                                    <li id="CabinInfo" class="list-group-item d-flex justify-content-between">
                                        Cabin <span id="Cabin">
                                            {{ $hotelReservation->table->iscabin ? $hotelReservation->table->cabin->name : 'No' }}</span>
                                    </li>
                                    @if ($hotelReservation->table->iscabin)
                                        <li id="CabinInfo" class="list-group-item d-flex justify-content-between">
                                            Cabin Charge <span id="Cabin">
                                                {{ $hotelReservation->table->cabin_charge }}</span>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between">
                                        Capacity <span
                                            id="TableCapacity">{{ $hotelReservation->table->max_capacity }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-8">
                                <input type="hidden" name="id" id="Id">
                                <div class="form-group row">
                                    <label for="client" class="col-sm-3 col-form-label">Select Client</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="client_id" disabled required>
                                            <option value=""> -- Select Client -- </option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" @if ($hotelReservation->client_id == $client->id) selected @endif>
                                                    {{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Number of People</label>
                                    <div class="col-sm-9">
                                        <input type="number" min="1" class="form-control" name="number_of_people"
                                            id="inputEmail3" value="{{ $hotelReservation->number_of_people }}"
                                            placeholder="Number Of People">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="inputEmail3"
                                        class="col-sm-3 col-form-label">From</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="date_time_start" class="form-control"
                                            id="datetimepicker_start" placeholder="from time"
                                            value="{{ date('m/d/Y H:i A', strtotime($hotelReservation->date_time_start)) }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">To</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="date_time_end" class="form-control"
                                            id="datetimepicker_end" placeholder="To time"
                                            value="{{ date('m/d/Y H:i A', strtotime($hotelReservation->date_time_end)) }}">
                                    </div>
                                </div>
                                
                                <fieldset class="form-group">
                                    <div class="row">
                                        <label class="col-form-label col-sm-3 pt-0">Payment Method</label>
                                        <div class="col-sm-9 form-inline">

                                            @foreach ($paymentMode as $payment)
                                                <div class="form-check pr-2">
                                                    <input class="form-check-input" type="radio" name="payment_by"
                                                        id="gridRadios1" value="{{ $payment->id }}"
                                                        @if ($hotelReservation->payment_method == $payment->id) checked @endif required>
                                                    <label class="form-check-label" for="gridRadios1">
                                                        {{ $payment->payment_mode }}
                                                    </label>
                                                </div>
                                            @endforeach



                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <div class="row">
                                        <label class="col-form-label col-sm-3 pt-0">Is Paid</label>
                                        <div class="col-sm-9 form-inline">
                                            <div class="form-check pr-2">
                                                <input class="form-check-input" type="radio" name="is_paid" id="gridRadios1"
                                                    value="0" @if ($hotelReservation->is_paid == 0) checked @endif required>
                                                <label class="form-check-label" for="gridRadios1">
                                                    Not Paid
                                                </label>
                                            </div>
                                            <div class="form-check disabled">
                                                <input class="form-check-input" type="radio" name="is_paid" id="gridRadios3"
                                                    value="1" @if ($hotelReservation->is_paid == 1) checked @endif required>
                                                <label class="form-check-label" for="gridRadios3">
                                                    Paid
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group row" id="DateToPaid">
                                    <label class="col-sm-3 col-form-label">Date To Paid</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="date_to_paid"
                                            value="{{ $hotelReservation->date_to_paid }}" placeholder="Date To Paid">
                                    </div>
                                </div>
                                <div class="form-group row" id="Amount">
                                    <label class="col-sm-3 col-form-label">Amount</label>
                                    <div class="col-sm-9">
                                        <input type="number" min="1" dec="2" class="form-control" name="amount"
                                            value="{{ $hotelReservation->amount }}" placeholder="Amount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Status</label>
                                    <div class="col-sm-9">
                                        <select name="status" class="form-control" required>
                                            <option value=""> -- Select Status -- </option>
                                            <option value="0" @if ($hotelReservation->status == 0) selected @endif>Free</option>
                                            <option value="1" @if ($hotelReservation->status == 1) selected @endif>Booked</option>
                                            <option value="1" @if ($hotelReservation->status == 2) selected @endif>Cancelled</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-2">
                            <button type="button" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary float-right">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            changePaid();

            $("input[name='is_paid']").change(function() {
                changePaid();
            });

            function changePaid() {
                var radioValue = $("input[name='is_paid']:checked").val();
                if (radioValue == 0) {
                    $('#DateToPaid').show();
                } else {
                    $('#DateToPaid').hide();
                }
            }
        });
    </script>
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
        $("#datetimepicker_start").on("dp.change", function(e) {
            $('#datetimepicker_end').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker_end").on("dp.change", function(e) {
            
            $('#datetimepicker_start').data("DateTimePicker").maxDate(e.date);
        });
        $('#datetimepicker_end').data("DateTimePicker").minDate("{{ date('m/d/Y H:i A', strtotime($hotelReservation->date_time_start)) }}");
        
    });
</script>
@endpush

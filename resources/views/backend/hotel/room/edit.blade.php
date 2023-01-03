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
                    <h1>Edit Hotel Room </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-room.index') }}" class="global-btn">View Hotel Rooms</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
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
                                <form action="{{ route('hotel-room.update', $room->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room_code">Floor<i class="text-danger">*</i></label>
                                                <select name="floor" class="form-control select2">
                                                    <option value="">Select Floor</option>
                                                    @foreach ($floors as $floor)
                                                        <option value="{{ $floor->id }}"
                                                            {{ old('floor', $room->floor_id) == $floor->id ? 'selected' : '' }}>
                                                            {{ $floor->name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('floor') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room_name">Room Name<i class="text-danger">*</i></label>
                                                <input type="text" name="room_name" class="form-control"
                                                    placeholder="Enter Room Name"
                                                    value="{{ old('room_name', $room->name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('room_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room_code">Room Code (Code must be unique)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="room_code" class="form-control"
                                                    placeholder="Enter Room Code"
                                                    value="{{ old('room_code', $room->code) }}" id="room_code">

                                                <p class="text-danger categorycode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('room_code') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="table_capacity">Table Capacity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" name="table_capacity" class="form-control"
                                                    placeholder="Enter Table Capacity"
                                                    value="{{ old('table_capacity', $room->table_capacity) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('table_capacity') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-sm ml-auto">Save</button>
                                </form>
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
        $(document).ready(function() {
            $(".select2").select2();
        });
    </script>
@endpush

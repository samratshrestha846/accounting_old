@extends('backend.layouts.app')
@push('styles')
<style>
    form .cabin-data{
        display: none;
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
                    <h1>New Hotel Table </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-room.index') }}" class="global-btn">View Hotel Tables</a>
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
                                <form action="{{ route('hotel-table.update', $table->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="room_code">Room<i
                                                        class="text-danger">*</i></label>
                                                <select name="room" class="form-control select2">
                                                    <option value="">Select Room</option>
                                                    @foreach ($rooms as $room)
                                                    <option value="{{$room->id}}" {{old('room', $table->room_id) == $room->id ? 'selected' : ''}}>{{$room->name.'('.$room->floor->name.')'}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('room') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="table_name">Table Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="table_name" class="form-control"
                                                    placeholder="Enter Table Name" value="{{ old('table_name', $table->name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('table_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="table_code">Table Code (Code must be unique)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="table_code" class="form-control"
                                                    placeholder="Enter Table Code" value="{{ old('table_code', $table->code) }}"
                                                    id="table_code">

                                                <p class="text-danger categorycode_error hide">Code is already used. Use
                                                    Different code.</p>
                                                <p class="text-danger">
                                                    {{ $errors->first('table_code') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="max_capacity">Max Capacity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" min="1" name="max_capacity" class="form-control"
                                                    placeholder="Enter Table Capacity" value="{{ old('max_capacity', $table->max_capacity) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('max_capacity') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="Status" style="display: block;">Is Cabin: </label>
                                                <span style="margin-right: 5px; font-size: 13px;"> No </span>
                                                <label class="switch pt-0">
                                                    <input type="checkbox" name="is_cabin" id="is_cabin" value="1" {{ old('is_cabin', $table->is_cabin) == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                                <span style="margin-left: 5px; font-size: 12px;">Yes</span>
                                            </div>
                                            <p class="text-danger">
                                                {{ $errors->first('is_cabin') }}
                                            </p>
                                        </div>
                                        <div class="cabin-data col-md-3">
                                            <div class="row">
                                            <div class="form-group col-md-10">
                                                <label for="room_name">Cabin Type<i class="text-danger">*</i></label>
                                                <select type="text" name="cabin_type" class="form-control">
                                                    <option value="">Select Cabin Type</option>
                                                    @foreach($cabin_types as $cabinType)
                                                        <option value="{{$cabinType->id}}" {{$cabinType->id == old('cabin_type', $table->cabin_type_id) ? 'selected' : ''}}>{{$cabinType->name}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('cabin_type') }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 pl-0" style="padding-left:7px;">
                                                <div class="form-group">
                                                    <label for="cabin_add"><span style="visibility: hidden;">add</span></label>
                                                <button type="button" data-toggle='modal'
                                                    data-target='#cabin_add' data-toggle='tooltip'
                                                    data-placement='top' class="btn btn-primary btn-sm icon-btn"
                                                    title="Add New Category"><i
                                                        class="fas fa-plus"></i></button>
                                                </div>
                                          </div>
                                        </div>
                                        </div>
                                        <div class="cabin-data col-md-3">
                                            <div class="form-group">
                                                <label for="cabin_charge">Cabin Charge<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="cabin_charge" class="form-control"
                                                    placeholder="Enter Cabion Charge" value="{{ old('cabin_charge', $table->cabin_charge) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('cabin_charge') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm ml-auto">Save</button>
                                </form>
                                @include('backend.hotel.table.createcabinmodel')
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

    (function ($) {

        let isCabin = "{{old('is_cabin', $table->is_cabin)}}";

        if(parseInt(isCabin) == 1){
            $('.cabin-data').show();
        }

        $("input[name='is_cabin']").on('change', function(event){
            let checked = $(this).is(":checked");

            if(checked) {
                $('.cabin-data').show();
            }else {
                $('.cabin-data').hide();
            }
        })

    }(jQuery));

</script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>
@endpush

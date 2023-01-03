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
                    <h1>Edit Hotel Kitchen </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hotel-floor.index') }}" class="global-btn">View Hotel Kitchen</a>
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
                                <form action="{{ route('hotel-kitchen.update', $kitchen->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="room">Room<i
                                                        class="text-danger">*</i></label>
                                                <select name="room" class="form-control select2">
                                                    <option value="">Select Room</option>
                                                    @foreach ($rooms as $room)
                                                    <option value="{{$room->id}}" {{old('room', $kitchen->room_id) == $room->id ? 'selected' : ''}}>{{$room->name.'('.$room->floor->name.')'}}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('room') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kitchen_name">Kitchen Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="kitchen_name" class="form-control"
                                                    placeholder="Enter Kitchen Name" value="{{ old('kitchen_name', $kitchen->kitchen_name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('kitchen_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea type="text" name="remarks" class="form-control"
                                                    id="remarks">{{ old('remarks', $kitchen->remarks) }}
                                                </textarea>
                                                <p class="text-danger">
                                                    {{ $errors->first('remarks') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm ml-left">Save</button>
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

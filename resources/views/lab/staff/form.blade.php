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
                    <h1>Staff </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('hospital-staff.index') }}" class="global-btn">View All Staff</a>
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
                @if ($staff->id)
                    <form action="{{ route('hospital-staff.update', $staff->id) }}" method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('hospital-staff.store') }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-header">Staff Form</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="name">name: *</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        aria-describedby="nameId" value="{{ old('name', $staff->name) }}" placeholder=""
                                        required>
                                    @error('name')
                                        <small id="nameId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="email">email : *</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        aria-describedby="emailId" value="{{ old('email', $staff->email) }}"
                                        placeholder="" required>
                                    @error('email')
                                        <small id="emailId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="name">phone : *</label>
                                    <input type="number" class="form-control" name="phone" id="phone"
                                        aria-describedby="phoneId" value="{{ old('phone', $staff->phone) }}"
                                        placeholder="" required>
                                    @error('phone')
                                        <small id="phoneId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="address">address : *</label>
                                    <input type="text" class="form-control" name="address" id="address"
                                        aria-describedby="addressId" value="{{ old('address', $staff->address) }}"
                                        placeholder="" required>
                                    @error('address')
                                        <small id="addressId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label for="users">
                                        Designation
                                    </label>
                                    <div class="row">
                                        <div class="col-md-9 pr-0">
                                            <select class="form-control select2" name="designationId" id="designationId">
                                                <option>--Select Designation--</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}" @if ($staff->designationId = $designation->id)
                                                        {{ 'selected' }}
                                                @endif
                                                data-login="{{ $designation->login }}">{{ $designation->title }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3" style="padding-left: 7px;">
                                            <button type="button" data-toggle='modal'
                                                data-target='#adddesignation' data-toggle='tooltip'
                                                data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                title="Add New Portal"><i class="fas fa-plus"></i></button>
                                        </div>
                                        @error('designationId')
                                            <small id="helpId" class="text-danger">{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="description">description</label>
                                    <textarea class="form-control" name="description" id="description"
                                        rows="3">{{ old('description', $staff->description) }}</textarea>
                                </div>
                                @error('description')
                                    <small id="helpId" class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @if (!$staff->id)
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <label for="login">Can Login </label>
                                        <span style="margin-left: 5px; font-size: 15px;"> No </span>
                                        <label class="switch pt-0">
                                            <input type="checkbox" name="login" id="login">
                                            <span class="slider round"></span>
                                        </label>
                                        <span style="margin-left: 5px; font-size: 15px;">Yes</span>
                                    </div>
                                </div>
                                <div class="row col-12" id="passwordToggle">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password<i class="text-danger">*</i>: </label>
                                            <input type="password" name="password" class="form-control"
                                                value="{{ old('password') }}" placeholder="Password">
                                            @error('password')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirmpassword">Confirm Password<i class="text-danger">*</i>:
                                            </label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                value="{{ @old('password_confirmation') }}"
                                                placeholder="Re-Enter Password">
                                            @error('password_confirmation')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            @endif
                            <div class="col-md-12">
                                <div class="btn-bulk mt-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="Reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class='modal fade text-left' id='adddesignation' tabindex='-1' role='dialog'
        aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document' style="max-width: 1000px;">
            <div class='modal-content'>
                <div class='modal-header text-center'>
                    <h2 class='modal-title' id='exampleModalLabel'>Add New Desigantion</h2>
                    <button type='button' class='close' data-dismiss='modal'
                        aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action="{{ route('hospital-designation.store') }}" method="POST" id="adddesignation">
                        @csrf
                        @method("POST")
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Title <span
                                                            class="text-danger">*</span>:
                                                    </label>
                                                    <input type="text" name="title"
                                                        class="form-control"
                                                       >
                                                    <p class="text-danger">
                                                        {{ $errors->first('name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_id">Description :</label>
                                                            <textarea class="form-control" name="descriptionDesignation" id="description"
                                                            rows="3">{{ old('description', $staff->description) }}</textarea>

                                                </div>
                                            </div>



                                            <div class="col-md-12 text-center">
                                                <button type="button"
                                                    class="btn btn-secondary btn-sm adddesignation">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
            checkPassword();
            $("#login").on('click', checkPassword);
        });
        var checkPassword = function() {
            $('#passwordToggle').toggle();
        }
    </script>

    <script>
        $('.adddesignation').click(function(){
            $.post("{{route('hospital-designation.store')}}",{
                title:$('input[name="title"]').val(),
                description:$('textarea[name="descriptionDesignation"]').val(),
                "_token":"{{csrf_token()}}"},function(response){
                    $('#designationId option').eq(0).after(response);
                    $('#adddesignation').modal('hide');
                })

        })
    </script>
@endpush

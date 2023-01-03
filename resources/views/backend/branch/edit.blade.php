@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Update Branch Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('branch.index') }}" class="global-btn">View Branches</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content mt">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Edit Branch</h2>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('branch.update', $branch->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="company_id">Company<i class="text-danger">*</i>:</label>
                                            <select name="company_id" id="company_id" class="form-control">
                                                @foreach ($companies as $company)
                                                <option value="{{$company->id}}" {{$company->id == $branch->company_id ? "selected" : ""}}>{{$company->name}}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('company_id') }}
                                            </p>
                                        </div>
                                    </div> --}}
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Branch Name<i class="text-danger">*</i>:</label>
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        value="{{ old('name', $branch->name) }}" />
                                                    <input type="hidden" name="company_id"
                                                        value="{{ $currentcomp->company_id }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('name') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="email">Email<i class="text-danger">*</i>:</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ old('email', $branch->email) }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('email') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="phone">Contact no.<i class="text-danger">*</i>:</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ old('phone', $branch->phone) }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('phone') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="province">Province no<i
                                                            class="text-danger">*</i>.</label>
                                                    <select name="province_no" class="form-control province">
                                                        <option value="">--Select a province--</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $province->id == $branch->province_no ? 'selected' : '' }}>
                                                                {{ $province->eng_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('province_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="district">Districts<i class="text-danger">*</i></label>
                                                    <select name="district_no" class="form-control" id="district">
                                                        @foreach ($district_group as $district)
                                                            <option value="{{ $district->id }}"
                                                                {{ $branch->district_no == $district->id ? 'selected' : '' }}>
                                                                {{ $district->dist_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('district_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="address">Local Address<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="local_address" class="form-control"
                                                        value="{{ old('local_address', $branch->local_address) }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('local_address') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary ml-auto">Submit</button>
                                                </div>
                                            </div>
                                    </form>
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
        $(function() {
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {

                    var uri = "{{ route('getdistricts', ':no') }}";
                    uri = uri.replace(':no', province_no);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            console.log(districts);
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });
    </script>

    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>
@endpush

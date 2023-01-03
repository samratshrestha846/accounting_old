@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

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
                                    <h2>{{ $setting->company_name }}</h2>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('setting.update', $setting->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="company_name">Company Name<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="text" id="company_name" name="company_name"
                                                        class="form-control" value="{{ $setting->company_name }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_name') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="company_email">Email<i class="text-danger">*</i>:</label>
                                                    <input type="text" name="company_email" class="form-control"
                                                        value="{{ $setting->company_email }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_email') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="company_phone">Contact no.<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="text" name="company_phone" class="form-control"
                                                        value="{{ $setting->company_phone }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_phone') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="province">Province no<i
                                                            class="text-danger">*</i>.</label>
                                                    <select name="province" class="form-control province">
                                                        <option value="">--Select a province--</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $province->id == $setting->province_id ? 'selected' : '' }}>
                                                                {{ $province->eng_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('province') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="district">Districts<i class="text-danger">*</i></label>
                                                    <select name="district" class="form-control" id="district">
                                                        @foreach ($district_group as $district)
                                                            <option value="{{ $district->id }}"
                                                                {{ $district->id == $setting->district_id ? 'selected' : '' }}>
                                                                {{ $district->dist_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('district') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="address">Local Address<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="address" class="form-control"
                                                        value="{{ $setting->address }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('address') }}
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="registration_no">Company Registration no<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="registration_no" class="form-control"
                                                        value="{{ $setting->registration_no }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('registration_no') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pan_vat">PAN / VAT<i class="text-danger">*</i></label>
                                                    <input type="text" name="pan_vat" class="form-control"
                                                        value="{{ $setting->pan_vat }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('pan_vat') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="company_logo">Company Logo<i
                                                            class="text-danger">*</i>:</label>
                                                    <input type="file" name="company_logo" class="form-control"
                                                        onchange="loadFile(event)">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="">Current Logo<i class="text-danger">*</i>:</label><br>
                                                <img id="output" style="height: 50px;"
                                                    src="{{ Storage::disk('uploads')->url($setting->logo) }}">
                                            </div>
                                            {{-- <div class="col-md-3">
                                              <label for="">Invoice Color:<i class="text-danger"><br>
                                              <input type="color" id="invoice_color" name="invoice_color" value="{{ $setting->invoice_color }}">
                                            </div> --}}
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ml-auto">Submit</button>
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

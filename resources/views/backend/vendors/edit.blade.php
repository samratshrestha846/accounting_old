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
                    <h1>Edit Company Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.index') }}" class="global-btn">Our Suppliers</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <form action="{{ route('vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Company Details</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Company Name<i class="text-danger">*</i></label>
                                                    <input type="text" name="company_name" class="form-control"
                                                        value="{{ old('company_name', $vendor->company_name) }}"
                                                        placeholder="Enter Company Name">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_name') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Company Code (Unique)</label>
                                                    <input type="text" name="supplier_code" class="form-control"
                                                        value="{{ old('supplier_code', $vendor->supplier_code) }}"
                                                        placeholder="Enter Company Code">
                                                    <p class="text-danger">
                                                        {{ $errors->first('supplier_code') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="company_email">Company Email</label>
                                                    <input type="email" name="company_email" class="form-control"
                                                        value="{{ old('company_email', $vendor->company_email) }}"
                                                        placeholder="Enter Company Email">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_email') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="company_phone">Company Phone.</label>
                                                    <input type="text" name="company_phone" class="form-control"
                                                        value="{{ old('company_phone', $vendor->company_phone) }}"
                                                        placeholder="Enter Company Contact no.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_phone') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                    <input type="text" name="pan_vat" class="form-control" value="{{ old('pan_vat', $vendor->pan_vat) }}" placeholder="Enter Company PAN or VAT No.">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="province">Province no.</label>
                                                    <select name="province" class="form-control province">
                                                        <option value="">--Select a province--</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $province->id == $vendor->province_id ? 'selected' : '' }}>
                                                                {{ $province->eng_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('province') }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if ($district == null)
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="district">Districts</label>
                                                        <select name="district" class="form-control" id="district">
                                                            <option value="">--Select a district--</option>
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('district') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="district">Districts</label>
                                                        <select name="district" class="form-control" id="district">
                                                            <option value="">--Select a district--</option>
                                                            <option value="{{ $district->id }}" selected>
                                                                {{ $district->dist_name }}</option>
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('district') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="company_address">Company Address</label>
                                                    <input type="text" name="company_address" class="form-control"
                                                        value="{{ old('company_address', $vendor->company_address) }}"
                                                        placeholder="Company Address">
                                                    <p class="text-danger">
                                                        {{ $errors->first('company_address') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="logo">Select a logo (optional)</label>
                                                    <input type="file" name="logo" class="form-control" onchange="loadFile(event)">
                                                    <p class="text-danger">
                                                        {{ $errors->first('logo') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Preview:</label> <br>
                                                <img src="{{ Storage::disk('uploads')->url($vendor->logo) }}" id="output" style="height: 50px;">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="is_client">Is Client:</label><br>
                                                    <span style="margin-right: 5px; font-size: 12px;">NO</span>
                                                        <label class="switch pt-0">
                                                            <input type="checkbox" name="is_client" value="1" {{ $vendor->is_client == 1 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">YES</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header" style="display: flex;justify-content:space-between;align-items:center;">
                                        <h2>Concerned Person Details </h2>
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onClick="addVendorRow('vendor_body')">Add</a>
                                    </div>
                                    <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover text-center m-0"
                                                    id="vendor_table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center" style="width: 25%;"> Name</th>
                                                            <th class="text-center" style="width: 20%;"> Phone No.</th>
                                                            <th class="text-center" style="width: 20%;"> Email</th>
                                                            <th class="text-center" style="width: 20%;"> Designation</th>
                                                            <th class="text-center" style="width: 5%;"> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="vendor_body">
                                                        @foreach ($vendor->vendorconcerns as $concern)
                                                        <tr>
                                                            <td>
                                                                <div class="form-group m-0">
                                                                    <input type="text" name="concerned_name[]" class="form-control"
                                                                        value="{{ $concern->concerned_name }}" placeholder="Enter Name">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_name') }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group m-0">
                                                                    <input type="text" name="concerned_phone[]" class="form-control"
                                                                        value="{{ $concern->concerned_phone }}" placeholder="Enter Phone">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_phone') }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group m-0">
                                                                    <input type="email" name="concerned_email[]" class="form-control"
                                                                        value="{{ $concern->concerned_email }}" placeholder="Enter Email">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_email') }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group m-0">
                                                                    <input type="text" name="designation[]" class="form-control"
                                                                        value="{{ $concern->designation }}" placeholder="Enter Designation">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('designation') }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary icon-btn btn-sm" style="margin:auto;" type="button"
                                                                    value="Delete" onclick="deleteVendorRow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                </div>
                                <div class="btn-bulk d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
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
        $(function() {
            $('.province').change(function() {
                var province_no = $(this).children("option:selected").val();

                function fillSelect(districts) {
                    document.getElementById("district").innerHTML =
                        districts.reduce((tmp, x) =>
                            `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
                }

                function fetchRecords(province_no) {
                    var uri = "{{ route('getdistricts', ':id') }}"
                    uri = uri.replace(":id", province_no);
                    $.ajax({
                        url: uri,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var districts = response;
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });
    </script>
    <script>
        function addVendorRow(divName) {
            // var optionval = $("#headoption").val();
            var row = $("#vendor_table tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabindex = count * 2;
                newdiv = document.createElement("tr");
                newdiv.innerHTML = `<td>
                                        <div class='form-group m-0'>
                                            <input type='text' name='concerned_name[]' class='form-control' placeholder='Enter Name'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group m-0'>
                                            <input type='text' name='concerned_phone[]' class='form-control' placeholder='Enter Phone'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group m-0'>
                                            <input type='email' name='concerned_email[]' class='form-control' placeholder='Enter Email'>
                                        </div>
                                    </td>
                                    <td>
                                        <div class='form-group m-0'>
                                            <input type='text' name='designation[]' class='form-control' placeholder='Enter Designation'>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary icon-btn btn-sm" style="margin:auto;" type="button"
                                            value="Delete" onclick="deleteVendorRow(this)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    `;
                document.getElementById(divName).appendChild(newdiv);
                // document.getElementById(tabin).focus();
                // $("#goDown_" + count).html(optionval);
                // count++;
                $("select.form-control:not(.dont-select-me)").select2({
                    // placeholder: "--Select One--",
                    // allowClear: true
                });
            }
        }

        function deleteVendorRow(e) {
            var t = $("#vendor_table > tbody > tr").length;
            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateTotal()
        }
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

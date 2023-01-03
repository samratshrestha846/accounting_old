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
                    <h1>Edit Customer</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('client.index') }}" class="global-btn">Our Customers</a>
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
                            <form action="{{ route('client.update', $clt->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Customer Details</h2>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="client_type">Customer Type<i
                                                            class="text-danger">*</i></label>
                                                    <select name="client_type" id="client_type" class="form-control">
                                                        <option value="company"
                                                            {{ $clt->client_type == 'company' ? 'selected' : '' }}>Company
                                                        </option>
                                                        <option value="person"
                                                            {{ $clt->client_type == 'person' ? 'selected' : '' }}>Person
                                                        </option>
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('client_type') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="dealer_type_id">Dealer Type<i
                                                            class="text-danger">*</i></label>
                                                    <select name="dealer_type_id" id="dealer_type_id" class="form-control">
                                                        @foreach ($dealerTypes as $dealertype)
                                                        <option value="{{$dealertype->id}}" {{$dealertype->id == $clt->dealer_type_id ? 'selected' : ''}}>{{$dealertype->title}}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('dealer_type_id') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Full Name<i class="text-danger">*</i></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $clt->name }}" placeholder="Enter Customer's Name">
                                                    <p class="text-danger">
                                                        {{ $errors->first('name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="name">Customer Code (Unique)</label>
                                                    <input type="text" name="client_code" class="form-control"
                                                        value="{{ $clt->client_code }}" placeholder="Enter Customer Code"
                                                        id="client_code">
                                                    <p class="text-danger clientcode_error hide">Code is already used. Use
                                                        Different code.</p>
                                                    <p class="text-danger">
                                                        {{ $errors->first('client_code') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="email">Customer Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $clt->email }}" placeholder="Enter Customer Email">
                                                    <p class="text-danger">
                                                        {{ $errors->first('email') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="province">Province no.</label>
                                                    <select name="province" class="form-control province">
                                                        <option value="">--Select a province--</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province->id }}"
                                                                {{ $clt->province == $province->id ? 'selected' : '' }}>
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
                                                            @foreach($districts as $district)
                                                            <option value="{{$district->id}}" @if($clt->district == $district->id) selected @endif>{{$district->dist_name}}</option>
                                                            @endforeach
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
                                                                {{ $district->dist_name }}
                                                            </option>
                                                            {{-- @foreach ($district_group as $district)
                                                            <option value="{{ $district->id }}"{{ $district->id == $clt->district ? 'selected' : '' }}>{{ $district->dist_name }}</option>
                                                        @endforeach --}}
                                                        </select>
                                                        <p class="text-danger">
                                                            {{ $errors->first('district') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="local_address">Customer Local Address</label>
                                                    <input type="text" name="local_address" class="form-control"
                                                        value="{{ $clt->local_address }}"
                                                        placeholder="Customer Local Address">
                                                    <p class="text-danger">
                                                        {{ $errors->first('local_address') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="phone">Customer Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ $clt->phone }}"
                                                        placeholder="Enter Customer Contact no.">
                                                    <p class="text-danger">
                                                        {{ $errors->first('phone') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="pan_vat">PAN No./VAT No. (Optional)</label>
                                                    <input type="text" name="pan_vat" class="form-control"
                                                        value="{{ $clt->pan_vat }}"
                                                        placeholder="Enter Company PAN or VAT No.">
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
                                                <img src="{{ Storage::disk('uploads')->url($clt->logo) }}" id="output" style="height: 50px;">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="is_vendor">Is Vendor:</label><br>
                                                    <span style="margin-right: 5px; font-size: 12px;">NO</span>
                                                        <label class="switch pt-0">
                                                            <input type="checkbox" name="is_vendor" value="1" @if($clt->is_vendor == 1) checked @endif>
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
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onClick="addClientRow('client_body')">Add</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12 mt-4 mb-4">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover text-center"
                                                    id="client_table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center" style="width: 25%;"> Name</th>
                                                            <th class="text-center" style="width: 20%;"> Phone No.</th>
                                                            <th class="text-center" style="width: 20%;"> Email</th>
                                                            <th class="text-center" style="width: 20%;"> Designation</th>
                                                            <th class="text-center" style="width: 5%;"> Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="client_body">
                                                        @foreach ($clt->clientconcerns as $concern)
                                                        <tr>
                                                            <td>
                                                                    <input type="text" name="concerned_name[]" class="form-control"
                                                                        value="{{ $concern->concerned_name }}" placeholder="Enter Name">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_name') }}
                                                                    </p>
                                                            </td>
                                                            <td>
                                                                    <input type="text" name="concerned_phone[]" class="form-control"
                                                                        value="{{ $concern->concerned_phone }}" placeholder="Enter Phone">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_phone') }}
                                                                    </p>
                                                            </td>
                                                            <td>
                                                                    <input type="email" name="concerned_email[]" class="form-control"
                                                                        value="{{ $concern->concerned_email }}" placeholder="Enter Email">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('concerned_email') }}
                                                                    </p>
                                                            </td>
                                                            <td>
                                                                    <input type="text" name="designation[]" class="form-control"
                                                                        value="{{ $concern->designation }}" placeholder="Enter Designation">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('designation') }}
                                                                    </p>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary icon-btn btn-sm m-auto" type="button"
                                                                    value="Delete" onclick="deleteClientRow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary ml-auto">Submit</button>
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

            var clientcodes = @php echo json_encode($allclientcodes) @endphp;
            $("#client_code").change(function() {
                var clientval = $(this).val();
                if ($.inArray(clientval, clientcodes) != -1) {
                    $('.clientcode_error').addClass('show');
                    $('.clientcode_error').removeClass('hides');
                } else {
                    $('.clientcode_error').removeClass('show');
                    $('.clientcode_error').addClass('hide');
                }
            })
        });
    </script>
    <script>
        function addClientRow(divName) {
            // var optionval = $("#headoption").val();
            var row = $("#client_table tbody tr").length;
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
                                        <button class="btn btn-primary icon-btn btn-sm m-auto" type="button"
                                            value="Delete" onclick="deleteClientRow(this)">
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

        function deleteClientRow(e) {
            var t = $("#client_table > tbody > tr").length;
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

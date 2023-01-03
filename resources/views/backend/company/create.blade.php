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
                    <h1>New Company Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('company.index') }}" class="global-btn">All Companies</a>
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
                <div class="col-sm-12">
                    <div class="alert  alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
        <div class="ibox">
            <div class="row ibox-body">
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Create Company</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{route('company.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Company Name<i class="text-danger">*</i>:</label>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ @old('name') }}" placeholder="Enter Company Name"/>
                                            <p class="text-danger">
                                                {{ $errors->first('name') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email<i class="text-danger">*</i>:</label>
                                            <input type="text" name="email" class="form-control" value="{{ @old('email') }}" placeholder="Enter Company Email">
                                            <p class="text-danger">
                                                {{ $errors->first('email') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Contact no.<i class="text-danger">*</i>:</label>
                                            <input type="text" name="phone" class="form-control" value="{{ @old('phone') }}" placeholder="Enter Company contact no.">
                                            <p class="text-danger">
                                                {{ $errors->first('phone') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province">Province no<i class="text-danger">*</i>.</label>
                                            <select name="province_no" class="form-control province">
                                                <option value="">--Select a province--</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}" {{ old('province_no') == $province->id ? 'selected' : '' }}>{{ $province->eng_name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('province_no') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="district">Districts<i class="text-danger">*</i></label>
                                            <select name="district_no" class="form-control" id="district">
                                                <option value="">--Select a province first--</option>
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('district_no') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="local_address">Local Address<i class="text-danger">*</i></label>
                                            <input type="text" name="local_address" class="form-control" value="{{ @old('local_address') }}" placeholder="Eg: Banasthali-Marg">
                                            <p class="text-danger">
                                                {{ $errors->first('local_address') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pan_vat">PAN / VAT<i class="text-danger">*</i></label>
                                            <input type="text" name="pan_vat" class="form-control" value="{{ @old('pan_vat') }}" placeholder="PAN or VAT">
                                            <p class="text-danger">
                                                {{ $errors->first('pan_vat') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="registration_no">Registration No<i class="text-danger">*</i></label>
                                            <input type="text" name="registration_no" class="form-control" value="{{ @old('registration_no') }}" placeholder="Company Registration NO.">
                                            <p class="text-danger">
                                                {{ $errors->first('registration_no') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="logo">Company Logo<i class="text-danger">*</i>:</label>
                                            <input type="file" name="company_logo" class="form-control" onchange="loadFile(event)">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                             <img width="100" height="100" id="output_image">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="ird_sync">IRD Sync</label><br>
                                        <input type="checkbox" name="ird_sync" value="1">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_importer">Is Importer?: </label>
                                            <label class="switch pt-0">
                                                <input type="checkbox" name="is_importer" value="1">
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 15px;">Importer</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="invoice_color">Invoice Color</label><br>
                                            <input type="color" name="invoice_color">
                                        </div>
                                    </div>



                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-secondary">Submit</button>
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
                    districts.reduce((tmp, x) => `${tmp}<option value='${x.id}'>${x.dist_name}</option>`, '');
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
        var output = document.getElementById('output_image');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>
  @endpush

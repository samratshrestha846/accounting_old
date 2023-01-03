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
                    <h1>New Delivery Partner </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('delivery-partner.index') }}" class="global-btn">All Companies</a>
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
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{$error}}</div>
                @endforeach
            @endif
        <div class="ibox">
            <div class="row ibox-body">
                <div class="col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Create Delivery Partner</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{route('delivery-partner.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Partner Name<i class="text-danger">*</i>:</label>
                                            <input type="text" id="name" name="name" class="form-control" value="{{ @old('name') }}" placeholder="Enter Partner Name"/>
                                            <p class="text-danger">
                                                {{ $errors->first('name') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email<i class="text-danger">*</i>:</label>
                                            <input type="text" name="email" class="form-control" value="{{ @old('email') }}" placeholder="Enter Email">
                                            <p class="text-danger">
                                                {{ $errors->first('email') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="address">Address<i class="text-danger">*</i>:</label>
                                            <input type="text" name="address" class="form-control" value="{{ @old('address') }}" placeholder="Enter Address">
                                            <p class="text-danger">
                                                {{ $errors->first('address') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_number">Contact no.<i class="text-danger">*</i>:</label>
                                            <input type="text" name="contact_number" class="form-control" value="{{ @old('contact_number') }}" placeholder="Enter contact no.">
                                            <p class="text-danger">
                                                {{ $errors->first('contact_number') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province">Province no<i class="text-danger">*</i>.</label>
                                            <select name="province_id" class="form-control province">
                                                <option value="">--Select a province--</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>{{ $province->eng_name }}</option>
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
                                            <select name="district_id" class="form-control" id="district">
                                                <option value="">--Select a province first--</option>
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('district_id') }}
                                            </p>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo">Delivery Partner Logo<i class="text-danger">*</i>:</label>
                                            <input type="file" name="logo" class="form-control" onchange="loadFile(event)">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="is_active">Status: </label>
                                            <span style="margin-right: 5px; font-size: 12px;"> Inactive </span>
                                            <label class="switch pt-0">
                                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                                    {{ old('is_active') == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                            <span style="margin-left: 5px; font-size: 12px;">Active</span>
                                        </div>
                                        <p class="text-danger">
                                            {{ $errors->first('is_active') }}
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">Note: Status must be active in order to use it in pos</div>
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
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>
  @endpush

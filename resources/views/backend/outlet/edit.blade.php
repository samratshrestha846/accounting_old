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
                    <h1>Update Outlet Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('outlet.index') }}"
                            class="global-btn">View Outlets</a>
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

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('outlet.update', $outlet->id) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Outlet Name <i class="text-danger">*</i>:</label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Outlet Name" value="{{ old('name', $outlet->name) }}">
                                        <p class="text-danger">
                                            {{ $errors->first('name') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="province_id">Province no. <i class="text-danger">*</i>:</label>
                                        <select name="province_id" class="form-control province">
                                            <option value="">--Select a province--</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ $province->id == $outlet->province_id ? 'selected' : '' }}>
                                                    {{ $province->eng_name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('province_id') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="district_id">Districts <i class="text-danger">*</i>:</label>
                                        <select name="district_id" class="form-control" id="district">
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ $district->id == $outlet->district_id ? 'selected' : '' }}>
                                                    {{ $district->dist_name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-danger">
                                            {{ $errors->first('district_id') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="local_address">Local Address <i class="text-danger">*</i>:</label>
                                        <input type="text" name="local_address" class="form-control"
                                            placeholder="Eg: Chamti tole" value="{{ old('local_address', $outlet->local_address) }}">
                                        <p class="text-danger">
                                            {{ $errors->first('local_address') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm ml-auto">Update</button>
                                </div>
                            </div>
                        </form>
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
                            fillSelect(districts);
                        }
                    });
                }
                fetchRecords(province_no);
            })
        });
    </script>
@endpush

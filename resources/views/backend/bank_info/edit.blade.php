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
                    <h1>Update Bank Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('bank.index') }}" class="global-btn">View Bank Info</a>
                        <a href="{{ route('bank.show', $bank_info->id) }}" class="global-btn">Go Back</a>
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

                <form action="{{ route('bank.update', $bank_info->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Update Bank Info</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bank_name">Bank Name <span class="text-danger">*</span>:
                                                </label>
                                                <input type="text" name="bank_name" class="form-control"
                                                    placeholder="Enter Bank Name" value="{{ old('bank_name', $bank_info->bank_name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="head_branch">Branch <span class="text-danger">*</span>:
                                                </label>
                                                <select name="head_branch" class="form-control">
                                                    <option value="">--Select one option--</option>
                                                    <option value="Head Office"
                                                        {{ $bank_info->head_branch == 'Head Office' ? 'selected' : '' }}>
                                                        Head Office</option>
                                                    <option value="Branch"
                                                        {{ $bank_info->head_branch == 'Branch' ? 'selected' : '' }}>Branch
                                                    </option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('head_branch') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bank_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="bank_province_no" class="form-control province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}"
                                                            {{ $province->id == $bank_info->bank_province_no ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bank_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="bank_district_no" class="form-control" id="district">

                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}"
                                                            {{ $district->id == $bank_info->bank_district_no ? 'selected' : '' }}>
                                                            {{ $district->dist_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="bank_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="bank_local_address" class="form-control"
                                                    value="{{ old('bank_local_address', $bank_info->bank_local_address) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_local_address') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="account_no">Account no. <i class="text-danger">*</i>:</label>
                                                <input type="text" class="form-control" name="account_no"
                                                    value="{{ old('account_no', $bank_info->account_no) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('account_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="account_name">Account Name <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" class="form-control" name="account_name"
                                                    value="{{ old('account_name', $bank_info->account_name) }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('account_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="account_type">Account Type <span class="text-danger">*</span>:
                                                </label>
                                                <select name="account_type" class="form-control">
                                                    <option value="">--Select an option--</option>
                                                    @foreach ($bankAccountTypes as $bankAccountType)
                                                        <option value="{{ $bankAccountType->id }}" {{ $bank_info->account_type_id == $bankAccountType->id ? 'selected' : '' }}>{{ $bankAccountType->account_type_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('account_type') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-sm ml-auto">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

        $.fn.regexMask = function(mask) {
            $(this).keypress(function (event) {
                if (!event.charCode) return true;
                var part1 = this.value.substring(0, this.selectionStart);
                var part2 = this.value.substring(this.selectionEnd, this.value.length);
                if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
                    return false;
            });
        };

        $(document).ready(function() {
            var mask = new RegExp('^[A-Za-z0-9 ]*$')
            $("input").regexMask(mask)
        });
    </script>
@endpush

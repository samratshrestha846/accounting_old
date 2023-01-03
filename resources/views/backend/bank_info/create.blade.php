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
                    <h1>New Bank Info </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('bank.index') }}" class="global-btn">View Bank Info</a>
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
                    <div class="card-header">
                        <h2>New Bank Info</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bank.store') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="bank_name">Bank Name <span class="text-danger">*</span>:
                                                </label>
                                                <input type="text" name="bank_name" class="form-control"
                                                    placeholder="Enter Bank Name" value="{{ old('bank_name') }}">
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="head_branch">Branch <span class="text-danger">*</span>:
                                                </label>
                                                <select name="head_branch" class="form-control">
                                                    <option value="">--Select one option--</option>
                                                    <option value="Head Office" {{ old('head_branch') == "Head Office" ? 'selected' : '' }}>Head Office</option>
                                                    <option value="Branch" {{ old('head_branch') == "Branch" ? 'selected' : '' }}>Branch</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('head_branch') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="bank_province_no">Province no. <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="bank_province_no" class="form-control province">
                                                    <option value="">--Select a province--</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}" {{ old('bank_province_no') == $province->id ? 'selected' : '' }}>
                                                            {{ $province->eng_name }}</option>
                                                    @endforeach
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_province_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="bank_district_no">Districts <i
                                                        class="text-danger">*</i>:</label>
                                                <select name="bank_district_no" class="form-control" id="district">
                                                    <option value="">--Select a province first--</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_district_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="bank_local_address">Local Address <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" name="bank_local_address" class="form-control" value="{{ old('bank_local_address') }}"
                                                    placeholder="Eg: Chamti tole">
                                                <p class="text-danger">
                                                    {{ $errors->first('bank_local_address') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="account_no">Account no. <i class="text-danger">*</i>:</label>
                                                <input type="text" class="form-control" name="account_no" value="{{ old('account_no') }}"
                                                    placeholder="Enter Account no.">
                                                <p class="text-danger">
                                                    {{ $errors->first('account_no') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="account_name">Account Name <i
                                                        class="text-danger">*</i>:</label>
                                                <input type="text" class="form-control" name="account_name" value="{{ old('account_name') }}"
                                                    placeholder="Enter Account Name">
                                                <p class="text-danger">
                                                    {{ $errors->first('account_name') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="account_type">Account Type <span class="text-danger">*</span>:
                                                </label>

                                                <div class="row">
                                                    <div class="col-md-9 pr-0">
                                                        <select name="account_type" class="form-control" id="account_types">
                                                            <option value="">--Select an option--</option>
                                                            @foreach ($bankAccountTypes as $bankAccountType)
                                                                <option value="{{ $bankAccountType->id }}" {{ old('account_type') == $bankAccountType->id ? 'selected' : '' }}>{{ $bankAccountType->account_type_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3" style="padding-left:7px;">
                                                        <button type="button" data-toggle='modal'
                                                            data-target='#bankinfoadd' data-toggle='tooltip'
                                                            data-placement='top' class="btn btn-primary icon-btn btn-sm"
                                                            title="Add New Bank"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>

                                                <p class="text-danger">
                                                    {{ $errors->first('account_type') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="opening_balance">Opening Balance</label>
                                                <input type="number" name="opening_balance" min="" class="form-control opening_balance" value="{{ @old('opening_balance') ?? 0 }}" step=".01">
                                                <p class="text-danger">
                                                    {{ $errors->first('opening_balance') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="behaviour">Opening Balance (Opt) </label>
                                                <select name="behaviour" class="form-control behaviour">
                                                    <option value="debit">Debit</option>
                                                    <option value="credit">Credit</option>
                                                </select>
                                                <p class="text-danger">
                                                    {{ $errors->first('behaviour') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-sm ml-auto">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class='modal fade text-left' id='bankinfoadd' tabindex='-1' role='dialog'
                        aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document' style="max-width: 500px;">
                            <div class='modal-content'>
                                <div class='modal-header text-center'>
                                    <h2 class='modal-title' id='exampleModalLabel'>Add New Account Type</h2>
                                    <button type='button' class='close' data-dismiss='modal'
                                        aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <form action="" method="POST" id="bank_add_form">
                                        @csrf
                                        @method("POST")
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="account_type_name">Account Type Name <span
                                                                            class="text-danger">*</span>:
                                                                    </label>
                                                                    <input type="text" name="account_type_name"
                                                                        class="form-control"
                                                                        id="account_type_name"
                                                                        placeholder="Enter Account Type Name"
                                                                        value="{{ old('account_type_name') }}">
                                                                    <p class="text-danger">
                                                                        {{ $errors->first('account_type_name') }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 text-center">
                                                                <button type="submit"
                                                                    class="btn btn-secondary btn-sm">Submit</button>
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

        $(document).ready(function() {
            $("#bank_add_form").submit(function(event) {
                var formData = {
                    account_type_name: $("#account_type_name").val()
                };

                $.ajax({
                    type: "POST",
                    url: "{{ route('post.apiBankAccountType') }}",
                    data: formData,
                    dataType: "json",
                    encode: true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    function fillBankAccountType(bank_info) {
                        document.getElementById("account_types").innerHTML =
                            '<option value=""> --Select an option-- </option>' +
                            bank_info.reduce((tmp, x) =>
                                `${tmp}<option value='${x.id}'>${x.account_type_name}</option>`,
                                '');
                    }

                    function fetchBankAccountType() {
                        $.ajax({
                            url: "{{ route('apiBankAccountType') }}",
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {
                                var bank_info = response;
                                fillBankAccountType(bank_info);
                            }
                        });
                    }
                    fetchBankAccountType();

                    $("#bank_add_form").html(
                        '<div class="alert alert-success">Successfully added.</div>'
                    );
                });
                event.preventDefault();
            });
        });
    </script>
@endpush

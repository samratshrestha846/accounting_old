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
                    <h1>Update Payroll</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('payrollIndex') }}" class="global-btn">View Payrolls</a>
                    </div>
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
                        <form action="{{ route('updatePayroll', $payroll->id) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Select a staff</label>
                                        <input type="hidden" name="staff" value="{{ $payroll->staff_id }}">
                                        <select name="staff" class="form-control staff_select" disabled>
                                            <option value="">--Select a staff--</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->id }}"
                                                    {{ $staff->id == $payroll->staff_id ? 'selected' : '' }}>
                                                    {{ $staff->name }}</option>
                                            @endforeach
                                            <p class="text-danger">
                                                {{ $errors->first('staff') }}
                                            </p>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Date (In B.S)</label>
                                        <input type="text" name="payment_date_nepali" id="payment_date_nepali"
                                            class="form-control" value="{{ $payroll->nepali_date }}">
                                        <p class="text-danger">
                                            {{ $errors->first('payment_date') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Date (In A.D)</label>
                                        <input type="text" name="payment_date" class="form-control" id="english"
                                            value="{{ $payroll->date }}" readonly>
                                        <p class="text-danger">
                                            {{ $errors->first('payment_date') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Amount Paid (In Rs.)</label>
                                        <input type="number" step="any" name="amount_paid" class="form-control"
                                            placeholder="Amount Paid.." value="{{ $payroll->amount_paid }}">
                                        <p class="text-danger">
                                            {{ $errors->first('amount_paid') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Type</label>
                                        <select name="payment_type" class="form-control" required>
                                            <option value="">--Select a type--</option>
                                            <option value="0" {{ $payroll->advance_regular == 0 ? 'selected' : '' }}>
                                                Regular</option>
                                            <option value="1" {{ $payroll->advance_regular == 1 ? 'selected' : '' }}>
                                                Advance</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-secondary">Update</button>
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
        window.onload = function() {
            var mainInput = document.getElementById("payment_date_nepali");

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
        }
    </script>
@endpush

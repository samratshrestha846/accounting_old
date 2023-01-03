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
                    <h1>Payroll </h1>
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
                        <form action="{{ route('savePayroll') }}" method="POST">
                            @csrf
                            @method("POST")
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Select a staff</label>
                                        <select name="staff" class="form-control staff_select" required>
                                            <option value="">--Select a staff--</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                            @endforeach
                                            <p class="text-danger">
                                                {{ $errors->first('staff') }}
                                            </p>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" id="button">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Date (In B.S)</label>
                                        <input type="text" name="payment_date_nepali" id="payment_date_nepali"
                                            class="form-control">
                                        <p class="text-danger">
                                            {{ $errors->first('payment_date') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Payment Date (In A.D)</label>
                                        <input type="text" name="payment_date" class="form-control" id="english"
                                            value="{{ date('Y-m-d') }}" readonly>
                                        <p class="text-danger">
                                            {{ $errors->first('payment_date') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Amount Paid (In Rs.)</label>
                                        <input type="number" step="any" name="amount_paid" class="form-control"
                                            placeholder="Amount Paid..">
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
                                            <option value="0">Regular</option>
                                            <option value="1">Advance</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-secondary">Submit</button>
                                </div>
                            </div>
                        </form>

                        {{-- Modal for budget info --}}
                        <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog"
                            aria-labelledby="mediumModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3>Last Budget Information</h3>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="mediumBody">
                                        <div id="filler">
                                            <!-- the result to be displayed apply here -->
                                        </div>
                                    </div>
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
        window.onload = function() {
            const d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var today = year + '-' + month + '-' + day;
            var mainInput = document.getElementById("payment_date_nepali");
            var engtodayobj = NepaliFunctions.ConvertToDateObject(today, "YYYY-MM-DD");
            var neptoday = NepaliFunctions.ConvertDateFormat(NepaliFunctions.AD2BS(engtodayobj), "YYYY-MM-DD");
            document.getElementById('payment_date_nepali').value = neptoday;

            mainInput.nepaliDatePicker({
                onChange: function() {
                    var nepdate = mainInput.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
        }



        $(function() {
            $('.staff_select').change(function() {
                var staff_id = $(this).children("option:selected").val();

                function fillAnotherSelect() {
                    document.getElementById("button").innerHTML = `<label for="">Last Payment Info</label>
                                                                <h5>Payment hasn't been done yet.</h5>`;
                }

                function fillSelect(staffinfo) {
                    document.getElementById("button").innerHTML =
                        `<a href="#" class="btn btn-primary mt-4" data-toggle="modal" id="mediumButton" data-target="#mediumModal">Last Payment Info</a>`;
                    var id = `${staffinfo.id}`;
                    document.getElementById("filler").innerHTML = `<h5><b>Date</b>: ${staffinfo.date}</h5>
                                                                <h5><b>Paid Amount</b>: Rs. ${staffinfo.amount_paid}</h5>
                                                                <h5><b>Paid Type</b>: ${staffinfo.advance_regular == 1 ? 'Regular' : 'Advance'}</h5>
                                                                <a href="/editPayroll/${id}" class="btn btn-primary btn-sm mt-2">Edit</a>
                                                            `;
                }

                function fetchstaffinfo(staff_id) {
                    $.ajax({
                        url: 'paymentInfo/' + staff_id,
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                            var staffinfo = response;
                            if (staffinfo.id == undefined) {
                                fillAnotherSelect();
                            } else {
                                fillSelect(staffinfo);
                            }
                        }
                    });
                }
                fetchstaffinfo(staff_id);
            })
        });
    </script>
@endpush

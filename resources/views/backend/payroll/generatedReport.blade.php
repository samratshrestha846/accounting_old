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
                    <h1>Payroll Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('payroll') }}" class="global-btn">Enter New Payroll</a>
                        <a href="{{ route('staff.index') }}" class="global-btn">View Staffs</a>
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
                        <h2>Generate Report</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('generatePayrollReport') }}" method="GET">
                            @csrf
                            @method("GET")
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Select a staff</label>
                                        <select name="staff" class="form-control" required>
                                            <option value="">--Select a staff--</option>
                                            @foreach ($staffs as $related_staff)
                                                <option value="{{ $related_staff->id }}">{{ $related_staff->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Starting date</label>
                                        <input type="text" name="starting_date" class="form-control" id="starting_date"
                                            value="{{ $neptoday }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ending date</label>
                                        <input type="text" name="ending_date" class="form-control" id="ending_date"
                                            value="{{ $neptoday }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm">Generate Report</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h2>Payroll for {{ $staff->name }}</h2>
                    </div>
                    <div class="card-body mid-body">
                        <h4>From {{ $starting_date }} to {{ $ending_date }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Date</th>
                                            <th class="text-nowrap">Staff</th>
                                            <th class="text-nowrap">Amount Paid</th>
                                            <th class="text-nowrap">Payment Type</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($payrolls) == 0)
                                            <tr>
                                                <td colspan="5">No payment information yet.</td>
                                            </tr>
                                        @else
                                            @foreach ($payrolls as $payroll)
                                                <tr>
                                                    <td>{{ $payroll->nepali_date }}</td>
                                                    <td>{{ $payroll->staff->name }}</td>
                                                    <td>Rs. {{ $payroll->amount_paid }}</td>
                                                    <td>{{ $payroll->advance_regular == 1 ? 'Advance' : 'Regular' }}</td>
                                                    <td style="width: 120px;">
                                                        <div class="btn-bulk justify-content-center">
                                                            @php
                                                            $editurl = route('editPayroll', $payroll->id);
                                                            $deleteurl = route('deletePayroll', $payroll->id);
                                                            $printurl = route('printSinglePayroll', $payroll->id);
                                                            $csrf_token = csrf_token();
                                                            $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                                                                                                                                                                                                            <a href='$printurl' class='edit btn btn-secondary icon-btn btn-sm' title='Print'><i class='fa fa-download'></i></a>
                                                                                                                                                                                                                                                            <button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#deletechild$payroll->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                                                                                                                                                                                                            <!-- Modal -->
                                                                                                                                                                                                                                                                <div class='modal fade text-left' id='deletechild$payroll->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                                    <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                                        <div class='modal-content'>
                                                                                                                                                                                                                                                                            <div class='modal-header'>
                                                                                                                                                                                                                                                                            <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                                                                                                                                                                                                                                                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                                <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                            <div class='modal-body text-center'>
                                                                                                                                                                                                                                                                                <form action='$deleteurl' method='POST' style='display:inline-block;'>
                                                                                                                                                                                                                                                                                <input type='hidden' name='_token' value='$csrf_token'>
                                                                                                                                                                                                                                                                                <label for='reason'>Are you sure you want to delete??</label><br>
                                                                                                                                                                                                                                                                                <input type='hidden' name='_method' value='DELETE' />
                                                                                                                                                                                                                                                                                    <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                                                                                                                                                                                                                                                </form>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                </div>";
                                                            echo $btn;
                                                        @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
    <script type="text/javascript">
        window.onload = function() {
            var starting_date = document.getElementById("starting_date");
            var ending_date = document.getElementById("ending_date");
            starting_date.nepaliDatePicker();
            ending_date.nepaliDatePicker();
        };
    </script>
@endpush

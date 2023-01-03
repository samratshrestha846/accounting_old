@extends('backend.layouts.app')
@push('styles')
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="right_col" role="main">

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
    <h1 class="mb-3">Salary Payment Record <a href="{{route('admin.staff.index')}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View Our Staffs</a></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <form action="{{route('admin.salarypayment.store')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="staff">Staffs</label>
                                <select name="staff_id" id="" class="form-control">
                                    <option value="">--Select a staff--</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{$staff->id}}">{{$staff->name}}</option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="for_month">For the month of:</label>
                                @php
                                    $today = date('Y-m');
                                @endphp
                                <input type="month" class="form-control" name="for_month" value="{{$today}}">
                                @error('for_month')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date">
                                @error('payment_date')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Payment Amount (in Rs.)</label>
                                <input type="text" class="form-control" name="amount" placeholder="Amount in Rs.">
                                @error('amount')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_type">Payment Type</label>
                                <select name="salary_type" id="" class="form-control">
                                    <option value="">--Select a type--</option>
                                    <option value="advance">Advance</option>
                                    <option value="regular">Regular</option>
                                </select>
                                @error('salary_type')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

                <hr>

            <div class="card">

                <div class="card-header">
                    <h1 class="text-center">Salary paid for this month {{date('F, Y')}} </h1>
                    <p class="text-center">(Total Working Days = 30 days)</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="table-responsive">
                                <table class="table table-bordered dataTable text-center" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Staff Name</th>
                                            <th>Position</th>
                                            <th>Allocated Salary</th>
                                            <th>Paid Amount</th>
                                            <th>Paid on</th>
                                            <th>Payment Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')

<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(function () {

      var table = $('.dataTable').DataTable({

          processing: true,
          serverSide: true,
          ajax: "{{ route('admin.salarypayment.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'staffname', name: 'staffname'},
              {data: 'position', name: 'position'},
              {data: 'allocated_salary', name: 'allocated_salary'},
              {data: 'amount', name: 'amount'},
              {data: 'paid_on', name: 'paid_on'},
              {data: 'salary_type', name: 'salary_type'},
              {
                  data: 'action',
                  name: 'action',
                  orderable: true,
                  searchable: true
              },
          ]
      });

    });
  </script>
@endpush

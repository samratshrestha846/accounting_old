@extends('backend.layouts.app')
@push('styles')
    <style>
        * {
          box-sizing: border-box;
        }

        #myInput {
          background-image: url('/uploads/search.png');
          background-position: 10px 10px;
          background-repeat: no-repeat;
          width: 100%;
          font-size: 13px;
          padding: 1px solid #e1e6eb;
          margin-bottom: 12px;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        @if(session()->has('success'))
            <div class="alert alert-success" style="position: none; margin-top: 4rem;">
                {{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header text-center">
                <h3>Monthly Salary Report</h3>
            </div>
            <div class="card-body">

                <form action="{{route('admin.salaryreportgenerate')}}" method="GET">
                    @csrf
                    @method('GET')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="month">Select a year and month to filter:</label>
                                @php
                                    $today = date('Y-m');
                                @endphp
                                <input type="month" name="monthyear" value="{{$today}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mt-4 text-left">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </form>

                <hr>

                @if (count($salaryreport) == 0)
                    <div class="col-md-12 mt-5 text-center">
                        <p style="font-size: 20px;">Sorry, We could't find the data.</p>
                    </div>
                @else

                <h3 class="text-center mt-5">Monthly Salary Report for the month {{$datetoselect}}</h3>

                <div class="row mt-3">
                    <div class="div col-6"></div>
                    <div class="div col-6">
                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for staff names.." title="Type in a name">
                    </div>
                </div>

                    <div class="table-responsive text-center mt-4">
                        <table class="table" id="myTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Position</th>
                                    <th>Allocated Salary</th>
                                    <th>Paid Amount</th>
                                    <th>Paid on</th>
                                    <th>Salary Type</th>
                                    <th>For month</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salaryreport as $report)
                                    <tr>
                                        <td>{{$report->staff->name}}</td>
                                        <td>
                                            @php
                                                $position = DB::table('positions')->where('id', $report->staff->position_id)->first();
                                            @endphp
                                            {{$position->name}}
                                        </td>
                                        <td>Rs. {{$report->staff->allocated_salary}}</td>
                                        <td>Rs. {{$report->amount}}</td>
                                        <td>{{date('F j, Y', strtotime($report->payment_date))}}</td>
                                        <td>
                                            @php
                                                if ($report->salary_type == 'advance') {
                                                    $salary_type = 'Advance';
                                                }elseif($report->salary_type == 'regular')
                                                {
                                                    $salary_type = 'Regular';
                                                }
                                            @endphp
                                            {{$salary_type}}
                                        </td>
                                        <td>
                                            {{$report->monthyear}}
                                        </td>
                                        <td><a href="{{route('pdf.generate', $report->id)}}" class="btn btn-success">Download (in PDF)</a></td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    function myFunction() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
    </script>
@endpush

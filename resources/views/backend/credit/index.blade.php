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
      padding: 12px 20px 12px 40px;
      border: 1px solid #e1e6eb;
      margin-bottom: 12px;
    }
</style>
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Credit Information</h1>
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>Select a customer for credit Information</h2>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('customerCredit') }}" method="GET">
                                    @csrf
                                    @method("GET")
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label for="">Select a customer:</label>
                                            <select name="customer_name" class="form-control customer">
                                                <option value="">--Select a customer--</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger">
                                                {{ $errors->first('customer_name') }}
                                            </p>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header text-center">
                        <h2>Credits</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="div col-8"></div>
                            <div class="div col-4">
                                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for client names.." title="Type in a name">
                            </div>

                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Client</th>
                                            <th class="text-nowrap">Days Limit</th>
                                            <th class="text-nowrap">Bills number limit</th>
                                            <th class="text-nowrap">Total Amount Limit</th>
                                            <th class="text-nowrap">Bill Due Date</th>
                                            <th class="text-nowrap">Allocated Bills</th>
                                            <th class="text-nowrap">Total Credited Amount</th>
                                            {{-- <th class="text-nowrap">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($clients as $client)
                                            @if(count($client->credits) > 0)
                                            <tr>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->credits[0]->allocated_days }} days</td>
                                                <td>{{ $client->credits[0]->allocated_bills }} Bills</td>
                                                <td>Rs. {{ $client->credits[0]->allocated_amount }}</td>
                                                <td>
                                                    {{ $client->credits[0]->bill_eng_date == null ? 'No credit yet.' : $client->credits[0]->bill_eng_date }}
                                                </td>
                                                <td>{{ $client->credits[0]->credited_bills == 0 ? 0 : $client->credits->count('credited_bills')}}</td>
                                                <td>Rs. {{ $client->credits->sum('credited_amount') }}</td>
                                                {{-- <td>
                                                    @php
                                                        $editurl = route('credit.update', $credit->id);
                                                        $deleteurl = route('credit.destroy', $credit->id);
                                                        $csrf_token = csrf_token();
                                                        $btn = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editcredit$credit->id' data-toggle='tooltip' data-placement='top' title='Edit'><i class='fa fa-edit'></i></button>
                                                                    <!-- Modal -->
                                                                        <div class='modal fade text-left' id='editcredit$credit->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                            <div class='modal-dialog' role='document'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Edit Credit</h5>
                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                    </button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <form action='$editurl' method='POST' style='display:inline-block;'>
                                                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                                                            <input type='hidden' name='_method' value='PUT' />
                                                                                            <div class='form-group'>
                                                                                                <div class='row'>
                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_days'>Allocated Days<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_days' name='allocated_days'
                                                                                                            class='form-control' value='$credit->allocated_days' />
                                                                                                    </div>

                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_bills'>Number Of Bills Limit<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_bills' name='allocated_bills'
                                                                                                            class='form-control'
                                                                                                            value='$credit->allocated_bills' />
                                                                                                    </div>

                                                                                                    <div class='col-md-6 mt-2'>
                                                                                                        <label for='allocated_amount'>Allocated Total Amount (In Rs. )<i
                                                                                                                class='text-danger'>*</i>:</label>
                                                                                                    </div>
                                                                                                    <div class='col-md-6'>
                                                                                                        <input type='number' id='allocated_amount' name='allocated_amount'
                                                                                                            class='form-control'
                                                                                                            value='$credit->allocated_amount' />
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button type='submit' class='btn btn-success btn-sm' title='Update'>Update</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ";
                                                        echo $btn;
                                                    @endphp
                                                </td> --}}
                                            </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="7">No any customer yet.</td>
                                            </tr>
                                        @endforelse
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
    <script>
        $(document).ready(function() {
            $(".customer").select2();
        });
    </script>
    <script>
        function myFunction()
        {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++)
            {
                td = tr[i].getElementsByTagName("td")[0];
                if (td)
                {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1)
                    {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endpush

@extends('backend.layouts.app')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content mt">
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
                    <div class="card-header">
                        <h2>Credit for {{ $selected_client->name }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <b>Allocated Days:</b>
                            </div>

                            <div class="col-md-9">
                                <p>{{ $single_credit_for_allocation->allocated_days }} days</p>
                            </div>
                            <div class="col-md-3">
                                <b>Allocated Number of Bills:</b>
                            </div>

                            <div class="col-md-9">
                                <p>{{ $single_credit_for_allocation->allocated_bills }} bills</p>
                            </div>
                            <div class="col-md-3">
                                <b>Allocated Total Amount:</b>
                            </div>

                            <div class="col-md-9">
                                <p>Rs. {{ $single_credit_for_allocation->allocated_amount }}</p>
                            </div>

                            <div class="col-md-12 mb-3">
                                <button type="button" class="global-btn" data-toggle="modal"
                                    data-target="#editcredit" data-toggle="tooltip" data-placement="top" title="Edit">Edit
                                    Allocation</button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade text-left" id="editcredit" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Credit</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="{{ route('credit.update', $single_credit_for_allocation->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method("PUT")
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-7 mt-2">
                                                            <label for="allocated_days">Allocated Days<i
                                                                    class="text-danger">*</i>:</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="number" id="allocated_days" name="allocated_days"
                                                                class="form-control"
                                                                value="{{ $single_credit_for_allocation->allocated_days }}" />
                                                        </div>

                                                        <div class="col-md-7 mt-2">
                                                            <label for="allocated_bills">Number Of Bills Limit<i
                                                                    class="text-danger">*</i>:</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="number" id="allocated_bills" name="allocated_bills"
                                                                class="form-control"
                                                                value="{{ $single_credit_for_allocation->allocated_bills }}" />
                                                        </div>

                                                        <div class="col-md-7 mt-2">
                                                            <label for="allocated_amount">Allocated Total Amount (In Rs. )<i
                                                                    class="text-danger">*</i>:</label>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="number" id="allocated_amount"
                                                                name="allocated_amount" class="form-control"
                                                                value="{{ $single_credit_for_allocation->allocated_amount }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm"
                                                    title="Update">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 table-responsive mt-2">

                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Invoice Id</th>
                                            <th class="text-nowrap">Bill Date</th>
                                            <th class="text-nowrap">Bill Due Date</th>
                                            <th class="text-nowrap">Bills Number</th>
                                            <th class="text-nowrap">Credit Amount</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @forelse ($credits as $credit)
                                            <tr>
                                                <td>
                                                    @if ($credit->invoice_id == null) No credit yet. @else <a href="{{ route('showSalesInvoice', $credit->invoice_id) }}" target="_blank">{{ $credit->invoice_id }}</a> @endif
                                                </td>
                                                <td>
                                                    {{ $credit->bill_nep_date == null ? 'No credit yet.' : $credit->bill_nep_date }}
                                                </td>
                                                <td>
                                                    {{ $credit->bill_expire_nep_date == null ? 'No credit yet.' : $credit->bill_expire_nep_date }}
                                                </td>
                                                <td>{{ $credit->credited_bills == 0 ? 'No credit' : 'Bill count: ' . $credit->credited_bills }}
                                                </td>
                                                <td>{{ $credit->credited_amount == 0 ? 'No credit' : 'Rs. ' . $credit->credited_amount }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    No any records.
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="4"><b>Total Credit Amount:</b></td>
                                            <td>Rs. {{ $credits->sum('credited_amount') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $credits->firstItem() }}</strong> to
                                                <strong>{{ $credits->lastItem() }} </strong> of <strong>
                                                    {{ $credits->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $credits->links() }}</span>
                                        </div>
                                    </div>
                                </div> --}}
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
@endpush

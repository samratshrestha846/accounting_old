@extends('backend.layouts.app')
@push('styles')
<style>
    /* #myTable{
        overflow-x: scroll;
    } */
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    @if(isset($billing_type_id) && $billing_type_id == 2)
                    <div class="btn-bulk">
                        <h1 class="m-0">Service Purchase Bills </h1>
                        <a href="{{ route('service.purchasecreate') }}" class="global-btn">Create Purchase Bills</a>
                        <a href="{{ route('service_sales.index',['billing_type_id'=>5]) }}" class="global-btn">Debit Notes Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Unapproved Debit Note Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Unapproved Purchase Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Cancelled Debit Note Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Cancelled Purchase Bills</a>
                    </div>

                    @elseif(isset($billing_type_id) && $billing_type_id == 5)
                    <div class="btn-bulk">
                        <h1 class="m-0">Service Debit Note Bills </h1>
                        <a href="{{ route('service.purchasecreate') }}" class="global-btn">Create Purchase Bills</a>
                        <a href="{{ route('service_sales.index',['billing_type_id'=>5]) }}" class="global-btn">Debit Notes Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Unapproved Debit Note Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Unapproved Purchase Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>5]) }}" class="global-btn">Cancelled Debit Note Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>2]) }}" class="global-btn">Cancelled Purchase Bills</a>
                    </div>

                    @elseif(isset($billing_type_id) && $billing_type_id == 6)
                    <div class="btn-bulk">
                        <h1 class="m-0">Service Credit Note Bills </h1>
                        <a href="{{ route('service_sales.create') }}" class="global-btn">Create Sales Bills</a>
                        <a href="{{ route('service_sales.index',['billing_type_id'=>6]) }}" class="global-btn">Credit Notes Bills</a>

                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Unapproved Credit Note Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Unapproved Sales Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Cancelled Credit Note Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Cancelled Sales Bills</a>
                    </div>
                    @else
                    <h1 class="m-0">Service Sales Bills </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service_sales.create') }}" class="global-btn">Create Sales Bills</a>
                        <a href="{{ route('service_sales.index',['billing_type_id'=>6]) }}" class="global-btn">Credit Notes Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Unapproved Credit Note Bills</a>
                        <a href="{{ route('unapprovedServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Unapproved Sales Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>6]) }}" class="global-btn">Cancelled Credit Note Bills</a>
                        <a href="{{ route('cancelledServiceBills',['billing_type_id'=>1]) }}" class="global-btn">Cancelled Sales Bills</a>
                    </div>
                    @endif
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
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
                        <form action="{{ route('generateSalesBillingReport') }}" method="GET">
                            @csrf
                            @method("GET")
                            <div class="row">
                                <input type="hidden" name="billtype" value="{{$billing_type_id == null ? 1 : $billing_type_id}}">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fiscal Year</label>
                                        <select name="fiscal_year" class="form-control fiscal">
                                            @foreach ($fiscal_years as $fiscal_year)
                                                <option value="{{ $fiscal_year->fiscal_year }}"
                                                    {{ $fiscal_year->id == $current_fiscal_year->id ? 'selected' : '' }}>
                                                    {{ $fiscal_year->fiscal_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Starting date</label>
                                        <input type="text" name="starting_date" class="form-control startdate"
                                            id="starting_date" value="{{ $nepali_today }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Ending date</label>
                                        <input type="text" name="ending_date" class="form-control enddate" id="ending_date"
                                            value="">
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

                <div class="card mt">
                    <div class="card-body mid-body">
                        @if($starting_date != null && $ending_date != null)
                            <h4>From {{ $starting_date }} to {{ $ending_date }}</h4>
                        @endif
                        <form action="" class="form-center">
                            @csrf
                            <div class="form-group" style="margin-bottom:0;">
                                {{-- <input type="checkbox" name="select-all" class="select_all" value="all">
                                Select All --}}
                            </div>
                            <div class="btn-bulk">
                                <button type="submit" name="submit" class="global-btn clicked">
                                    Unapprove Selected</button>
                                    <a href="javascript:void(0)" class="global-btn csvbilled">Export (CSV)</a>

                                @if ($starting_date != null && $ending_date != null)
                                    <form style="display: inline;" id="exportcsv"
                                        action="{{ route('exportFilterServiceSalesBills', ['id' => $current_fiscal_year->id, 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="selectedcsvid" class="selectedcsvid">
                                    </form>
                                @else
                                    <form style="display: inline;" id="exportcsv"
                                        action="{{ route('exportServiceSalesBills', $current_fiscal_year->id) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="selectedcsvid" class="selectedcsvid">
                                    </form>
                                @endif


                                <a href="javascript:void(0)" class="global-btn pdfbilled" style="margin-left:5px;">Export
                                    (PDF)</a>
                                @if ($starting_date != null && $ending_date != null)
                                    <form style="display: inline;" id="exportpdf"
                                        action="{{ route('pdf.generateServiceSalesBillingReport', ['id' => $current_fiscal_year->id, 'starting_date' => $starting_date, 'ending_date' => $ending_date]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="selectedid" class="selectedid">
                                    </form>
                                @else
                                    <form style="display: inline;" id="exportpdf"
                                        action="{{ route('pdf.ServiceSalesBillings') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="selectedid" class="selectedid">
                                    </form>
                                @endif

                                &nbsp;<a href="#" class="mr-2 btn btn-secondary" style="background-color:#dc3b05;" disable>Total:Rs. {{number_format($total_sum,2)}}</a>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="filter">
                            <form action="{{ route('generateSalesBillingReport') }}" method="GET">
                                @csrf
                                <div class="row">
                                    @if ($starting_date != null && $ending_date != null)
                                        <input type="hidden" name="starting_date" value="{{ $starting_date }}">
                                        <input type="hidden" name="ending_date" value="{{ $ending_date }}">
                                    @endif
                                    <div class="col-md-8 pr-0">
                                        <select name="number_to_filter" class="form-control">
                                            <option value="5" {{ $number == 5 ? 'selected' : '' }}>5</option>
                                            <option value="10" {{ $number == 10 ? 'selected' : '' }}>10
                                            </option>
                                            <option value="20" {{ $number == 20 ? 'selected' : '' }}>20
                                            </option>
                                            <option value="50" {{ $number == 50 ? 'selected' : '' }}>50
                                            </option>
                                            <option value="100" {{ $number == 100 ? 'selected' : '' }}>100
                                            </option>
                                            <option value="250" {{ $number == 250 ? 'selected' : '' }}>250
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="global-btn" name="approved">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <form class="form-inline" action="{{ route('searchResults') }}" method="POST">
                                @csrf
                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn btn-sm"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive mt">
                            <table class="table table-bordered data-table text-center global-table" id="myTable">
                                {{-- <thead class="topsearch">
                                    <tr>
                                        <th></th>
                                        <th>Billing Type</th>

                                        @if ($billing_type_id == 1 || $billing_type_id == 6 || $billing_type_id == 7)
                                            <th>Customer</th>
                                        @endif
                                        @if ($billing_type_id == 2 || $billing_type_id == 5)
                                            <th>Supplier</th>
                                        @endif
                                        <th>Reference No</th>
                                        <th>Transaction No</th>

                                        @if ($billing_type_id == 2 || $billing_type_id == 5)
                                            <th>Party Bill No</th>
                                        @else
                                            <th>VAT Bill No</th>
                                        @endif
                                        <th>Bill Date</th>
                                        <th>Grand Total</th>
                                        <th></th>
                                    </tr>
                                </thead> --}}
                                <thead class="thead-light">
                                    <tr>
                                        <th>Select</th>
                                        <th>Related JV</th>
                                        <th>Billing Type</th>

                                        @if ($billing_type_id == 1 || $billing_type_id == 6 || $billing_type_id == 7)
                                            <th>Customer</th>
                                        @endif
                                        @if ($billing_type_id == 2 || $billing_type_id == 5)
                                            <th>Supplier</th>
                                        @endif
                                        <th>Reference No</th>
                                        <th>Transaction No</th>

                                        @if ($billing_type_id == 2 || $billing_type_id == 5)
                                            <th>Party Bill No</th>
                                        @else
                                            <th>VAT Bill No</th>
                                        @endif
                                        <th>Bill Date</th>
                                        <th>Grand Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @forelse ($billings as $billing)
                                        <tr>
                                            <td>
                                                <input name='select[]' type='checkbox' class='select' value='{{ $billing->id }}'>
                                            </td>
                                            <td>
                                                {{ $billing->client->name ?? '' }}
                                            </td>
                                            <td>{{ $billing->reference_no }}</td>
                                            <td>{{ $billing->transaction_no }}</td>
                                            <td>{{ $billing->ledger_no }}</td>
                                            <td>{{ $billing->nep_date }}</td>
                                            <td>Rs.{{ $billing->grandtotal }}</td>
                                            <td style="width: 120px;">
                                                <div class="btn-bulk justify-content-center">

                                                    <a href="{{ route('serviceSalesBillPrint', $billing->id) }}" class="btn btn-secondary icon-btn btnprn" title="Print" ><i class="fa fa-print"></i> </a>
                                                    <a href="{{route('serviceSaleCreditNote',$billing->id)}}" class='edit btn btn-secondary icon-btn btn-sm' data-toggle='tooltip' data-placement='top' title='Create Debit Note'><i class='fas fa-credit-card'></i></a>
                                                    <a href="{{ route('service_sales.show', $billing->id) }}" class="btn btn-primary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
                                                    {{-- <a href="{{ route('service_sales.edit', $billing->id) }}" class="btn btn-secondary icon-btn btn-sm"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a> --}}
                                                    {{-- <button type="button" class="btn btn-secondary icon-btn btn-sm" data-toggle="modal" data-target="#cancellation" data-toggle="tooltip" data-placement="top" title="Cancel"><i class="fa fa-ban"></i></button>
                                                    <a href="{{ route('unapproveSingleServiceBill', $billing->id) }}" class="btn icon-btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Unapprove"><i class="fas fa-thumbs-down"></i></a>
                                                    <!-- Modal -->
                                                        <div class="modal fade text-left" id="cancellation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Billing Cancellation</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Please give reason for Cancellation</p>
                                                                    <hr>
                                                                    <form action="{{ route('cancelSingleServiceBill', $billing->id) }}" method="POST">
                                                                        @csrf
                                                                        @method("POST")
                                                                        <div class="form-group">
                                                                            <label for="reason">Reason:</label>
                                                                            <input type="text" name="reason" id="reason" class="form-control" placeholder="Enter Reason for Cancellation" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="description">Description: </label>
                                                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter Detailed Reason" required></textarea>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-danger">Submit</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No any bills yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody> --}}
                            </table>
                            {{-- <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-sm">
                                            Showing <strong>{{ $billings->firstItem() }}</strong> to
                                            <strong>{{ $billings->lastItem() }} </strong> of <strong>
                                                {{ $billings->total() }}</strong>
                                            entries
                                            <span> | Takes
                                                <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="pagination-sm m-0 float-right">{{ $billings->links() }}</span>
                                    </div>
                                </div>
                            </div> --}}
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
<script src="{{ asset('backend/dist/js/mousetrap/service.js') }}"></script>
<script src="{{ asset('backend/dist/js/mousetrap/servicepurchase.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.btnprn').printPage();
    });
</script>

<script>
    $(function() {
        // var all = $('input.select_all');
        // all.change(function() {
        //     var select = $('input.select');
        //     if (this.checked) {
        //         select.prop('checked', true);
        //     } else {
        //         select.prop('checked', false);
        //     }

        // })
        $(document).on('change','input.select_all',function(){
                var select = $('input.select');
                if (this.checked) {
                    select.prop('checked', true);
                } else {
                    select.prop('checked', false);
                }
            });
        var selectval = [];
        $('.clicked').click(function(event) {
            event.preventDefault();
            $('.select:checked').each(function(i) {
                selectval[i] = $(this).val();
            })

            $.ajax({
                url: "{{ route('serviceSalesBillsUnapprove') }}",
                type: 'POST',
                dataType: 'json',
                encode: true,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: {
                    id: selectval,
                },
                beforeSend: function(xmlhttp) {
                    xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xmlhttp.setRequestHeader('Content-type',
                        'application/x-www-form-urlencoded; charset=UTF-8');
                },
                success: function(response) {
                    alert("You will now be redirected.");
                    window.location = "{{ route('unapprovedServiceBills') }}";
                },
            });
        })

        $('.pdfbilled').click(function(event) {
            var billedval = [];
            event.preventDefault();
            $('.select:checked').each(function(i) {
                billedval[i] = $(this).val();
            })
            $('input.selectedid').val(billedval);
            $("#exportpdf").submit();
        })
        $('.csvbilled').click(function(event) {
            var billedval = [];
            event.preventDefault();
            $('.select:checked').each(function(i) {
                billedval[i] = $(this).val();
            })
            $('input.selectedcsvid').val(billedval);
            $("#exportcsv").submit();
        })
    })
</script>
<script>
    window.onload = function() {
        var starting_date = document.getElementById("starting_date");
        var ending_date = document.getElementById("ending_date");
        var ending_year = "{{ $actual_year[1] }}";

        var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
        starting_date.nepaliDatePicker();

        var ending_full_date = ending_year + '-03-' + days_count;
        ending_date.value = ending_full_date;

        ending_date.nepaliDatePicker();
    };

    $(function() {
        $('.fiscal').change(function() {
            var fiscal_year = $(this).children("option:selected").val();
            var array_date = fiscal_year.split("/");

            var starting_date = document.getElementById("starting_date");
            var starting_full_date = array_date[0] + '-04-01';
            starting_date.value = starting_full_date;
            starting_date.nepaliDatePicker();

            var ending_date = document.getElementById("ending_date");
            var ending_year = array_date[1];
            var days_count = NepaliFunctions.GetDaysInBsMonth(ending_year, 3);
            var ending_full_date = ending_year + '-03-' + days_count;
            ending_date.value = ending_full_date;
            ending_date.nepaliDatePicker();
        })
    })

    $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');

        $(function () {

            var billing_type_id = @php echo json_encode($billing_type_id); @endphp;

            var url = "{{route('service_sales.index',':id')}}";
            var url = url.replace(':id',billing_type_id);
            if((billing_type_id == 1) || (billing_type_id == 6) || (billing_type_id == 7)){
                var clientorsupplier = 'client.name';
            }else{
                var clientorsupplier = 'suppliers.company_name';
            }

            var table = $('#myTable').DataTable({
                "order": [[ 2, "desc" ]],
                columnDefs: [
                    { width: 500, targets: 0 }
                ],
                fixedColumns: true,
                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            if(title != 'Action'){
                                $(cell).html('<input class="searchcolumn" type="text" placeholder="' + title + '" />');
                            }



                            // On every keypress in this input
                            $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });

                        $('.searchcolumn').each(function(k,v){
                            if($(this).attr('placeholder') == "Select"){
                                $(this).replaceWith('<input type="checkbox" name="select-all" class="select_all" value="all">Select All');
                            }


                        });
                },
                searchPanes: {
                    viewTotal: true
                },
                dom: 'Plfrtip',
                columnDefs: [
                    { width: 100, targets: 0 }
                ],
                fixedColumns: true,
                processing: false,
                serverSide: true,
                pageLength:25,
                ajax:{
                    url: url,
                    method:'GET',
                    data:{
                        billing_type_id:billing_type_id,

                    },
                },
                columns: [
                    {data: 'select'},
                    {data:'related_jv_no'},
                    {data: 'billing_types.billing_types'},
                    {data: 'billing_type_id',name:clientorsupplier},
                    {data: 'reference_no'},
                    {data: 'transaction_no'},
                    {data: 'ledger_no'},
                    {data: 'nep_date'},
                    {data: 'grandtotal'},
                    {data: 'action'},
                ]
            });

        });
</script>
@endpush

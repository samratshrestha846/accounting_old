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
      border-radius: 0px;
      margin-bottom: 12px;
    }

    .searchcolumn{
        width: 100px;
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
                    <h1>Product Stocks Generate </h1>
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
                                <h2>Generate Product Report</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('product.stockgenerate') }}" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-gorup">
                                                <label for="godown">Select Godown</label>
                                                <select name="godown_id" id="godown" class="form-control godown_info"
                                                    required>
                                                    <option value="">--Select Option--</option>
                                                    @foreach ($godowns as $godown)
                                                        <option value="{{ $godown->id }}">{{ $godown->godown_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Product Title:</label>
                                                <select name="product_id" class="form-control item" id="item" required>
                                                    <option value="">--Select Option--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Starting date</label>
                                                <input type="date" name="starting_date" class="form-control startdate"
                                                    id="starting_date" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">Ending date</label>
                                                <input type="date" name="ending_date" class="form-control enddate"
                                                    id="ending_date" value="0" required>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="">
                                    <div class="row justify-content-end pb-2">
                                        <div class="col-2">
                                            <select name="selectgodown" id="selectgodown" class="form-control">
                                                @foreach ($godowns as $godown)
                                                    <option value="{{$godown->id}}" {{$defaultgodown->id == $godown->id ? 'selected' : ''}}>{{$godown->godown_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{$defaultgodown->godown_name}}</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="div col-9"></div>
                                                <div class="div col-3">
                                                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for product names.." title="Type in a name">
                                                </div>
                                            </div>
                                            <table class="table table-bordered text-center" id="myTable">
                                                <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Product Name</th>
                                                    <th scope="col">Product SKU</th>
                                                    <th scope="col">Opening Stock</th>
                                                    <th scope="col">Closing Stock</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- @forelse ($defaultgodown->godownproduct as $item)
                                                        <tr>
                                                            <td>{{$item->product->product_name}}</td>
                                                            <td>{{$item->product->product_code}}</td>
                                                            <td>{{$item->opening_stock}}</td>
                                                            <td>{{$item->stock}}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4">No any products</td>
                                                        </tr>
                                                    @endforelse --}}
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
        </div>

    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
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
    <script>
        $(document).ready(function() {
            $(".account_head").select2();
        });
    </script>

    <script>
        $(function() {
            $(document).ready(function() {
                $(".item").select2();
            });

            $(document).ready(function() {
                $(".godown_info").select2();
            });
        });
    </script>
    <script>
        $('#godown').change(function() {
            var selgodown = $('#godown').find(":selected").val();
            var warehouses = @php echo  json_encode($godowns); @endphp;
            var warehousecount = warehouses.length;
            // console.log(warehouses);
            function wareproducts() {

                for (let i = 0; i < warehousecount; i++) {
                    if (warehouses[i].id == selgodown) {
                        var godownpros = warehouses[i].godownproduct;
                        var gdpcount = godownpros.length;
                        // console.log(godownpros);
                        var prodoptions = '';
                        for (let s = 0; s < gdpcount; s++) {
                            var stock = godownpros[s].stock;
                            var proname = godownpros[s].product.product_name;
                            var rate = godownpros[s].product.product_price;
                            var primary_unit = godownpros[s].product.primary_unit;
                            var procode = godownpros[s].product.product_code;
                            var proid = godownpros[s].product_id;
                            prodoptions += `<option value="${proid}"
                                            data-rate="${rate}"
                                            data-stock="${stock}"
                                            data-priunit = "${primary_unit}">
                                            ${proname}(${procode})
                                        </option>`;
                        }

                    }
                }
                return prodoptions;
            }
            $('.item').html('<option value="">--Select Option--</option>' + wareproducts());
            return 1;
        })
    </script>
    <script>
        $('.item').change(function() {
            var products = @php echo json_encode($products); @endphp;
            var selectedid = parseInt($(this).find(':selected').val());

            for(let x = 0; x < products.length; x++){
                if(products[x].id == selectedid){
                    var startdate = products[x].created_at;
                }
            }
            // var startdate = products[selectedid].created_at;


            var start_date = (new Date(startdate)).toLocaleDateString();
            var splited = start_date.split('/');
            var month = splited[0];
            if (month < 10) {
                month = "0" + month;
            }
            var newstartdate = splited[2] + '-' + month + '-' + splited[1];
            console.log(newstartdate);
            $('#starting_date').val(newstartdate);
            const d = new Date();
            var thisyear = d.getFullYear();
            var thismonth = d.getMonth() + 1;
            if (thismonth < 10) {
                thismonth = "0" + thismonth;
            }
            var thisday = d.getDate();
            var today = thisyear + '-' + thismonth + '-' + thisday;
            // console.log(today);
            $('#starting_date').attr('min', newstartdate);
            // $('#starting_date').attr('max', today);
            $('#ending_date').attr('min', newstartdate);
            $('#ending_date').attr('max', today);
        });
    </script>
    <script>
        $(function(){
            $('#selectgodown').change(function(){
                var godown_id = $(this).find(':selected').val();
                var url = "{{ route('stockreportcreate.godown', ':id') }}";
                url = url.replace(':id', godown_id);
                window.location = url;
            })
        })
    </script>
    <script>
          $('#myTable thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#myTable thead');
        $(function () {

            var url = "{{route('stockreportcreate')}}";


            var table = $('#myTable').DataTable({

                orderCellsTop: true,
                columnDefs: [
                    { width: 100, targets: 0 }
                ],
                fixedColumns: true,
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
                },
                searchPanes: {
                    viewTotal: true
                },
                dom: 'Plfrtip',


                processing: true,
                serverSide: true,
                pageLength:25,
                ajax:{
                    'url':url,
                    'data':{'gd_id':@php echo json_encode($defaultgodown->id); @endphp},
                },
                columns: [
                    {data: 'product.product_name'},
                    {data: 'product.product_code'},
                    {data: 'opening_stock',name:'godown_products.opening_stock'},
                    {data: 'stock'},

                ]
            });

        });

    </script>
@endpush

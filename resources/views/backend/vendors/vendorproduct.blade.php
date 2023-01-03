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
                    <h1>{{ $vendor->company_name }}'s Products </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('vendors.create') }}" class="global-btn">Add New Suppliers</a>
                        <a href="{{ route('vendors.index') }}" class="global-btn">View All Suppliers</a>
                        <a href="{{ route('vendors.show',$vendor->id) }}" class="global-btn">Back</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2>{{ $vendor->company_name }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="div col-8"></div>
                                    <div class="div col-4">
                                        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for product names.." title="Type in a name">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="myTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-nowrap">Product</th>
                                                <th class="text-nowrap">Bill Type</th>
                                                <th class="text-nowrap">Ref.No</th>
                                                <th class="text-nowrap">Quantity</th>
                                                <th class="text-nowrap">Date(Eng)</th>
                                                <th class="text-nowrap">Date(Nep)</th>
                                                <th class="text-nowrap">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vendorproducts as $product)
                                            <tr>
                                                <td>{{$product->product_name}}</td>
                                                <td>{{$product->billing_types}}</td>
                                                <td><a href="{{route('billings.show', $product->billing_id)}}">{{$product->reference_no}}</a></td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{$product->eng_date}}</td>
                                                <td>{{$product->nep_date}}</td>
                                                <td><span class="badge badge-{{$product->status == 1 ? 'success' : 'danger'}}">{{$product->status == 1 ? 'Approved' : 'Waiting for Approval'}}</span></td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">No products yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <p class="text-sm">
                                                Showing <strong>{{ $vendorproducts->firstItem() }}</strong> to
                                                <strong>{{ $vendorproducts->lastItem() }} </strong> of <strong>
                                                    {{ $vendorproducts->total() }}</strong>
                                                entries
                                                <span> | Takes
                                                    <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-7">
                                            <span
                                                class="pagination-sm m-0 float-right">{{ $vendorproducts->links() }}</span>
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

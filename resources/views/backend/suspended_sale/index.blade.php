@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="sec-header">
        <div class="container-fluid">
            <div class="sec-header-wrap">
                <h1>Suspended Sales </h1>
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="m-0 float-right">
                                <div id="dataTableForm" class="form-inline">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="search" class="sr-only">Search</label>
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2" onclick="searchFilterForm()"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered yajra-datatable text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Date</th>
                                            <th class="text-nowrap">Oulet</th>
                                            <th class="text-nowrap">Customer</th>
                                            <th class="text-nowrap">Total Items</th>
                                            <th class="text-nowrap">Product Tax</th>
                                            <th class="text-nowrap">Discount</th>
                                            <th class="text-nowrap">Total Cost</th>
                                            <th class="text-nowrap">Suspended By</th>
                                            <th class="text-nowrap">Canceled</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($suspendedSales as $suspendedSale)
                                            <tr>
                                                <td class="text-nowrap">
                                                    {{\Carbon\Carbon::parse($suspendedSale->created_at)->format('Y-m-d h:i A')}}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{$suspendedSale->outlet->name}}
                                                </td>
                                                <td class="text-nowrap">
                                                    {{$suspendedSale->customer->name}}
                                                </td>
                                                <td class="text-nowrap">{{ $suspendedSale->suspended_items_count }}</td>
                                                <td class="text-nowrap">{{ $suspendedSale->total_tax }}</td>
                                                <td class="text-nowrap">{{ $suspendedSale->total_discount }}</td>
                                                <td class="text-nowrap">{{ $suspendedSale->total_cost }}</td>
                                                <td class="text-nowrap">{{ $suspendedSale->suspendedUser ? $suspendedSale->suspendedUser->name : '—'}}</td>
                                                <td>
                                                    @if($suspendedSale->is_canceled)
                                                    <span class="badge">Yes</span>
                                                    @else
                                                    <span class="badge">No</span>
                                                    @endif
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="btn-bulk">
                                                        <a
                                                            href='{{route("pos.suspendedsale.index", $suspendedSale->id)}}'
                                                            class='view btn btn-primary icon-btn'
                                                            title='Add this sale to pos screen'>
                                                            <i class='fa fa-plus-circle'></i>
                                                        </a>
                                                        <form id="suspendedsale-{{$suspendedSale->id}}" action="{{route('suspendedsale.delete', $suspendedSale->id)}}" method='POST' style='display:inline-block;'>
                                                            @csrf
                                                            @method('DELETE')
                                                            <a
                                                                type='submit' title='Delete'
                                                                class="delete icon-btn btn btn-secondary"
                                                                onclick="deleteAction('{{$suspendedSale->id}}')"
                                                                title="Delete"
                                                            >
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="10">No suspended sales yet.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="position">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $suspendedSales->firstItem() }}</strong> to
                                                <strong>{{ $suspendedSales->lastItem() }} </strong> of <strong>
                                                    {{ $suspendedSales->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $suspendedSales->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </div>

@endsection
@push('scripts')
<script>
    function deleteAction(id) {
        if(confirm("Are you sure you want to delete this item?")){
            $('#suspendedsale-'+id).trigger('submit');
        }
    }
</script>
@endpush

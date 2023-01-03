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
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Biller Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('biller.create') }}"
                            class="global-btn">Add New Biller</a>
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
                    <div class="card-body">
                        <div class="row">
                                <div class="div col-md-8"></div>
                                <div class="div col-md-4">
                                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Outlet or Biller names.." title="Type in a name">
                                </div>

                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center global-table" id="myTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Outlet Name</th>
                                            <th class="text-nowrap">Biller Name</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($billers as $biller)
                                            <tr>
                                                <td>{{ $biller->outlet->name }}</td>
                                                <td>{{ $biller->user->name }}</td>
                                                <td>
                                                    <div class="btn-bulk">
                                                    @php
                                                        $editurl = route( 'biller.edit', $biller->id);
                                                        $deleteurl = route('biller.destroy', $biller->id);
                                                        $csrf_token = csrf_token();
                                                        $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                    <button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#deletionservice$biller->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                    <!-- Modal -->
                                                                        <div class='modal fade text-left' id='deletionservice$biller->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                                                            <button type='submit' class='btn btn-danger icon-btn' title='Delete'>Confirm Delete</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        ";

                                                        echo $btn;
                                                    @endphp
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No any billers.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $billers->firstItem() }}</strong> to
                                                <strong>{{ $billers->lastItem() }} </strong> of <strong>
                                                    {{ $billers->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $billers->links() }}</span>
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
                td1 = tr[i].getElementsByTagName("td")[1];

                if (td) {
                    txtValue = td.textContent+td1.textContent || td.innerText+td1.innerText;
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

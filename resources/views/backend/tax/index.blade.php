@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Tax </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('tax.create') }}" class="global-btn">Create Tax</a>
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
                            <div class="d-flex justify-content-end">
                                <form class="form-inline" action="{{ route('tax.search') }}" method="POST">
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
                        <div class="table-responsive mt noscroll" id="refresh">
                            <table class="table table-bordered data-table text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Percent</th>
                                        <th>Mark As Default</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($taxes as $tax)
                                        <tr>
                                            <td>{{ $tax->title }}</td>
                                            <td>{{ $tax->percent }}%</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  value="{{ $tax->id }}"  class="custom-control-input" id="customSwitch{{ $tax->id }}" @if($tax->is_default) checked disabled @else  onclick="checkAsDefault(this)" @endif>
                                                    <label class="custom-control-label" for="customSwitch{{ $tax->id }}"></label>
                                                  </div>
                                            </td>
                                            <td>
                                                <div class="btn-bulk">
                                                    @php
                                                    $editurl = route('tax.edit', $tax->id);
                                                    $deleteurl = route('tax.destroy', $tax->id);
                                                    $csrf_token = csrf_token();
                                                    $btn = "<a href='$editurl' class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                            <form action='$deleteurl' method='POST' style='display:inline-block;padding:0; ' class='btn'>
                                                            <input type='hidden' name='_token' value='$csrf_token'>
                                                            <input type='hidden' name='_method' value='DELETE' />
                                                                <button type='submit' class='btn btn-secondary icon-btn btn-sm' title='Delete'><i class='fa fa-trash'></i></button>
                                                            </form>
                                                        ";
                                                    echo $btn;
                                                @endphp
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No tax info yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $taxes->firstItem() }}</strong> to
                                            <strong>{{ $taxes->lastItem() }} </strong> of <strong>
                                                {{ $taxes->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $taxes->links() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
<script>
function checkAsDefault(that)
{
    var id = $(that).val();
    $.ajax({
          url: "{{ route('tax.mark_as_default') }}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            id:id,
          },
          success:function(response){
            console.log(response);
            location.reload();

          },
         });

}
</script>
@endpush

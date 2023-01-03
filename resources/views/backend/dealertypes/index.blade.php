@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Dealer Types </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('dealertype.create') }}" class="global-btn">Create Dealer Type</a>
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
                        <div class="table-responsive mt" id="refresh">
                            <table class="table table-bordered data-table text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Percent</th>
                                        <th>Mark As Default</th>
                                        <th>Make User</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dealerTypes as $dealertype)
                                        <tr>
                                            <td>{{ $dealertype->title }}</td>
                                            <td>{{ $dealertype->percent }}%</td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox"  value="{{ $dealertype->id }}"  class="custom-control-input" id="customSwitch{{ $dealertype->id }}" @if($dealertype->is_default) checked disabled @else  onclick="checkAsDefault(this)" @endif>
                                                    <label class="custom-control-label" for="customSwitch{{ $dealertype->id }}"></label>
                                                  </div>
                                            </td>
                                            <td><span class="badge badge-{{$dealertype->make_user == 1 ? 'success' : 'danger'}}">{{ $dealertype->make_user == 1 ? 'Yes' : 'No' }}</span></td>
                                            <td style="width:120px;">
                                                <div class="btn-bulk justify-content-center">
                                                    @php
                                                    $editurl = route('dealertype.edit', $dealertype->id);
                                                    $deleteurl = route('dealertype.destroy', $dealertype->id);
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
                                        <tr><td colspan="3">No Dealertype yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $dealerTypes->firstItem() }}</strong> to
                                            <strong>{{ $dealerTypes->lastItem() }} </strong> of <strong>
                                                {{ $dealerTypes->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $dealerTypes->links() }}</span>
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
          url: "{{ route('dealertype.mark_as_default') }}",
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

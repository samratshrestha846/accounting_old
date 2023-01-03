@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Notifications </h1>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="col-sm-12">
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-2 offset-10 py-3"><a href="{{route('productnoti.read', $type)}}" class="btn btn-primary">Mark All as Read</a></div>
                        <div class="col-md-12">
                            @foreach ($pronotis as $pronoti)
                            <a href="{{route('productnotification.show', $pronoti->id)}}" class="dropdown-item" {{$pronoti->status == 0 ? "style=background-color:lightgrey;":""}}>
                                {{$pronoti->product->product_name.'('.$pronoti->product->product_code.')'}} is low in stock on {{$pronoti->godown->godown_name}}
                                <span class="float-right text-muted text-sm">{{$pronoti->created_at->diffforHumans()}}</span>
                            </a>
                            @endforeach
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $pronotis->firstItem() }}</strong> to
                                            <strong>{{ $pronotis->lastItem() }} </strong> of <strong>
                                                {{ $pronotis->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $pronotis->links() }}</span>
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
@endpush

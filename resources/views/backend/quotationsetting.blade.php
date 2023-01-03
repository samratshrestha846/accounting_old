@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Quotation Setting </h1>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('quotationsetting.update', $quotation->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="show_brand">Show Brand: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="show_brand" value="1"
                                                            {{ $quotation->show_brand == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="show_model">Show Model: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="show_model" value="1"
                                                            {{ $quotation->show_model == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="show_picture">Show Picture: </label>
                                                    <span style="margin-right: 5px; font-size: 12px;"> Disable </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="show_picture" value="1"
                                                            {{ $quotation->show_picture == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px;">Enable</span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="letterhead">Select Letterhead(Please select png, jpg, jpeg
                                                        file type):</label>
                                                    <input type="file" name="letterhead" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <img src="{{ Storage::disk('uploads')->url($quotation->letterhead) }}"
                                                    class="img-fluid" alt="">
                                            </div>
                                        </div>

                                        <button name="submit" class="btn btn-primary ml-auto">Submit</button>

                                    </form>
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

@endpush

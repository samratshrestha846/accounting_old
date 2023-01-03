    @extends('backend.layouts.app')
    @push('styles')
        <style>
            div#holder>img {
                border-radius: 20%;
                margin: 0.5rem;
                height: 16rem !important;
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
                        <h1>Upload Product Images</h1>
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
                    <div class="ibox">
                        <div class="row ibox-body">
                            <div class="col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Upload Product Images</h2>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('productPostImage') }}" method="POST">
                                            @csrf
                                            @method("POST")
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a id="main_image" data-input="thumbnail" data-preview="holder"
                                                                class="btn btn-secondary btn-sm" style="margin-top:-3px;">
                                                                <i class="las la-image"></i> Choose
                                                            </a>
                                                        </span> &nbsp;
                                                        <input id="thumbnail" class="form-control" type="text" name="image"
                                                            placeholder="Choose Images..">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div id="holder form-group">
                                                        <label for="" style="display: block;">Preview Image</label>
                                                        <img src="" alt="" height="50px">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="btn-bulk d-flex justify-content-start">
                                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
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
        <script src="{{ asset('vendor/laravel-file-manager/js/stand-alone-button.js') }}"></script>

        <script>
            $('#main_image').filemanager('image');
        </script>
    @endpush

@extends('backend.layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/nestable/jquery-nestable.css') }}">
<style>
    ol {
        list-style-type: none;
    }

    .menu-handle {
        display: block;
        margin-bottom: 5px;
        padding: 6px 4px 6px 12px;
        color: #333;
        font-weight: bold;
        border: 1px solid #ccc;
        background: #fafafa;
        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
        background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
        background: linear-gradient(top, #fafafa 0%, #eee 100%);
        -webkit-border-radius: 3px;
        border-radius: 3px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        cursor: move;
    }

    .menu-handle:hover {
        background: #fff;
    }

    .menu-handle span {
        line-height: 2.2;
    }

    .placeholder {
        margin-bottom: 10px;
        background: #D7F8FD
    }

    .drag_disabled {
        pointer-events: none;
    }

    .modal {
        z-index: 99999999;
    }

    /* .dd-item button {
        display: none;
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
                <h1>Category </h1>
                <div class="btn-bulk">
                    <a href="{{ route('category.create') }}" class="global-btn">Add New Categorsy</a>
                    <a href="#" data-toggle='modal' data-target='#upload_category_importer' data-toggle='tooltip' data-placement='top' class="global-btn" title="Upload Category from CSV">Upload Category</a>

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
                <div class="card-body card-format dd" id="nestable">
                    @if ($categories->count() > 0)
                    <ol class="sortable dd-list" style="padding:0;">
                        @foreach ($categories as $category)
                        @include('backend.category.category_list',['category' => $category])
                        @endforeach
                        {{-- <div class="form-group mt-4">
                            <button type="button" class="btn btn-secondary btn-sm" id="serialize"><i
                                    class="fa fa-save"></i>
                                Update Order
                            </button>
                        </div> --}}
                    </ol>
                    @else
                    <p class="text-center">Category Not Found.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class='modal fade text-left' id='upload_category_importer' tabindex='-1' role='dialog'
    aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document' style="max-width: 800px;">
            <div class='modal-content'>
                <div class='modal-header text-center'>
                    <h2 class='modal-title' id='exampleModalLabel'>Upload CSV for Category</h2>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action="{{ route('categoryImporter') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method("POST")
                        <div class="row">

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="csv_file">CSV file<i class="text-danger">*</i>
                                    </label>
                                    <input type="file" name="csv_file" class="form-control"
                                        required>
                                    <p class="text-danger">
                                        {{ $errors->first('csv_file') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="btn-bulk">
                            <button type="submit" class="btn btn-primary btn-sm"
                                name="modal_button">Save</button>
                            {{-- <a href="{{ route('exportNonImporter') }}"
                                class="btn btn-secondary btn-sm">Download Format</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@push('scripts')
<script src="{{ asset('backend/plugins/nestable/jquery.nestable.js') }}"></script>
<script src="{{ asset('backend/plugins/toastrjs/toastr.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');

            $.ajax({
                method: "POST",
                url: "{{ URL::route('category.set_order')}}",
                data: {
                    '_token': $('input[name=_token]').val(),
                    list_order: list.nestable('serialize'),
                    table: "categories"
                },
                success: function (response) {
                    // console.log("success");
                    // console.log("response " + response);
                    var obj = jQuery.parseJSON(response);
                    if (obj.status == 'success') {
                        toastr.success("Content Sorted Successfully");
                    }
                    if (obj.status == 'error') {
                        toastr.error("Sorry Something Went Wrong!!");
                    }
                    ;

                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error("Something Went Wrong!");
            });
        };

        $('#nestable').nestable({
            group: 1,
            maxDepth: 3,
            expandBtnHTML:false,
            collapseBtnHTML:false,
        }).on('change', updateOutput);
    });

    $('.dd div.action-buttons').on('mousedown', function (event) {
             event.preventDefault(); return false;
             });
        function show_alert() {
            if (!confirm("Do you really want to do this?")) {
                return false;
            }
            this.form.submit();
        }

</script>


@endpush

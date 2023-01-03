@extends('backend.layouts.app')
@push('styles')
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

        .placeholder {
            margin-bottom: 10px;
            background: #D7F8FD
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
                    <h1>Service Category </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('service_category.create') }}" class="global-btn">Add New
                            Category</a>
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
                    <div class="card-body card-format">
                        @if ($categories->count() > 0)
                            <ol class="sortable" style="padding:0;margin:0;">
                                @foreach ($categories as $category)
                                    <li id="menuItem_{{ $category->id }}">
                                        <div class="menu-handle d-flex justify-content-between">
                                            <span>
                                                {{ $category->category_name }}
                                            </span>

                                            <div class="menu-options btn-group global-table">
                                                <a href="{{ route('service_category.edit', $category->id) }}"
                                                    class="btn btn-sm btn-primary icon-btn" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-sm btn-secondary icon-btn ml-1" data-toggle="modal"
                                                    data-target="#deletionservice{{ $category->id }}"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></button>
                                                <!-- Modal -->
                                                <div class="modal fade text-left" id="deletionservice{{ $category->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete
                                                                    Confirmation</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <form
                                                                    action="{{ route('service_category.destroy', $category->id) }}"
                                                                    method="POST" style="display:inline-block;">
                                                                    @csrf
                                                                    @method("POST")
                                                                    <label for="reason">Are you sure you want to
                                                                        delete??</label><br>
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <button type="submit" class="btn btn-danger"
                                                                        title="Delete">Confirm Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- {{ get_nested_menu($item->id) }} --}}
                                        {{-- @include('admin.menu.nested',['data'=>$item->child_menu]) --}}
                                    </li>
                                @endforeach
                                <div class="form-group mt-2 mb-0 text-right">
                                    <button type="button" class="btn btn-secondary btn-sm" id="serialize"><i
                                            class="fa fa-save"></i>
                                        Update Order
                                    </button>
                                </div>
                            </ol>
                        @else
                            <p class="text-center">Category Not Found.</p>
                        @endif
                    </div>
                </div>

                {{-- <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('serviceCategory.search') }}" method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm mb-2"><i
                                                class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered data-table text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Category Image</th>
                                            <th class="text-nowrap">Category Name</th>
                                            <th class="text-nowrap">Category Code</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td><img src="{{ $category->category_image == 'noimage.jpg' ? Storage::disk('uploads')->url('noimage.jpg') : Storage::disk('uploads')->url($category->category_image) }}"
                                                        alt="{{ $category->category_name }}" style="max-height:100px;">
                                                </td>
                                                <td>{{ $category->category_name }}</td>
                                                <td>{{ $category->category_code }}</td>
                                                <td>
                                                    @php
                                                        $editurl = route('service_category.edit', $category->id);
                                                        $deleteurl = route('service_category.destroy', $category->id);
                                                        $csrf_token = csrf_token();
                                                        $btn = "<a href='$editurl' class='edit btn btn-primary btn-sm' title='Edit'><i class='fa fa-edit'></i></a>
                                                                    <button type='button' class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deletechild$category->id' data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
                                                                    <!-- Modal -->
                                                                        <div class='modal fade text-left' id='deletechild$category->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
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
                                                                                            <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    ";
                                                        echo $btn;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="text-sm">
                                                Showing <strong>{{ $categories->firstItem() }}</strong> to
                                                <strong>{{ $categories->lastItem() }} </strong> of <strong>
                                                    {{ $categories->total() }}</strong>
                                                entries
                                                <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                    seconds to
                                                    render</span>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="pagination-sm m-0 float-right">{{ $categories->links() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script src="{{ asset('backend/plugins/sortablejs/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/sortablejs/jquery.mjs.nestedSortable.js') }}"></script>
    <script src="{{ asset('backend/plugins/toastrjs/toastr.min.js') }}"></script>
    <script>
        $('ol.sortable').nestedSortable({
            forcePlaceholderSize: true,
            placeholder: 'placeholder',
            handle: 'div.menu-handle',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            maxLevels: 1,
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
        });

        $("#serialize").click(function(e) {
            e.preventDefault();
            $(this).prop("disabled", true);
            $(this).html(
                `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Updating...`
            );
            var serialized = $('ol.sortable').nestedSortable('serialize');
            console.log(serialized);
            $.ajax({
                url: "{{ route('updateServiceCategoryOrder') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sort: serialized
                },
                success: function(res) {
                    toastr.options.closeButton = true
                    toastr.success('category Order Successfuly', "Success !");
                    $('#serialize').prop("disabled", false);
                    $('#serialize').html(`<i class="fa fa-save"></i> Update order`);
                }
            });
        });

        function show_alert() {
            if (!confirm("Do you really want to do this?")) {
                return false;
            }
            this.form.submit();
        }
    </script>
@endpush

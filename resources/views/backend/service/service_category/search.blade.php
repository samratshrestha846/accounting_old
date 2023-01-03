@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-9">
                        <h1 class="m-0">Search Service Category </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-3">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Service Category</a></li>
                        </ol>
                    </div><!-- /.col -->
                    <div class="col-sm-12 mt-2">
                        <a href="{{ route('service_category.create') }}" class="btn btn-sm btn-primary">Add New
                            Category</a>
                    </div>
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
                @if (session('error'))
                    <div class="col-sm-12">
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="m-0 float-right">
                                    <form class="form-inline" action="{{ route('serviceCategory.search') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="search" class="sr-only">Search</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Search">
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-2"><i
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
                                                        alt="{{ $category->category_name }}" style="max-height:100px;"></td>
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
                </div>

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

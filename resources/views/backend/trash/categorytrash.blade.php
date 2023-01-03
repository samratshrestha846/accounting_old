@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Category (Trashed) </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('category.index') }}" class="global-btn">View Categories</a>
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
                            <div class="table-responsive noscroll">
                                <table class="table table-bordered data-table text-center global-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-nowrap">Category Image</th>
                                            <th class="text-nowrap">Category Name</th>
                                            <th class="text-nowrap">Category code</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td><img src="{{ $category->category_image == 'noimage.jpg' ? Storage::disk('uploads')->url('noimage.jpg') : Storage::disk('uploads')->url($category->category_image) }}"
                                                        alt="{{ $category->category_name }}" style="height:50px;">
                                                </td>
                                                <td>{{ $category->category_name }}</td>
                                                <td>{{ $category->category_code }}</td>
                                                <td>
                                                    @php
                                                        $restoreurl = route('restorecategory', $category->id);
                                                        $btn = "<button type='button' class='btn btn-primary icon-btn btn-sm' data-toggle='modal' data-target='#restoreaccount$category->id' data-toggle='tooltip' data-placement='top' title='Restore'><i class='fa fa-trash-restore'></i></button>
                                                                                                                                                                                                                                                                                    <!-- Modal -->
                                                                                                                                                                                                                                                                                        <div class='modal fade text-left' id='restoreaccount$category->id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                                                                                                                                                                                                                                                            <div class='modal-dialog' role='document'>
                                                                                                                                                                                                                                                                                                <div class='modal-content'>
                                                                                                                                                                                                                                                                                                    <div class='modal-header'>
                                                                                                                                                                                                                                                                                                    <h5 class='modal-title' id='exampleModalLabel'>Restore Confirmation</h5>
                                                                                                                                                                                                                                                                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                                                                                                                                                                                                                                                        <span aria-hidden='true'>&times;</span>
                                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <div class='modal-body text-center'>
                                                                                                                                                                                                                                                                                                        <label for='reason'>Are you sure you want to restore??</label><br>
                                                                                                                                                                                                                                                                                                        <a href='$restoreurl' class='edit btn btn-primary btn-sm' title='Restore'>Confirm Restore</a>
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    ";

                                                        echo $btn;
                                                    @endphp
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No category info trashed yet.</td></tr>
                                        @endforelse
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
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection
@push('scripts')

@endpush

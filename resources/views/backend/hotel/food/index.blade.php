@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Food Items </h1>
                    <div class="btn-bulk">
                        @can('hotel-food-create')
                            <a href="{{ route('hotel-food.create') }}" class="global-btn">Add Food Item</a>
                        @endcan
                    </div>
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
                        <div class="filter">
                            <form action="{{ route('hotel-food.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-8 pr-0">
                                        <select name="per_page" class="form-control">
                                            @foreach ($perpages as $page)
                                                <option value="{{ $page }}"
                                                    {{ $page == $per_page ? 'selected' : '' }}>{{ $page }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="global-btn">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <form class="form-inline" action="" method="">
                                <div class="form-group mx-sm-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                        placeholder="Search">
                                </div>
                                <button type="submit" class="btn btn-primary icon-btn"><i
                                        class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive mt noscroll">
                            <table class="table table-bordered yajra-datatable text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Food Name</th>
                                        <th class="text-center">Component</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($foods as $food)
                                        <tr>
                                            <td><img src="{{ Storage::disk('uploads')->url($food->food_image) }}"
                                                    alt="{{ $food->food_name }}" style="height:50px;"></td>
                                            <td>{{ $food->category ? $food->category->category_name : '-' }}</td>
                                            <td>{{ $food->food_name }}</td>
                                            <td>{{ $food->component }}</td>
                                            <td>{{ $food->food_price }}</td>
                                            <td>{{ $food->status ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <div class="btn-bulk">
                                                    @can('hotel-food-view')
                                                        <a href='{{ route('hotel-food.show', $food->id) }}'
                                                            class='edit btn btn-secondary icon-btn btn-sm' title='View'><i
                                                                class='fa fa-eye'></i></a>
                                                    @endcan
                                                    @can('hotel-food-edit')
                                                        <a href='{{ route('hotel-food.edit', $food->id) }}'
                                                            class='edit btn btn-primary icon-btn btn-sm' title='Edit'><i
                                                                class='fa fa-edit'></i></a>
                                                    @endcan
                                                    @can('hotel-food-delete')
                                                        <form action='{{ route('hotel-food.destroy', $food->id) }}'
                                                            method='POST' style='display:inline;padding:0;' class='btn'>
                                                            @csrf
                                                            <input type='hidden' name='_method' value='DELETE' />
                                                            <button type='submit' class='btn btn-secondary icon-btn btn-sm'
                                                                title='Delete'
                                                                onclick="return confirm('Are you sure you want to delete food item?')"><i
                                                                    class='fa fa-trash'></i></button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No Food Item yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="position row">
                                    <div class="col-md-8">
                                        <p class="text-sm">
                                            Showing <strong>{{ $foods->firstItem() }}</strong> to
                                            <strong>{{ $foods->lastItem() }} </strong> of <strong>
                                                {{ $foods->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b>
                                                seconds to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="pagination-sm m-0 float-right">{{ $foods->links() }}</span>
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

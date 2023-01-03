@extends('backend.layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Edit Role </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('roles.index') }}" class="global-btn">View Roles</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="display: flex;margin-bottom:0;align-items:center;">
                                        <label for="name" style="white-space: nowrap;margin-right:10px;margin-bottom:0;">Role Name<i class="text-danger">*</i>: </label>
                                        <input type="text" name="name" class="form-control" value="{{ $role->name }}"
                                            @if ($role->slug == 'biller') ? readonly @endif placeholder="Enter Permission Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($permissions as $key => $permission)
                                    <div class="this-one col-md-4">
                                        <ul>
                                            <li>
                                                <input type="checkbox" /> <b> Manage {{ $key }}
                                                </b>
                                                <ul class="padding">

                                                    @foreach ($permission as $userperm)
                                                        <li>
                                                            <input type="checkbox" name="permissions[]"
                                                                value="{{ $userperm->id }}" @if (in_array($userperm->id, $selectedperm)) {{ 'checked' }} @else {{ '' }} @endif>
                                                            {{ $userperm->name }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary bnt-sm mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $('li :checkbox').on('click', function() {
            var $chk = $(this),
                $li = $chk.closest('li'),
                $ul, $parent;
            if ($li.has('ul')) {
                $li.find(':checkbox').not(this).prop('checked', this.checked)
            }
            do {
                $ul = $li.parent();
                $parent = $ul.siblings(':checkbox');
                if ($chk.is(':checked')) {
                    $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
                } else {
                    $parent.prop('checked', false)
                }
                $chk = $parent;
                $li = $chk.closest('li');
            } while ($ul.is(':not(.someclass)'));
        });
    </script>
@endpush

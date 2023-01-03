@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>New Biller </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('biller.index') }}"
                            class="global-btn">View Billers</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('biller.store') }}" method="POST" class="">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Outlet<i class="text-danger">*</i>: </label>
                                                <select name="outlet" class="form-control outlet" id="outlet">
                                                </select>
                                                @error('outlet')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Billers<i class="text-danger">*</i>: </label>
                                                <select name="biller" class="form-control biller" id="biller_user">
                                                </select>
                                                @error('biller')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

<script type="text/javascript">
    window.onload = function() {
        let billerId = "{{ old('biller') }}";
        function fillBillers(users) {
            document.getElementById("biller_user").innerHTML = '<option value=""> --Select a biller-- </option>' +
                users.reduce((tmp, x) => `${tmp}<option value='${x.id}'${x.id == billerId ? 'selected' : ''}>${x.name}</option>`, '');
        }

        function fetchUsers() {
            $.ajax({
                url: "{{ route('billers') }}",
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var users = response;
                    fillBillers(users);
                }
            });
        }
        fetchUsers();


        let outletId = "{{ old('outlet') }}";
        function fillOutlets(outlets) {
            document.getElementById("outlet").innerHTML = '<option value=""> --Select an outlet-- </option>' +
                outlets.reduce((tmp, x) => `${tmp}<option value='${x.id}'${x.id == outletId ? 'selected' : ''}>${x.name}</option>`, '');
        }

        function fetchOutlets() {
            $.ajax({
                url: "{{ route('outlets') }}",
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var outlets = response;
                    fillOutlets(outlets);
                }
            });
        }
        fetchOutlets();

        $(document).ready(function() {
            $(".biller").select2();
        });

        $(document).ready(function() {
            $(".outlet").select2();
        });
    };
</script>
@endpush

@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Update Biller </h1>
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
                                <form action="{{ route('biller.update', $outlet_biller->id) }}" method="POST" class="">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Outlet<i class="text-danger">*</i>: </label>
                                                <select name="outlet" class="form-control outlet" id="outlet">
                                                </select>
                                                @error('outlet')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Billers<i class="text-danger">*</i>: </label>
                                                <select name="biller" class="form-control biller" id="biller_user">
                                                </select>
                                                @error('biller')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                                <p class="off text-danger message">Biller is already assigned to another Outlet.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-sm submit">Update</button>
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


<script>
    $(function() {
        $('.biller').change(function() {
            var biller = $(this).children("option:selected").val();

            var outlet_biller = <?php echo json_encode($outlet_biller); ?>;

            function fetchNewBillers(outlet_biller) {

                var uri = "{{ route('getBillers', ':id') }}";
                uri = uri.replace(':id', outlet_biller['user_id']);
                $.ajax({
                    url: uri,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response.length > 0) {
                            for (let i = 0; i < response.length; i++) {
                                if (biller == response[i].user_id) {
                                    $('.submit').addClass("disabled");
                                    $('.message').removeClass('off');
                                } else {
                                    $('.submit').removeClass("disabled");
                                    $('.message').addClass('off');
                                }
                            }
                        }
                    }
                });
            }
            fetchNewBillers(outlet_biller);
        })
    });
</script>

<script type="text/javascript">
    window.onload = function() {
        var biller = <?php echo json_encode($outlet_biller); ?>;

        function fillBillers(users) {
            document.getElementById("biller_user").innerHTML = '<option value=""> --Select a biller-- </option>' +
                users.reduce((tmp, x) => `${tmp}<option value='${x.id}'${x.id == biller.user_id ? "selected" : ""}>${x.name}</option>`, '');
        }

        function fetchUsers() {
            $.ajax({
                url: "{{ route('allbillers') }}",
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var users = response;
                    fillBillers(users);
                }
            });
        }
        fetchUsers();

        function fillOutlets(outlets) {
            document.getElementById("outlet").innerHTML = '<option value=""> --Select an outlet-- </option>' +
                outlets.reduce((tmp, y) => `${tmp}<option value='${y.id}'${y.id == biller.outlet_id ? "selected" : ""}>${y.name}</option>`, '');
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

@extends('backend.layouts.app')
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">
    <style>
        .submit.disabled {
            pointer-events: none;
            cursor: default;
            text-decoration: none;
            color: black;
        }
        .off{
            display: none;
        }
    </style>
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="ch-wrap d-flex justify-content-between align-items-center">
                    <h1>Edit Added Stock of {{$productStock->product->product_name}} in {{$productStock->godown->godown_name}} on date: {{$productStock->added_date}}</h1>
                    <a href="javascript:void(0)" onclick="goBack()" class="btn btn-primary">Go Back</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-12">
                            <h5>
                                <span class="badge badge-primary">Opening Stock: {{$productStock->godownproduct->opening_stock}}{{$productStock->product->primary_unit}}</span>
                                <span class="badge badge-success">Current Stock: {{$productStock->godownproduct->stock}}{{$productStock->product->primary_unit}}</span>
                            </h5>
                            <form action="{{route('addstock.update', $productStock->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="product_id" value="{{$productStock->product_id}}">
                                <input type="hidden" name="godown" value="{{$productStock->godown_id}}">
                                <div class="col-md-4 mt-4 mb-4">
                                    {{-- <div class="form-group">
                                        <label for="godown_id">Godown</label>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="stock">Stock</label>
                                        <input type="text" name="added_stock" class="form-control {{$product->has_serial_number == 1 ? 'serial_number' : ''}}" {{$product->has_serial_number == 1 ? 'readonly' : ''}} value="{{$productStock->added_stock}}">
                                    </div>
                                    @if($product->has_serial_number == 1)
                                    <div class="field_wrapper">
                                        <label for="serial_no">Serial Number: </label><a href="javascript:void(0);" class="add_button" title="Add field"> <i class="fas fa-plus-circle 2x"></i></a><br>
                                        @foreach ($serialnumbers as $serialnumber)
                                        <div>
                                            <input type="text" class="serialno my-1" name="serial_number[]" required value="{{$serialnumber->serial_number}}"><a href="javascript:void(0);" class="remove_button"><i class="fas fa-minus-circle 2x"></i></a>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                 </div>
                                 <button type="submit" class="btn btn-success">Submit</button>
                            </form>
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
    <script>
        function goBack() {
           window.history.back();
        }

        $(document).on('focus', '.serial_number', function() {
            $('#stock_add').modal({
                show: true
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div><input type="text" class="serialno my-1" name="serial_number[]" required value=""/><a href="javascript:void(0);" class="remove_button"><i class="fas fa-minus-circle 2x"></i></a></div>'; //New input field html
            var x = <?php echo json_encode(count($serialnumbers)) ?> ; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                // if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                    $('.serial_number').val(x);
                // }
            });



            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                if(x==1){
                    window.alert("Can't remove the last box");
                }else{
                    $(this).parent('div').remove(); //Remove field html
                    x--; //Decrement field counter
                    $('.serial_number').val(x);
                }
            });
        });
        </script>
@endpush

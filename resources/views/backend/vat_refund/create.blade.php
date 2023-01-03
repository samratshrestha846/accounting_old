@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Vat Refund</h1>
                    <div class="btn-bulk">
                        <a href="{{route('vat_refund.index')}}" class="global-btn">View all</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
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
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <h2>Fill Vat Refund Information</h2>
                            </div>
                            <div class="card-body">
                                <form id="vat_refundform" action="{{ route('vat_refund.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method("POST")
                                    <div class="row mt-3">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="fiscal_year_id">Fiscal Year<i class="text-danger">*</i>
                                                    :</label>
                                                    <select name="fiscal_year_id" class="form-control">
                                                        <option value="">--Select Fiscal year--</option>
                                                        @foreach($fiscal_years as $years)
                                                        <option value="{{$years->id}}">{{$years->fiscal_year}}</option>
                                                        @endforeach
                                                    </select>
                                                    {{-- <input type="text" id="fiscal_year_id" name="fiscal_year_id"
                                                    class="form-control" value="{{ old('fiscal_year_id') }}"
                                                    placeholder="eg:-2078" style="width:91%;"> --}}
                                                    <p class="text-danger">
                                                        {{ $errors->first('fiscal_year_id') }}
                                                    </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="amount">Amount<i class="text-danger">*</i>
                                                    :</label>
                                                    <input type="number" id="amount" name="amount"
                                                    class="form-control" value="{{ old('amount') ?? 0 }}"
                                                    placeholder="Amount" style="width:91%;">
                                                    <p class="text-danger">
                                                        {{ $errors->first('amount') }}
                                                    </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="number">Due Amount<i class="text-danger">*</i>
                                                    :</label>
                                                    <input type="number" id="due" name="due"
                                                    class="form-control" value="{{ old('due') ?? 0 }}"
                                                    placeholder="Due" style="width:91%;">
                                                    <p class="text-danger">
                                                        {{ $errors->first('due') }}
                                                    </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="refunded">Refunded<i class="text-danger">*</i>
                                                    :</label>
                                                    <select name="refunded" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="1">YES</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="total_amount">Total Amount<i class="text-danger">*</i>
                                                    :</label>
                                                    <input type="text" id="total_amount" name="total_amount"
                                                    class="form-control" value="{{ old('total_amount') }}"
                                                    placeholder="Total amount" style="width:91%;" readonly>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group  text-left">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-large">Submit</button>
        
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

@push('scripts')
<script>
   function calculatetotal(){

        var amount = $('input[name="amount"]').val();
        var due = $('input[name="due"]').val();
        $('input[name="total_amount"]').val(parseInt(amount) + parseInt(due));
    }

    $('input[name="due"]').keyup (function(){
        calculatetotal();
    });
    $('input[name="amount"]').keyup (function(){
        calculatetotal();
    });
    $(document).ready(function(){
        calculatetotal();
    });
</script>
@endpush

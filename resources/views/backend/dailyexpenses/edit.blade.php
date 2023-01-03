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
                    <h1>Update Daily Expense Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('dailyexpenses.index') }}" class="global-btn">Daily Expenses</a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('dailyexpenses.update', $dailyExpenses->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="date">Purchase Date<i class="text-danger">*</i></label>
                                                    <input type="text" id="entry_date" name="date"
                                                        class="form-control datepicker"
                                                        value="{{ old('date', $dailyExpenses->date) }}" />
                                                    <p class="text-danger">
                                                        {{ $errors->first('date') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="bill_number">Bill number<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="bill_number" class="form-control"
                                                        value="{{ old('bill_number', $dailyExpenses->bill_number) }}"
                                                        placeholder="Enter Bill number">
                                                    <p class="text-danger">
                                                        {{ $errors->first('bill_number') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="image">Bill image (optional)</label>
                                                    <input type="file" name="bill_image" class="form-control">
                                                    <p class="text-danger">Note*: Don't select new image if you want
                                                        previous one.</p>
                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="vendor_name">Purchase From<i
                                                            class="text-danger">*</i></label>
                                                    <select name="vendor_name" class="form-control supplier_info">
                                                        <option value="">--Select a Vendor--</option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor->id }}"
                                                                {{ $dailyExpenses->vendor_id == $vendor->id ? 'selected' : '' }}>
                                                                {{ $vendor->company_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger">
                                                        {{ $errors->first('vendor_name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="bill_amount">Bill Amount<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="bill_amount" class="form-control"
                                                        value="{{ old('bill_amount', $dailyExpenses->bill_amount) }}"
                                                        placeholder="Enter Bill Amount">
                                                    <p class="text-danger">
                                                        {{ $errors->first('bill_amount') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="paid_amount">Paid Amount<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="paid_amount" class="form-control"
                                                        value="{{ old('paid_amount', $dailyExpenses->paid_amount) }}"
                                                        placeholder="Enter Paid Amount">
                                                    <p class="text-danger">
                                                        {{ $errors->first('paid_amount') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="purpose">Purpose<i class="text-danger">*</i></label>
                                                    <textarea name="purpose" class="form-control" style="height:80px;" cols="30" rows="10"
                                                        placeholder="Purpose of expenses">{{ old('purpose', $dailyExpenses->purpose) }}</textarea>
                                                    <p class="text-danger">
                                                        {{ $errors->first('purpose') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ml-auto">Submit</button>
                                        </div>
                                    </form>
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

    {{-- Nepali Date-picker --}}
    <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v3.6.min.js"
        type="text/javascript">
    </script>

    <script type="text/javascript">
        window.onload = function() {
            var mainInput = document.getElementById("entry_date");
            //   var date = new Date;
            //   var year = date.getFullYear();
            //   var month = date.getMonth()+1;
            //   var day = date.getDate();
            //   let Addate = {year: year, month: month, day: day};
            //   var nepali_date = NepaliFunctions.AD2BS(Addate);
            //   var nepali_date_string = nepali_date['year'] + '/' + nepali_date['month'] + '/' + nepali_date['day'];
            //   mainInput.value = nepali_date_string;
            mainInput.nepaliDatePicker();
        };
    </script>

    <script>
        $(document).ready(function() {
            $(".supplier_info").select2();
        });
    </script>
@endpush

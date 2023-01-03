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
                    <h1>Update Offer</h1>
                    <div class="btn-bulk">
                        <a href="{{ route('offer.index') }}" class="global-btn">View Offers</a>
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
                <div class="ibox">
                    <div class="row ibox-body">
                        <div class="col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('offer.update', $existing_offer->id) }}"
                                        enctype="multipart/form-data" method="POST">
                                        @csrf
                                        @method("PUT")
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="offer_start_date">Offer Start Date (B.S):</label>
                                                    <input type="text" name="offer_start_date_nepali"
                                                        id="offer_start_date_nepali" class="form-control"
                                                        value="{{ old('offer_start_date_nepali', $existing_offer->offer_start_nep_date) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="offer_start_date_english">Offer Start Date (A.D):</label>
                                                    <input type="date" name="offer_start_date_english" id="english"
                                                        class="form-control"
                                                        value="{{ old('offer_start_date_english', $existing_offer->offer_start_eng_date) }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="offer_end_date">Offer End Date (B.S):</label>
                                                    <input type="text" name="offer_end_date_nepali"
                                                        id="offer_end_date_nepali" class="form-control"
                                                        value="{{ old('offer_end_date_nepali', $existing_offer->offer_end_nep_date) }}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="offer_end_date_english">Offer End Date (A.D):</label>
                                                    <input type="date" name="offer_end_date_english" id="englishEnd"
                                                        class="form-control"
                                                        value="{{ old('offer_end_date_english', $existing_offer->offer_end_eng_date) }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Offer Name:</label>
                                                    <input type="text" name="offer_name" class="form-control"
                                                        placeholder="Offer Name"
                                                        value="{{ old('offer_name', $existing_offer->offer_name) }}">
                                                    <p class="text-danger">
                                                        {{ $errors->first('offer_name') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Offer Percent:</label>
                                                    <input type="number" name="offer_percent" class="form-control"
                                                        placeholder="Offer Percent"
                                                        value="{{ old('offer_percent', $existing_offer->offer_percent) }}" step="any">
                                                    <p class="text-danger">
                                                        {{ $errors->first('offer_percent') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Minimum Price Range (opt):</label>
                                                    <input type="number" name="minimun_price_range" class="form-control"
                                                        placeholder="Amount in Rs."
                                                        value="{{ old('minimun_price_range', $existing_offer->range_min) }}" step="any">
                                                    <p class="text-danger">
                                                        {{ $errors->first('minimun_price_range') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="">Maximum Price Range (opt):</label>
                                                    <input type="number" name="maximum_price_range" class="form-control"
                                                        placeholder="Amount in Rs."
                                                        value="{{ old('maximum_price_range', $existing_offer->range_max) }}" step="any">
                                                    <p class="text-danger">
                                                        {{ $errors->first('maximum_price_range') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="Status" style="display: block;">Status: </label>
                                                    <span style="margin-right: 5px; font-size: 12px"> Inactive </span>
                                                    <label class="switch pt-0">
                                                        <input type="checkbox" name="status" value="1"
                                                            {{ $existing_offer->status == 1 ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span style="margin-left: 5px; font-size: 12px">Active</span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn btn-primary ml-auto">Update</button>
                                            </div>
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

    <script type="text/javascript">
        window.onload = function() {
            var offerStartDateInNepali = document.getElementById("offer_start_date_nepali");
            var offerEndDateInNepali = document.getElementById("offer_end_date_nepali");
            offerStartDateInNepali.nepaliDatePicker({
                onChange: function() {
                    var nepdate = offerStartDateInNepali.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('english').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });

            offerEndDateInNepali.nepaliDatePicker({
                onChange: function() {
                    var nepdate = offerEndDateInNepali.value;
                    var neptodaydateformat = NepaliFunctions.ConvertToDateObject(nepdate, "YYYY-MM-DD");
                    document.getElementById('englishEnd').value = NepaliFunctions.ConvertDateFormat(
                        NepaliFunctions.BS2AD(neptodaydateformat), "YYYY-MM-DD");
                }
            });
        }
    </script>
@endpush

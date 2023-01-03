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
                    <h1>Edit Follow Up </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('billings.show', $followup->billing_id) }}"
                            class="global-btn">Back</a>
                    </div>
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
                                <div class="card-header">
                                    <h2>Follow Up Title: {{ $followup->followup_title }} </h2>
                                </div>
                                <div class="card-body">
                                    <div class="container py-2">
                                        <form action="{{route('quotationfollowup.update', $followup->id)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <input type="hidden" name="billing_id" value="{{$followup->billing_id}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">Follow-up Title: </label>
                                                        <input type="text" name="followup_title" placeholder="Enter Follow-up title" class="form-control" value="{{$followup->followup_title}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date">Date: </label>
                                                        @php
                                                            $followupdate = $followup->followup_date;
                                                            $strfollowupdate = strtotime($followupdate);
                                                            $newformat = date('Y-m-d\TH:i', $strfollowupdate);
                                                        @endphp
                                                        <input type="datetime-local" class="form-control" name="followup_date" placeholder="Enter Follow-up Date" value="{{$newformat}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="details">Follow-up Details: </label>
                                                <textarea name="followup_details" cols="30" rows="3" class="form-control" placeholder="Enter Follow Up Details">{{$followup->followup_details}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="is_followed">Is followed: </label>
                                                <input type="radio" name="is_followed" value="1" {{$followup->is_followed == 1 ? 'checked' : ''}}>
                                                <input type="radio" name="is_followed" value="0" {{$followup->is_followed == 0 ? 'checked' : ''}}>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
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

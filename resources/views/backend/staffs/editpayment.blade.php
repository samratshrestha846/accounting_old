@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
<div class="right_col" role="main">

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
    <h2 class="mb-3">Edit Salary Payment Record <a href="{{route('admin.salarypayment.create')}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Go back to payment</a></h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <form action="{{route('admin.salarypayment.update', $salarypayment->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="staff">Staffs</label>
                                <select name="staff_id" id="" class="form-control">
                                    <option value="">--Select a staff--</option>
                                    @foreach ($staffs as $staff)
                                        <option value="{{$staff->id}}"{{$staff->id == $salarypayment->staff_id?'selected':''}}>{{$staff->name}}</option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="for_month">For the month of:</label>
                                @php
                                    $for_month = date('Y-m', strtotime($salarypayment->monthyear));
                                @endphp
                                <input type="month" class="form-control" name="for_month" value="{{@old('for_month')?@old('for_month'):$for_month}}">
                                @error('for_month')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" name="payment_date" value="{{@old('payment_date')?@old('payment_date'):$salarypayment->payment_date}}">
                                @error('payment_date')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Payment Amount (in Rs.)</label>
                                <input type="text" class="form-control" name="amount" placeholder="Amount in Rs." value="{{@old('amount')?@old('amount'):$salarypayment->amount}}">
                                @error('amount')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary_type">Payment Type</label>
                                <select name="salary_type" id="" class="form-control">
                                    <option value="">--Select a type--</option>
                                    <option value="advance"{{$salarypayment->salary_type == 'advance'?'selected':''}}>Advance</option>
                                    <option value="regular"{{$salarypayment->salary_type == 'regular'?'selected':''}}>Regular</option>
                                </select>
                                @error('salary_type')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')

@endpush

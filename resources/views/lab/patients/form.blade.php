@extends('backend.layouts.app')
@push('styles')


    <style type="text/css">

        .coloradd{
             color: red;
        }
        </style>
@endpush
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Patients </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('patients.index') }}" class="global-btn">View All patients</a>
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
                @if ($patient->id)
                    <form action="{{ route('patients.update', $patient->id) }}" method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('patients.store') }}" method="post">
                @endif
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="name">FullName</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="name"
                                        aria-describedby="helpId" value="{{ old('name', $patient->name) }}">
                                    @error('name')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="address" aria-describedby="helpId"
                                        value="{{ old('address', $patient->address) }}">
                                    @error('address')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="email"
                                        aria-describedby="helpId" value="{{ old('email', $patient->email) }}">
                                    @error('email')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="number">Mobile Numbers</label>
                                    <input type="number" name="number" id="number" class="form-control"
                                        placeholder="number" aria-describedby="helpId"
                                        value="{{ old('number', $patient->number) }}">
                                    @error('number')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="date">Date Of Birth</label>
                                    <input type="date" name="date" id="date" class="form-control" placeholder="date"
                                        aria-describedby="helpId" value="{{ old('date', $patient->date) }}">
                                    @error('date')
                                        <small id="helpId" class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="date">Reason For Visit</label>
                                    <textarea class="form-control" name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="gender">Gender</label>
                                <div class="d-flex">
                                    @foreach ($patient::GENDER as $key => $gender)
                                        <div class="form-check mr-2">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="gender"
                                                    value="{{ $key }}" @if ($patient->gender == $key)
                                                {{ 'checked' }}
                                    @endif>
                                    {{ $gender }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('gender')
                                <small id="helpId" class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @if (!$patient->id)
                        <div class="col-md-3 mt-3">
                            <div class="form-group">
                                <label for="toggle">Make Appointment: </label>
                                <span style="margin-left: 5px; font-size: 15px;"> No </span>
                                <label class="switch pt-0">
                                    <input type="checkbox" name="toggle" id="toggle">
                                    <span class="slider round"></span>
                                </label>
                                <span style="margin-left: 5px; font-size: 15px;">Yes</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card" id="appointmentForm" style="display: none;">
                        <div class="card-header">Appointment Form</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="appointment">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center"> Test Type<i
                                                    class="text-danger">*</i></th>

                                                <th class="text-center"> Assigned To<i
                                                        class="text-danger">*</i></th>
                                                {{-- <th class="text-center"> Date<i
                                                    class="text-danger">*</i></th> --}}
                                                <th class="text-center"> From<i
                                                    class="text-danger">*</i></th>
                                                <th class="text-center"> To</th>
                                                <th class="text-center"> Type<i
                                                    class="text-danger">*</i></th>
                                                <th class="text-center"> Notes (if any)</th>

                                                <th class="text-center"> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="appoint">
                                            <tr class="test">
                                                <td class="col-adds-wrap">
                                                        <div class="col-adds-wrap-input">
                                                            <select name='testType[1][]' class='form-control testtypeselect' id='testType_1' multiple>
                                                                <option disbaled>--Select Test Type--</option>

                                                                @foreach ($testType as $test)
                                                                <option value="{{ $test->id }}">
                                                                    {{ $test->title }}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                        <div class="col-adds-wrap-btn">
                                                            <button type="button" data-toggle='modal'
                                                                    data-target='#addtesttype' data-toggle='tooltip' data-placement='top'
                                                                    class="btn btn-primary icon-btn" title=""><i
                                                                        class="fas fa-plus"></i></button>
                                                        </div>
                                                </td>
                                                <td class="">
                                                    <select name="staff[1][]" class="form-control" multiple>
                                                        <option disabled>--Select Staff--</option>
                                                        @foreach ($designations as $designation)
                                                        @if($designation->staffs->count() > 0)
                                                        <option value="" disabled>
                                                         {{ $designation->title }}</option>
                                                         @foreach ($designation->staffs as $staff)
                                                         <option value="{{ $staff->id }}">
                                                             ↪{{ $staff->name }}({{$staff->id}})</option>
                                                         @endforeach
                                                        @endif

                                                        @endforeach

                                                    </select>
                                                </td>


                                                {{-- <td>
                                                    <input type="datetime-local" name="appointmentdate[]" id="appointmentdate_1" class="form-control">
                                                </td> --}}
                                                <td>
                                                    <input type="datetime-local" name="startTime[]" id="startTime_1" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="datetime-local" name="endTime[]" id="endTime_1" class="form-control">
                                                </td>
                                                <td>
                                                    <select name="type[]" class="form-control" id="type_1">
                                                        <option value='0'>Visit</option>
                                                        <option value='1'>Home Service</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="notes[]"
                                                        class="form-control" id="notes_1">
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-bulk">
                                                        <button class="btn btn-primary icon-btn btn-sm" type="button"
                                                        value="Delete" onclick="deleteRow(this)"><i
                                                            class="fa fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>


                                    </table>
                                    <div class="btn-bulk d-flex justify-content-end">
                                        <a class="btn btn-primary"
                                    name="add_more"
                                    onClick="addRow('appoint')"><i
                                        class="fa fa-plus"></i> Add</a>
                                    </div>

                                        <input type="hidden" id="staffoption" value="<option value='' disabled>--Select Staff--</option>
                                        @foreach ($designations as $designation)
                                        @if($designation->staffs->count() > 0)
                                        <option disabled>
                                         {{ $designation->title }}</option>
                                         @foreach ($designation->staffs as $staff)
                                         <option value='{{ $staff->id }}'>
                                            ↪{{ $staff->name }}({{$staff->id}})</option>
                                         @endforeach
                                        @endif

                                        @endforeach"
                                    name="">
                                    <input type="hidden" id="testoption" value="<option disabled>--Select Test Type--</option>
                                    @foreach ($testType as $test)
                                     <option value='{{ $test->id }}'>
                                        {{ $test->title }}</option>
                                    @endforeach">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="btn-bulk">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="Reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <!-- /.content -->
    </div>

    <div class='modal fade text-left' id='addtesttype' tabindex='-1' role='dialog'
    aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document' style="max-width: 1000px;">
        <div class='modal-content'>
            <div class='modal-header text-center'>
                <h2 class='modal-title' id='exampleModalLabel'>Add New Test Type</h2>
                <button type='button' class='close' data-dismiss='modal'
                    aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form action="{{ route('test-type.store') }}" method="POST" id="adddesignation">
                    @csrf
                    @method("POST")
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Title <span
                                                        class="text-danger">*</span>:
                                                </label>
                                                <input type="text" name="title"
                                                    class="form-control"
                                                   >
                                                <p class="text-danger">
                                                    {{ $errors->first('name') }}
                                                </p>
                                            </div>
                                        </div>
                                        <input type="hidden" name="publish" value="1">


                                        <div class="col-md-12 text-center">
                                            <button type="button"
                                                class="btn btn-secondary btn-sm addtest">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
            $("select.form-control").select2({
                templateResult: function (data, container) {
                if (data.element) {
                    $(container).addClass($(data.element).attr("class"));
                }
                return data.text;
                }
            });
    </script>
    <script>
         function deleteRow(e) {
            var t = $("#appointment > tbody > tr").length;

            if (1 == t) alert("There only one row you can't delete.");
            else {
                var a = e.parentNode.parentNode;
                a.parentNode.removeChild(a)
            }
            calculateTotal()
        }
    </script>
     <script>
        function addRow(divName) {
            var staffoption = $("#staffoption").val();
            var testoption = $("#testoption").val();
            var row = $("#appointment tbody tr").length;
            var count = row + 1;
            var limits = 500;
            var tabin = 0;
            if (count == limits) alert("You have reached the limit of adding " + count + " inputs");
            else {
                var newdiv = document.createElement('tr');
                var tabin = "testType_" + count;
                var tabindex = count * 2;
                newdiv = document.createElement("tr");
                newdiv.innerHTML = " <td><div class='col-adds-wrap'><div class='col-adds-wrap-input'><select name='testType[" + count + "][]' class='form-control testtypeselect' required id='testType_" + count + "' multiple></select></div><div class='col-adds-wrap-btn'><button type='button' data-toggle='modal' data-target='#addtesttype' data-toggle='tooltip' data-placement='top' class='btn btn-primary icon-btn' title='Add New Supplier'><i class='fas fa-plus'></i></button></div></div></td>"
                + "<td class=''>"
                +"<select name='staff["+ count +"][]' class='form-control' required id='staff_" + count + "' multiple></select></td>"
                                            // + "<td><input type='date' name='appointmentdate[]' id='appointmentdate_" + count + "' required class='form-control'></td>"
                                            + "<td><input type='datetime-local' name='startTime[]' id='startTime_"+count+"' required class='form-control'></td>"
                                            + "<td><input type='datetime-local' name='endTime[]' id='endTime_"+count+"' required class='form-control'></td>"
                                            +"<td><select name='type[]'' class='form-control' required id='type_" + count + "'>"
                                                    + "<option value='0'>Visit</option>"
                                                    + "<option value='1'>Home Service</option>"
                                                + "</select>"
                                            + "</td>"
                                            + "<td><input type='text' name='notes[]' class='form-control' id='notes_" + count + "' ></td>"
                                            + "<td style='text-align: center;'><button class='btn btn-primary icon-btn btn-sm' type='button' value='Delete' onclick='deleteRow(this)'><i class='fa fa-trash'></i></button>";
                document.getElementById(divName).appendChild(newdiv);
                document.getElementById(tabin).focus();
                $("#staff_" + count).html(staffoption);
                $("#testType_" + count).html(testoption);
                count++;
                $("select.form-control").select2();
            }
        }

    </script>
    <script>
        $(document).ready(function(){
          $("#toggle").click(function(){
            $("#appointmentForm").toggle();
          });

        });
        </script>

<script>
    $('.addtest').click(function(){
        $.post("{{route('test-type.store')}}",{
            title:$('input[name="title"]').val(),
            description:$('textarea[name="descriptionDesignation"]').val(),
            "_token":"{{csrf_token()}}"},function(response){
                $('.testtypeselect').each(function(k,v){
                    $(this).find('option').eq(0).after(response);
                })

                $('#addtesttype').modal('hide');
            })

    })
</script>
@endpush

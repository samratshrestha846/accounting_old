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
                    <h1>Make Appointment </h1>

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
                @if ($appointment->id)
                    <form
                        action="{{ route('appointment.update', ['patient' => $patient->id, 'appointment' => $appointment->id]) }}"
                        method="post">
                        @method('PATCH')
                    @else
                        <form action="{{ route('appointment.store', ['patient' => $patient->id]) }}" method="post">
                @endif
                @csrf
                <div class="card">
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
                                            <th class="text-center"> To<i
                                                class="text-danger">*</i></th>
                                            <th class="text-center"> Type<i
                                                class="text-danger">*</i></th>
                                            <th class="text-center"> Notes (if any)</th>

                                            <th class="text-center"> Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appoint">
                                        <tr class="test">
                                            <td class="">
                                                <select name='testType[1][]' class='form-control' id='testType_1' multiple>
                                                    <option disbaled>--Select Test Type--</option>
                                                    @foreach ($testType as $test)
                                                     <option value="{{ $test->id }}">
                                                        {{ $test->title }}</option>
                                                    @endforeach

                                                </select>
                                            </td>
                                            <td class="">
                                                <select name="staff[1][]" class="form-control account_head" required id="staff_1" multiple>
                                                    <option >--Select Staff--</option>
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
                                                <input type="date" name="date[]" id="date_1" required >
                                            </td> --}}
                                            <td>
                                                <input type="datetime-local" name="startTime[]" id="startTime_1" required class='form-control'>
                                            </td>
                                            <td>
                                                <input type="datetime-local" name="endTime[]" id="endTime_1" required class='form-control'>
                                            </td>
                                            <td>
                                                <select name="type[]" class="form-control" required id="type_1">
                                                    <option value='0'>Visit</option>
                                                    <option value='1'>Home Service</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="notes[]"
                                                    class="form-control" id="notes_1">
                                            </td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-primary icon-btn btn-sm" type="button"
                                                    value="Delete" onclick="deleteRow(this)"><i
                                                        class="fa fa-trash"></i></button>

                                            </td>
                                        </tr>
                                    </tbody>


                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="card-footer d-flex">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="Reset" class="btn btn-dark ml-2">Reset</button>
                    <a class="btn btn-primary"
                                                    name="add_more"
                                                    onClick="addRow('appoint')"><i
                                                        class="fa fa-plus"></i> Add More</a>
                </div>

            </div>
            </form>
            <input type="hidden" id="staffoption" value="<option value=''>--Select Staff--</option>
            @foreach ($designations as $designation)
            @if($designation->staffs->count() > 0)
            <option disabled>
             {{ $designation->title }}</option>
             @foreach ($designation->staffs as $staff)
             <option value='{{ $staff->id }}''>
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
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
            $("select.form-control").select2();
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
                newdiv.innerHTML = " <td><select name='testType[" + count + "][]' class='form-control' required id='testType_" + count + "' multiple></select></td>"
                + "<td class=''><select name='staff["+ count +"][]' class='form-control' required id='staff_" + count + "' multiple></select></td>"
                                            // + "<td><input type='date' name='date[]' id='date_" + count + "' required></td>"
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
@endpush

@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Create New User </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('user.index') }}" class="global-btn">View
                            Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Full Name<i class="text-danger">*</i>: </label>
                                        <input type="text" name="name" class="form-control" value="{{ @old('name') }}"
                                            placeholder="Enter Full Name">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="email">Email<i class="text-danger">*</i>: </label>
                                        <input type="text" name="email" class="form-control" value="{{ @old('email') }}"
                                            placeholder="E-mail Address">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="role">Role<i class="text-danger">*</i>:</label>
                                        <select class="form-control" name="role_id" id="role">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                        <label for="company">Company<i class="text-danger">*</i>:</label>
                                        <select class="form-control" name="company_id" id="company">
                                            <option value="">--Select Option--</option>
                                            @foreach ($companies as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="branch">Branch<i class="text-danger">*</i>:</label>
                                        <select class="form-control" name="branch_id" id="branch">
                                            <option value="">--Select Option--</option>
                                        </select>
                                        @error('branch_id')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div> --}}

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="password">Password<i class="text-danger">*</i>: </label>
                                        <input type="password" name="password" class="form-control"
                                            value="{{ @old('password') }}" placeholder="Password">
                                        @error('password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="confirmpassword">Confirm Password<i class="text-danger">*</i>:
                                        </label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            value="{{ @old('password_confirmation') }}" placeholder="Re-Enter Password">
                                        @error('password_confirmation')
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
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            // var company = $('#company').find(":selected").val();
            // console.log(company);
            $("#company").change(function() {
                var ccompany = $('#company').find(":selected").val();
                var allcomp = @php echo json_encode($companies); @endphp;
                var compcount = allcomp.length;
                for (let x = 0; x < compcount; x++) {
                    if (allcomp[x].id == ccompany) {
                        var allbranch = allcomp[x].branches;
                        // console.log(allbranch);
                        var branchcount = allbranch.length;
                        var options = '';
                        for (let y = 0; y < branchcount; y++) {
                            options += `<option value = "${allbranch[y].id}">${allbranch[y].name}</option>`;
                        }
                        $("#branch").html(options);
                    }
                }
            })
        });
    </script>
@endpush

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
                    <h1>New Staff Information </h1>
                    <div class="btn-bulk">
                        <a href="{{ route('staff.index') }}" class="global-btn">View
                            Staffs
                        </a>
                    </div>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="name">Employee Id<span class="text-danger">*</span> :</label>
                                                <input type="text" name="employee_id" class="form-control"
                                                    value="{{ @old('employee_id') }}" placeholder="Eg. - 001">
                                                @error('employee_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6 col-12"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">First Name<span class="text-danger">*</span> :</label>
                                                <input type="text" name="first_name" class="form-control"
                                                    value="{{ @old('first_name') }}" placeholder="Full Name">
                                                @error('first_name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Last Name<span class="text-danger">*</span> :</label>
                                                <input type="text" name="last_name" class="form-control"
                                                    value="{{ @old('last_name') }}" placeholder="Last Name">
                                                @error('last_name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email: </label>
                                                <input type="text" name="email" class="form-control"
                                                    value="{{ @old('email') }}" placeholder="Email Address">
                                                @error('email')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Contact: </label>
                                                <input type="text" name="phone" class="form-control"
                                                    value="{{ @old('phone') }}" placeholder="Contact no.">
                                                @error('phone')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Gender: </label>
                                                <select name="gender" id="" class="form-control">
                                                    <option value="">Select a gender</option>
                                                    @foreach ($genders as $gender)
                                                        <option value="{{ $gender }}" {{old('gender') == $gender ? 'selected': ''}}>{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                                @error('gender')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date_of_birth">Date of Birth: </label>
                                                <input type="date" name="date_of_birth" class="form-control"
                                                    value="{{ @old('date_of_birth') }}" placeholder="Y/m/d">
                                                @error('date_of_birth')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="department">Department: </label>
                                                <select name="department" id="" class="form-control">
                                                    <option value="">Select a department</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}" {{old('department') == $department->id ? 'selected': ''}}>{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('department')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="position">Position: </label>
                                                <select name="position" id="position" class="form-control">
                                                    <option value="">Select a position</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{ $position->id }}" {{old('position') == $position->id ? 'selected': ''}}>{{ $position->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('position')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="join_date">Join Date: </label>
                                                <input type="date" name="join_date" class="form-control"
                                                    value="{{ @old('join_date') }}" placeholder="Y/m/d">
                                                @error('join_date')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Image (Pp): </label>
                                                <input type="file" name="image" class="form-control"
                                                    value="{{ @old('image') }}">
                                                @error('image')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="national_id">National Id (Citizenship/ License) (pdf): </label>
                                                <input type="file" name="national_id" class="form-control"
                                                    value="{{ @old('national_id') }}">
                                                @error('national_id')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="documents">Document (Resume/ CV/ Traning Certificates) (pdf):
                                                </label>
                                                <input type="file" name="documents" class="form-control"
                                                    value="{{ @old('documents') }}">
                                                @error('documents')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contract">Contract/ Aggrement (pdf): </label>
                                                <input type="file" name="contract" class="form-control"
                                                    value="{{ @old('contract') }}">
                                                @error('contract')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button type="submit" class="btn btn-secondary">Submit</button>
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
@endsection

{{-- @push('scripts')
<script>
    function giveRole() {
      var row = document.getElementById("willappear");
      if (row.style.display === "none") {
        row.style.display = "block";
      } else {
        row.style.display = "none";
      }
    }
    // $(document).ready(function(){
    //     $("#slider").click(function(event){
    //         event.preventDefault();
    //     });
    // });
</script>
@endpush --}}

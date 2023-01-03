<div class='modal fade text-left' id='cabin_add' tabindex='-1' role='dialog'
aria-labelledby='exampleModalLabel' aria-hidden='true'>
<div class='modal-dialog' role='document' style="max-width: 800px;">
    <div class='modal-content'>
        <div class='modal-header text-center'>
            <h2 class='modal-title' id='exampleModalLabel'>Add Cabin</h2>
            <button type='button' class='close' data-dismiss='modal'
                aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>
        <div class='modal-body'>
            <form action="{{ route('cabintype.store') }}" method="POST" id="cabin_form">
                @csrf
                @method("POST")
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Name<i
                                    class="text-danger">*</i></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Enter Name" value="{{ old('name') }}">
                            <p class="text-danger">
                                {{ $errors->first('name') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="floor_code">Remarks</label>
                            <textarea name="remarks" class="form-control">{{old('remarks')}}</textarea>
                            <p class="text-danger">
                                {{ $errors->first('remarks') }}
                            </p>
                        </div>
                    </div>
                   

                </div>

                <button type="button" class="btn btn-secondary btn-sm modal_button"
                    name="modal_button">Save</button>
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
     $(document).on('click','.modal_button',function(){

        $('#cabin_add').find('span.text-danger').remove();

        var url = $('#cabin_form').attr('action');
        var name = $('input[name="name"]').val();
        var remarks = $('input[name="remarks"]').val();
        axios.post(url, {
            name : name,
            remarks: remarks
        }).then(response => {
            console.log(response);
            $('select[name="cabin_type"]').append('<option value="'+response.data.data.id+'">'+response.data.data.name+'</option>')
            $('input[name="name"]').val('');
            $('input[name="remarks"]').val('');
            $('#cabin_add').modal('hide');
        })
        .catch((error) => {
            console.log(error);
            if(error.response.status == 422){

                let serverError = error.response.data.errors;

               let errorName = (serverError && serverError.name) ? serverError.name[0] : "";
               let errorRemarks = (serverError && serverError.remarks) ? serverError.remarks[0] : "";

                if(errorName) {  

                    $('input[name="name"]').after('<span class="text-danger">'+errorName+'</span>');
                }

                if(errorRemarks) {  
                   
                    $('textarea[name="remarks"]').after('<span class="text-danger">'+errorRemarks+'</span>');
                }

               
            }
        });
    
        
    });
</script>
@endpush
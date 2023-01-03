<button type='button' class='btn btn-secondary icon-btn btn-sm' data-toggle='modal' data-target='#{{ 'model' . $id }}'
    data-toggle='tooltip' data-placement='top' title='Delete'><i class='fa fa-trash'></i></button>
<!-- Modal -->
<div class='modal fade text-left' id='{{ 'model' . $id }}' tabindex='-1' role='dialog'
    aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>Delete Confirmation</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body text-center'>
                <form action='{{ $route }}' method='POST' style='display:inline-block;'>
                    @csrf
                    <label for='reason'>Are you sure you want to delete??</label><br>
                    <input type='hidden' name='_method' value='DELETE' />
                    <button type='submit' class='btn btn-danger' title='Delete'>Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

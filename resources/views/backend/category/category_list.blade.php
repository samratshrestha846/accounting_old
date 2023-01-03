<li class="dd-item" id="menuItem_{{ $category->id }}" data-id="{{ $category->id }} ">
    <div class="menu-handle dd-handle dd3-content d-flex justify-content-between">
        <div class="">
            <span>
                {{ $category->category_name }}
            </span>
        </div>


        <div class="menu-options btn-group action-buttons global-table">
            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-primary icon-btn"
                title="Edit"><i class="fas fa-edit"></i></a>
            <button type="button" class="btn btn-sm btn-secondary icon-btn ml-1" data-toggle="modal"
                data-target="#deletionservice{{ $category->id }}" data-toggle="tooltip" data-placement="top"
                title="Delete"><i class="fa fa-trash"></i></button>
            
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade text-left" id="deletionservice{{ $category->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete
                        Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        @method("POST")
                        <label for="reason">Are you sure you want to
                            delete??</label><br>
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="btn btn-danger" title="Delete">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- {{ get_nested_menu($item->id) }} --}}
    @if($category->categories()->count())
    <ol class="dd-list">
        @foreach ($category->categories as $subcategory)
        @include('backend.category.category_list',['category'=>$subcategory])
        @endforeach
    </ol>
    @endif
</li>
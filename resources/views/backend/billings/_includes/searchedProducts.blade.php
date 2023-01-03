@if ($products->count())
    <ul class="list-group list-group-flush list-group-sm" id="searchProductResults">
        @foreach ($products as $item)
            <li class="list-group-item small" data-productId="{{ $item->id }}" title="click to add Product">
                {{ $item->product_name . ' (' . $item->product_code . ')' }}</li>
        @endforeach
    </ul>

@else
    No Products Found
@endif

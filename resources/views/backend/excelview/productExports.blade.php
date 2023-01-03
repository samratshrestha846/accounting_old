<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Product Code</th>

        <th>Total Stock</th>

        <th>Total Cost</th>
        <th>Product Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->product_code }}</td>

            <td>{{ $product->total_stock }}</td>

            <td>{{ $product->total_cost }}</td>
            <td>{{ $product->product_price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

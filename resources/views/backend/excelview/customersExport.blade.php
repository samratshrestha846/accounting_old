<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Customer Code</th>
        <th>Opening Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->client_code }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>

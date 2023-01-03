<table>
    <thead>
        <tr>

            <th></th>
            <th>Suppliers</th>
            <th></th>
            <th></th>
        </tr>

        <tr></tr>
        <tr>
            <th class="text-nowrap">Company Name</th>
            <th class="text-nowrap">Company Code</th>
            <th class="text-nowrap">Contact Person</th>
            <th class="text-nowrap">Company Email</th>
            <th class="text-nowrap">Company Code</th>
            <th class="text-nowrap">Company Phone</th>
            <th class="text-nowrap">Opening Balance</th>
            {{-- <th class="text-nowrap">Closing Balance</th> --}}
            {{-- <th class="text-nowrap">Company Address</th> --}}
            <th class="text-nowrap">Pan No./Vat No.</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($vendors as $vendor)
        <tr>
            <td>{{ $vendor->company_name }}</td>
            <td>{{ $vendor->supplier_code == null ? 'Not Provided' : $vendor->supplier_code }}
            </td>
            <td>{{ $vendor->concerned_name == null ? 'Not Provided' : $vendor->concerned_name }}
            </td>
            <td>{{ $vendor->company_email == null ? 'Not Provided' : $vendor->company_email }}
            </td>
            <td>{{$vendor->supplier_code}}</td>
            <td>{{ $vendor->company_phone == null ? 'Not Provided' : $vendor->company_phone }}
            </td>
            <td>{{$vendor->opening_balance->opening_balance ?? ""}}</td>
            {{-- <td>{{$vendor->opening_balance->closing_balance ?? ""}}</td> --}}
            <td>{{ $vendor->pan_vat == null ? 'Not Provided' : $vendor->pan_vat }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tbody>
    </tbody>
</table>

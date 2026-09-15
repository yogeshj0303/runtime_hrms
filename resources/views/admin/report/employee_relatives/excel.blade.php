<table>
    <thead>
        <tr>
            <th>SN</th>
            <th>CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>RELATIONSHIP</th>
            <th>RELATIVE NAME</th>
            <th>DATE OF BIRTH</th>
            <th>DEPENDENT</th>
            <th>PHONE</th>
            <th>E-MAIL</th>
            <th>NOTES</th>
        </tr>
    </thead>
    <tbody>
        @foreach($relatives as $index => $relative)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ optional($relative->employee)->employee_code ?? '-' }}</td>
                <td>{{ optional($relative->employee)->first_name }} {{ optional($relative->employee)->last_name }}</td>
                <td>{{ $relative->relation ?? '-' }}</td>
                <td>{{ $relative->name ?? '-' }}</td>
                <td>{{ $relative->dob ? \Carbon\Carbon::parse($relative->dob)->format('d M Y') : '-' }}</td>
                <td>{{ $relative->is_dependent ? 'Yes' : 'No' }}</td>
                <td>{{ $relative->phone ?? '-' }}</td>
                <td>{{ $relative->email ?? '-' }}</td>
                <td>{{ $relative->notes ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

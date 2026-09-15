<table border="1">
    <thead>
        <tr>
            <th colspan="11" style="text-align: center; font-weight: bold; font-size: 16px;">
                Employee Master Export Records
            </th>
        </tr>
        <tr>
            <th>EMPLOYEE CODE</th>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>EMAIL ADDRESS</th>
            <th>PHONE NUMBER</th>
            <th>JOINING DATE</th>
            <th>EXIT DATE</th>
            <th>STATUS</th>
            <th>LOCATION</th>
            <th>DEPARTMENT</th>
            <th>DESIGNATION</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $emp)
            @php
                $wp = $emp->workProfiles->first();
            @endphp
            <tr>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->first_name }}</td>
                <td>{{ $emp->last_name }}</td>
                <td>{{ $emp->email }}</td>
                <td>{{ $emp->phone }}</td>
                <td>{{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('Y-m-d') : '-' }}</td>
                <td>{{ $emp->exit_date ? \Carbon\Carbon::parse($emp->exit_date)->format('Y-m-d') : '-' }}</td>
                <td style="text-transform: capitalize;">{{ $emp->status }}</td>
                <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

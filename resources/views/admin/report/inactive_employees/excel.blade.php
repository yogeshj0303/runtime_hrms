<table border="1">
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 16px;">
                Inactive Employees Report
            </th>
        </tr>
        <tr>
            <th>SN</th>
            <th>EMPLOYEE CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>JOINING DATE</th>
            <th>LOCATION</th>
            <th>COST CENTER</th>
            <th>DEPARTMENT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $index => $emp)
            @php
                $wp = $emp->workProfiles->first();
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                <td>{{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('Y-m-d') : '-' }}</td>
                <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->costCenter)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

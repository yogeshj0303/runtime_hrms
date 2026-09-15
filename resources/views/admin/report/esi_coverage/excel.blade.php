<table>
    <thead>
        <tr>
            <th>SN</th>
            <th>CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>DEPARTMENT</th>
            <th>LOCATION</th>
            <th>GROSS SALARY</th>
            <th>ESI NUMBER</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['employee']->employee_code ?? '-' }}</td>
                <td>{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                <td>{{ optional(optional($item['employee']->workProfiles->first())->department)->name ?? '-' }}</td>
                <td>{{ optional(optional($item['employee']->workProfiles->first())->location)->name ?? '-' }}</td>
                <td>{{ $item['gross_salary'] }}</td>
                <td>{{ $item['esi_number'] ?? '-' }}</td>
                <td>{{ $item['status_str'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

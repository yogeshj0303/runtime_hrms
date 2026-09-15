<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 16px;">
                Increment Ageing Report
            </th>
        </tr>
        <tr>
            <th>EMPLOYEE CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>DESIGNATION</th>
            <th>DEPARTMENT</th>
            <th>LAST INCREMENT DATE</th>
            <th>AGEING</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $emp)
            @php
                $wp = $emp->workProfiles->first();
            @endphp
            <tr>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($emp->last_increment_date)->format('Y-m-d') }}</td>
                <td>{{ $emp->ageing_str }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

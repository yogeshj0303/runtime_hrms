<table border="1">
    <thead>
        <tr>
            <th colspan="16" style="text-align: center; font-weight: bold; font-size: 16px;">
                Cost To Company Report
            </th>
        </tr>
        <tr>
            <th>EMPLOYEE CODE</th>
            <th>EMPLOYEE NAME</th>
            <th>DESIGNATION</th>
            <th>DEPARTMENT</th>
            <th>LOCATION</th>
            <th>REVISION DATE</th>
            <th>BASIC</th>
            <th>HRA</th>
            <th>ALLOWANCES</th>
            <th>GROSS SALARY</th>
            <th>PF (EMPLOYER)</th>
            <th>ESI (EMPLOYER)</th>
            <th>PT</th>
            <th>TDS</th>
            <th>NET SALARY</th>
            <th>CTC</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exportData as $data)
            @php
                $emp = $data['employee'];
                $rev = $data['revision'];
                $wp = $emp->workProfiles->first();
            @endphp
            <tr>
                <td>{{ $emp->employee_code }}</td>
                <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($rev->effective_from)->format('Y-m-d') }}</td>
                <td>{{ $rev->basic_salary }}</td>
                <td>{{ $rev->hra }}</td>
                <td>{{ $rev->allowances }}</td>
                <td>{{ $rev->gross_salary }}</td>
                <td>{{ $rev->pf }}</td>
                <td>{{ $rev->esi }}</td>
                <td>{{ $rev->pt }}</td>
                <td>{{ $rev->tds }}</td>
                <td>{{ $rev->net_salary }}</td>
                <td>{{ $rev->ctc }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

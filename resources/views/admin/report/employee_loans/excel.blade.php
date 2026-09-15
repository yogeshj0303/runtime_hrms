<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000000;
        padding: 5px;
    }
    th {
        background-color: #133C5A;
        color: #ffffff;
        font-weight: bold;
    }
    .schedule-header {
        background-color: #e9ecef;
        color: #000;
    }
</style>

<table>
    <thead>
        <tr>
            <th colspan="{{ $reportType == 'detailed' ? 10 : 8 }}" style="text-align: center; font-size: 16px; font-weight: bold;">Employee Loans Report</th>
        </tr>
        <tr>
            <th>SN</th>
            <th>E-CODE</th>
            <th>EMPLOYEE</th>
            <th>LOAN AMOUNT</th>
            <th>ISSUE DATE</th>
            <th>EMI</th>
            <th>TENURE</th>
            <th>STATUS</th>
            @if($reportType == 'detailed')
                <th>INSTALLMENT DATE</th>
                <th>INSTALLMENT AMOUNT</th>
                <th>INSTALLMENT STATUS</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(isset($loans) && $loans->count() > 0)
            @foreach($loans as $index => $loan)
                @if($reportType == 'detailed' && $loan->installments->count() > 0)
                    @foreach($loan->installments as $instIndex => $inst)
                    <tr>
                        @if($instIndex == 0)
                            <td rowspan="{{ $loan->installments->count() }}">{{ $index + 1 }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ $loan->employee->employee_code }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ number_format($loan->loan_amount, 2) }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ $loan->issue_date->format('d M, Y') }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ number_format($loan->emi_amount, 2) }}</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ $loan->tenure_months }} Months</td>
                            <td rowspan="{{ $loan->installments->count() }}">{{ $loan->status }}</td>
                        @endif
                        <td>{{ $inst->installment_date->format('M Y') }}</td>
                        <td>{{ number_format($inst->amount, 2) }}</td>
                        <td>{{ $inst->status }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $loan->employee->employee_code }}</td>
                        <td>{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</td>
                        <td>{{ number_format($loan->loan_amount, 2) }}</td>
                        <td>{{ $loan->issue_date->format('d M, Y') }}</td>
                        <td>{{ number_format($loan->emi_amount, 2) }}</td>
                        <td>{{ $loan->tenure_months }} Months</td>
                        <td>{{ $loan->status }}</td>
                        @if($reportType == 'detailed')
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="{{ $reportType == 'detailed' ? 10 : 8 }}" style="text-align: center;">No loans found for the selected filters.</td>
            </tr>
        @endif
    </tbody>
</table>

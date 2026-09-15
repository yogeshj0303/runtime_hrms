<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Loans Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #133C5A;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #133C5A;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #133C5A;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .loan-col {
            font-weight: bold;
            color: #133C5A;
            white-space: nowrap;
        }
        .schedule-table th, .schedule-table td {
            font-size: 8px;
            padding: 3px 5px;
        }
        .schedule-table th {
            background-color: #e9ecef;
            color: #333;
        }
        .nested-row td {
            padding: 0;
            background-color: #f8f9fa;
        }
        .nested-box {
            padding: 10px;
        }
        .nested-box strong {
            display: block;
            margin-bottom: 5px;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Employee Loans Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">SN</th>
                <th width="10%">E-CODE</th>
                <th width="20%">EMPLOYEE</th>
                <th width="15%">LOAN AMOUNT</th>
                <th width="15%">ISSUE DATE</th>
                <th width="10%">EMI</th>
                <th width="10%">TENURE</th>
                <th width="15%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($loans) && $loans->count() > 0)
                @foreach($loans as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $loan->employee->employee_code }}</td>
                    <td>{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</td>
                    <td class="loan-col">{{ number_format($loan->loan_amount, 2) }}</td>
                    <td>{{ $loan->issue_date->format('d M, Y') }}</td>
                    <td>{{ number_format($loan->emi_amount, 2) }}</td>
                    <td>{{ $loan->tenure_months }} Months</td>
                    <td>{{ $loan->status }}</td>
                </tr>
                @if($reportType == 'detailed' && $loan->installments->count() > 0)
                <tr class="nested-row">
                    <td colspan="8">
                        <div class="nested-box">
                            <strong>Loan Schedule:</strong>
                            <table class="schedule-table">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                                @foreach($loan->installments as $inst)
                                <tr>
                                    <td>{{ $inst->installment_date->format('M Y') }}</td>
                                    <td>{{ number_format($inst->amount, 2) }}</td>
                                    <td>{{ $inst->status }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            @else
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No loans found for the selected filters.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        SomyaHRMS - Employee Loans Report &copy; {{ date('Y') }}
    </div>

</body>
</html>

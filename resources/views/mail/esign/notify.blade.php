<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notify RM</title>

    <style>
        .table-bordered{
            border: 1px solid #252525;
        }
        .table-bordered td{
            border: 1px solid #252525;
        }
    </style>
</head>
<body>
    <p> Dear <strong>{{ $RMName }}</strong><br> You are hereby requested to resolve the following pendency/issue against the mentioned loan Account no. <strong>{{ $LoanNo }}</strong>,
        Customer name <strong>{{ $customername }}</strong> for which the document have been first submitted by you on {{ $notifydate }} <br>Looking for your co-operation.</p>
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th width="4%">SR</th>
                        <th width="90%">Pending/Rejected Document</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($documents as $Key => $documentsData)
                    <tr class="text-center">
                        <td width="4%">{{ $Key+1 }}</td>
                        <td>{{ $documentsData }}</td>
                        <td>{{ $status[$Key] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>
                <strong>Thanks & Regards</strong>
                <br>Operation Team (HO) <br>User id-{{ auth()->user()->emp_id }}
            </p>
</body>
</html>
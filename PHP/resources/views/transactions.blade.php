<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions</title>
</head>

<body>
<table border="1">
    <tr>
        <th>Amount Debit</th>
        <th>Amount Credit</th>
        <th>Balance</th>
        <th>Time</th>
    </tr>
    @foreach($transactions as $transaction)
<tr>
    <td>{{ $transaction->debit }}</td>
    <td>{{ $transaction->credit }}</td>
    <td>{{ $transaction->balance }}</td>
    <td>{{ $transaction->time }}</td>
</tr>
    @endforeach
</table>
</body>
</html>
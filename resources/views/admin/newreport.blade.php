<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <br>
    <p>Dear {{ $user->name}},</p> <br>
    <p>There is a new report on {{ $report->title }}, located at {{ $report->latitude }}, {{ $report->longitude }}.</p>
    <p>The report currently has {{ $report->votes }} votes.</p>
    <p>Please click the following link for details</p>
    <a href="http://127.0.0.1:8000/report/{{ $report->id }} ">See Details</a>

<br>
<br>
<p>Yours respectfully,</p>
<p>The ReportiTT Team.</p>
   
</body>
</html>
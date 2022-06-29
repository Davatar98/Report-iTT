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
    <p>Your report on {{ $report->title }}, Issue No: {{ $report->id }}, has had its status changed to {{ $report->status }}</p>
    <p>Your report details can be found  <a href="http://127.0.0.1:8000/report/{{ $report->id }}">here.</a></p>
<br>
<br>
<p>Yours respectfully,</p>
<p>The ReportiTT Team.</p>
   
</body>
</html>
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
    <p>The ReportiTT team regrets to inform you that the following report has been discarded:</p> <br>
      <p>Issue Number: {{ $report->id }} </p>    
       <p>Title: {{ $report->title }} </p>  
   <p>Description: {{ $report->description }}</p><br>
<br>
<br>
<p>Yours respectfully,</p>
<p>The ReportiTT Team.</p>
   
</body>
</html>
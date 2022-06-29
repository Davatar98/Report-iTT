<html>
<head>
    <title>ReportiTT Reports Summary</title>
</head>
<body>
    <h1 style="text-align: center">{{ $title }}</h1>
    <div style="width:100%">
        <table style="border: 1px solid #ddd; width: 100%" >
        <tr><td>Reports Received: </td><td>{{ $received}}</td></tr>
        <tr><td>Reports Resolved: </td><td>{{ $resolved}}</td></tr>
        <tr><td>Resolution Rate: </td><td>{{ $rate}}</td></tr>
        <br>
        <tr><td>Total Outstanding Reports: </td><td>{{ $outstanding}}</td></tr>
        <tr><td>Outstanding Reports Resolved: </td><td>{{ $outstandingResolved}}</td></tr>
     </table>
    
    </div>
     
</body>
</html>t
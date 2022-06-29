@extends('layouts.report-table')

@section('content')

<!-- Create a table with the reports -->

<div class="container justify-content-center">
    
<div class="card " >
    <div class="card-header" >My Reports</div>
    <div class="card-body">
      <div style="overflow-x:auto;">
        <table class="table" id="report-table">
            <thead>
              <tr>
                <th scope="col">Issue Number:</th>
                <th scope="col">Issue</th>
                <th scope="col">Description</th>
                <th scope="col">Service Provider</th>
                <th scope="col">Status</th>
                <th scope="col">Votes</th>
                <th scope="col">Flags</th>
               <th scope="col">Date Reported</th>
               <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report ->title }}</td>
                    <td>{{ $report->description }}</td>
                    <td>{{ $report->organisation }}</td>
                    <td>{{ $report->status }}</td>
                    <td>{{ $report->votes }}</td>
                    <td>{{ $report->flags }}</td>
                    <td >{{ $report->created_at->format('d. m. Y') }} </td> 
                    <td onclick="window.location='{{ route('report.show', ['report' => $report]) }}'"><a href="#">View Details</a></td>
                    
                </tr>

                @endforeach
              
              
            </tbody>
          
          </table>

      </div>

    </div>             
</div>
</div></div>

    

{{-- <ul class=" pagination justify-content-center" >{!! $reports->links() !!}</ul> --}}
 
 <script>
  $(document).ready(function(){
    $('#report-table').DataTable();
  });
</script>
@endsection
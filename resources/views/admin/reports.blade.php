@extends('layouts.report-table')

@section('content')

<!-- Create a table with the reports -->

<div class="container justify-content-center">
    
<div class="card" >
    <div class="card-header" >My Reports</div>
    <div class="card-body">

      <div>
        <table  cellspacing="5" cellpadding="5">
          <tbody>
              <tr>
                  <td>Minimum Date:</td>
                  <td><input name="min" id="min" type="text" autocomplete="off"></td>
              </tr>
              <tr>
                  <td>Maximum Date:</td>
                  <td><input name="max" id="max" type="text" autocomplete="off"></td>
              </tr>
          </tbody>
      </table>
      </div>
      <div style="overflow-x:auto;">
        <table class="table" id="admin-table">
            <thead >
              <tr>
                <th scope="col" >Issue Number: </th>
                <th scope="col" >Issue</th>
                <th scope="col" >Description</th>
                <th scope="col" >Status</th>
               <th scope="col" >Votes</th>
                <th scope="col" >Flags</th> 
                <th data-type="date" {{-- data-format-string="DoMMYYYY" --}}scope="col">Date Created</th>
                {{-- <th scope="col">Created By: </th> --}}
                <th scope="col"></th>
              <!--  <th scope="col">Date Reported</th>-->
              </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                <tr>
                  <td>{{ $report->id }}</td>
                  <td >{{ $report ->title }}</td><td>{{ $report->description }}</td>
                  <td>{{ $report->status }}</td>
                  <td>{{ $report->votes }}</td>
                  <td>{{ $report->flags }}</td> 
                  <td >{{ $report->created_at->format('Y/m/d') }} </td> 
                   {{-- {{ $my_custom_datetime_field->format('d. m. Y') }} --}}
                   
                   {{-- <td>{{ $report->user_name }}<td>make a route to go to the user profile. --}}
                   <td onclick="window.location='{{ route('report.show', ['report' => $report]) }}'"><a href="#">View Details</a> </td>
                </tr>
                @endforeach
            
            </tbody>
          </table>

      </div>

        




    </div>             
</div>
</div></div>

<script>
 
  
  $(document).ready(function(){
        $.fn.dataTable.ext.search.push(
         
        function (settings, data, dataIndex) {
          
          //formats but table loads and disappears if the field is empty.
        //  var min =  $("#min").datepicker("option", "dateFormat", "yy/mm/dd" ).val();
        //  var max =  $("#max").datepicker("option", "dateFormat", "yy/mm/dd" ).val();
            // var min = $('#min').datepicker("getDate").formatDate('yy/mm/dd');
            // var max = $('#max').datepicker("getDate").formatDate('yy/mm/dd');
          // $('#min').val($.datepick.formatDate('yyyy-mm-dd', min));
          // $('#max').val($.datepick.formatDate('yyyy-mm-dd', max));
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[6]);

            if (min == null && max == null) { return true; }
            if (min == null && startDate <= max) { return true;}
            if(max == null && startDate >= min) {return true;}
            if (startDate <= max && startDate >= min) { return true; }
            return false;
        }
        );

       
            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            var table = $('#admin-table').DataTable();

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });
        });


  
</script>

@endsection
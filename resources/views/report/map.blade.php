@extends('layouts.appmain')
{{--  --}}


@section('content')



<div id="main-section" class="container">
<!-- Report Card/Sheet -->
    <div class="row">
        
    <!-- Leaflet map -->

    <div id="map" class=" order-xs-1  order-lg-5 col-xs-12 col-sm-7 col-lg-7">
       
        <script>
            var map = L.map("map").setView([10.6418, -61.3995], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
             
            
        
        </script>
        
        
    



            </div>
           <div class=" order-xs-1  order-lg-5 col-xs-12 col-sm-1 col-lg-1"><button id="create-report">Click Me</button></div> 
    <div id="report" class="order-xs-5  order-lg-1 col-xs-12 col-sm-8 col-lg-4">
    <strong> Report Section - this is where report details will be displayed </strong>

        <table>
            <tr>
              <th>Company</th>
              <th>Contact</th>
              <th>Country</th>
            </tr>
            <tr>
              <td>Alfreds Futterkiste</td>
              <td>Maria Anders</td>
              <td>Germany</td>
            </tr>
            <tr>
              <td>Centro comercial Moctezuma</td>
              <td>Francisco Chang</td>
              <td>Mexico</td>
            </tr>
          </table>
    </div>
    

</div>
</div>
@endsection

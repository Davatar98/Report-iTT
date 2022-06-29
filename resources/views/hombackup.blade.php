@extends('layouts.form')
{{--  --}}


@section('content')
<div id="main-section" class="container">
<!-- Report Card/Sheet -->
    <div class="row">
        
    <!-- Leaflet map -->
    
 <strong>This is the Map(controls coming soon)</strong> 
    <div id="map" class=" order-xs-1  order-lg-5 col-xs-12 col-sm-8 col-lg-8">
    
        <script>
            var map = L.map("map").setView([10.6418, -61.3995], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
//creating markers 
var greenIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
var blueIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
var yellowIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
//https://stackoverflow.com/questions/67858785/mark-location-from-database-using-leaflet-in-laravel
        $(function(){
    $.ajax({
        url: "/leaflet",

        type: 'GET',

        success: function(data) {
          
         
          
          //Displaying date. Should make a function to do this since I will have to filter by date in the controls
                

          function formatDate(timezone){
            var set = toString(timezone);
            var date = new Date(timezone);
            date.toLocaleString('en-US', { timeZone: 'America/Port_of_Spain' });
            date.getDate();
           
          var newFormat = date.getDate()+"/"+date.getMonth()+"/"+date.getFullYear() + " "+date.getHours()+  ":"+date.getMinutes()+":"+date.getSeconds();
          
        
            return newFormat;
          }


          var markerTTEC =[];
          var markerWASA;
          var markerTSTT;


        
       for(var i=0; i<data.length; i++){
         switch(data[i].organisation_id){
          case 1:
           
          // markerTTEC = L.marker([data[i].latitude, data[i].longitude],{icon : yellowIcon}).addTo(map)
          //           .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
          //           'Created:'+ formatDate(data[i].created_at) + "<a href='http://www.cnn.com'>Test</a>" + "<br>" );///link works. 
          //           //var cities = L.layerGroup([markerTTEC]);
          //           console.log(markerTTEC);

          markerStyle = yellowIcon;
          marker = L.marker([data[i].latitude, data[i].longitude],{icon : yellowIcon}).addTo(map)
          .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
          'Created:'+ formatDate(data[i].created_at) + "<a id='phone' href=''>Test</a>" + "<br>" );
          layerGroup.addLayer(marker);
                    
          break;
          case 2:
          marker2 = L.marker([data[i].latitude, data[i].longitude],{icon : blueIcon}).addTo(map)
          .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
          'Created:'+ formatDate(data[i].created_at) + "<a id='phone' href=''>Test</a>" + "<br>" );
          layerGroup2.addLayer(marker2);
        //   markerWASA = L.marker([data[i].latitude, data[i].longitude],{icon : blueIcon})
        //   .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
        //   'Created:'+ formatDate(data[i].created_at) + "<a id='phone' href=''>Test</a>" + "<br>" );
        //  layerGroup2.addLayer(markerWASA);
        markerStyle = blueIcon;
          break;
          case 3:
          marker3 = L.marker([data[i].latitude, data[i].longitude],{icon : greenIcon}).addTo(map)
          .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
          'Created:'+ formatDate(data[i].created_at) + "<a id='phone' href=''>Test</a>" + "<br>" );
          layerGroup3.addLayer(marker3);
          // markerTSTT= L.marker([data[i].latitude, data[i].longitude],{icon : greenIcon})
          // .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
          // 'Created:'+ formatDate(data[i].created_at)+ "<a href='http://www.cnn.com'>Test</a>" + "<br>" );
          // layerGroup3.addLayer(markerTSTT);
          markerStyle = greenIcon;
          break;
          default:
          console.log("Test");
         };
    
    //  marker = L.marker([data[i].latitude, data[i].longitude],{icon : markerStyle}).addTo(map)
    //       .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
    //       'Created:'+ formatDate(data[i].created_at) + "<a id='phone' href=''>Test</a>" + "<br>" );
    //       layerGroup.addLayer(marker);
        // 
        //  layerGroup.addLayer([markerTTEC]);
        // var overlay = {'TTEC': layerGroup};
        // //, 'WASA': layerGroup2,'TSTT': layerGroup3};

        // L.control.layers(null, overlay).addTo(map);

       }
         
            $.each(data, function( key, value ) {
     
              
          
                    
            })
        },
        error: function(data) {

        }
    });
})

         

   // apply custom layer groupings and controls to filter by type/organisation id 

var layerGroup = L.layerGroup().addTo(map);
var layerGroup2 = L.layerGroup().addTo(map);
var layerGroup3 = L.layerGroup().addTo(map);

// for (i = 0; i < coordinates.length; i++) {
//     marker = L.marker([coordinates[i][0], coordinates[i][1]],{icon: yellowIcon});
//     layerGroup.addLayer(marker);
// }
    var overlay = {'TTEC Reports': layerGroup, 'WASA Reports' : layerGroup2, 'TSTT Reports': layerGroup3};
L.control.layers(null, overlay).addTo(map);

</script>
        
</div>
    <div id="report-display" class="order-xs-5  order-lg-1 col-xs-12 col-sm-8 col-lg-4">
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

<script>


</script>
@endsection

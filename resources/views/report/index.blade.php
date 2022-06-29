@extends('layouts.form')
{{--  --}}


@section('content')
<div id="main-section" class="container">
<!-- Report Card/Sheet -->
    <div class="row">
        
    <!-- Leaflet map -->
    
 <strong>Click on map marker for report summary.</strong> 
 <strong>Double click on marker for report details.</strong>
    <div id="map" class=" ">
    
  <script>
       var map = L.map("map").setView([10.6418, -61.3995], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
 
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
          function formatDate(timezone){
            var set = toString(timezone);

            var date = new Date(timezone);
           date.toLocaleString('en-US', { timeZone: 'America/Port_of_Spain' });
           // date.getDate();
           
           var month = date.getMonth() + 1;
          var newFormat = date.getDate()+"/"+ month +"/"+date.getFullYear() + " "+date.getHours()+  ":"+date.getMinutes();
          
        
            return newFormat;
          }
          var cluster = L.markerClusterGroup.layerSupport();
           var water = L.markerClusterGroup.layerSupport();
           function addToCluster(marker){
          
             cluster.addLayer(marker);
          
             clusterLayer.addLayer(marker);
             cluster.addTo(map);
           }
//https://stackoverflow.com/questions/67858785/mark-location-from-database-using-leaflet-in-laravel
// $(function(){
//     $.ajax({
//         url: "/leaflet",

//         type: 'GET',
     
//         success: function(data) {
//           console.log(data);

          




           

         
// //https://stackoverflow.com/questions/29035896/leaflet-dont-fire-click-event-function-on-doubleclick
//         //Big Working
//        for(var i=0; i<data.length; i++){
//          switch(data[i].organisation_id){
//           case 1:
//           markerStyle = yellowIcon;
//           marker = L.marker([data[i].latitude, data[i].longitude],{icon : yellowIcon},{})
//           .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
//           'Created:'+ formatDate(data[i].created_at)+ "<br>" );
//           marker.url = '/report/'+ data[i].id;
//           clicked = 0;
//           marker.on('click', function(event){
//               clicked = clicked + 1;
//               setTimeout(function(){
//                   if(clicked == 1){
//                     marker.openPopup();
//                   }
//               }, 300);
//           });
//           marker.on('dblclick', function(event){
//               clicked = 0;
//               window.location = (this.url);
                  
//           });

//           layerGroup.addLayer(marker);
//           getStatus(data[i].status, marker);
//           addToCluster(marker);
          
          
//           break;
//           case 2:
//           marker2 = L.marker([data[i].latitude, data[i].longitude],{icon : blueIcon})
//           .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
//           'Created:'+ formatDate(data[i].created_at) + "<br>" );
//           layerGroup2.addLayer(marker2);
//           addToCluster(marker2)
//           getStatus(data[i].status, marker2);    
//           marker2.url = '/report/'+ data[i].id;
//           clicked = 0;
//           marker2.on('click', function(event){
//               clicked = clicked + 1;
//               setTimeout(function(){
//                   if(clicked == 1){
//                     marker2.openPopup();
//                   }
//               }, 300);
//           });
//           marker2.on('dblclick', function(event){
//               clicked = 0;
//               window.location = (this.url);
                  
//           }); 
       
//           break;
//           case 3:
//           marker3 = L.marker([data[i].latitude, data[i].longitude],{icon : greenIcon})
//           .bindPopup( 'Title: ' + data[i].title + "<br>" + 'Description: ' + data[i].description + "<br>" + 
//           'Created:'+ formatDate(data[i].created_at) + "<br>" );
//           layerGroup3.addLayer(marker3);
//           //cluster.addLayer(marker3);
//           getStatus(data[i].status, marker3);
//           addToCluster(marker3)
//           marker3.url = '/report/'+ data[i].id;
//           clicked = 0;
//           marker3.on('click', function(event){
//               clicked = clicked + 1;
//               setTimeout(function(){
//                   if(clicked == 1){
//                     marker3.openPopup();
//                   }
//               }, 300);
//           });
//           marker3.on('dblclick', function(event){
//               clicked = 0;
//               window.location = (this.url);
                  
//           }); 
          
//           break;
//           default:
      
//          };
  

//        }
         
//             $.each(data, function( key, value ) {
     
              
          
                    
//             })
//         },
//         error: function(data) {

//         }
//     });
// })


//    // apply custom layer groupings and controls to filter by type/organisation id 

 var layerGroup = L.layerGroup().addTo(map);
 var layerGroup2 = L.layerGroup().addTo(map);
 var layerGroup3 = L.layerGroup().addTo(map);
 var layerGroupCreated = L.layerGroup();
 var layerGroupProgress = L.layerGroup();
 var layerGroupCompleted = L.layerGroup();
 var clusterLayer = L.layerGroup();

// var waterLayer = L.layerGroup();
     var overlay = {'TTEC Reports': layerGroup, 'WASA Reports' : layerGroup2, 'TSTT Reports': layerGroup3,
      'Status: Created': layerGroupCreated,
      'Status: In Progress': layerGroupProgress,
      'Status: Completed': layerGroupCompleted};
 L.control.layers(null, overlay).addTo(map);
 L.control.scale().addTo(map);
 function getStatus(status, marker){
          
          switch(status){
          case 'In-Progress':
          layerGroupProgress.addLayer(marker);
     
          break;
          case 'Completed':
          layerGroupCompleted.addLayer(marker);
          break;
          case 'Created':
          layerGroupCreated.addLayer(marker);
          break;
         
          }
        }

</script>
        

<script>
  
 $(function(){
        $.ajax({
            url: "/leafletTTEC",

            type: 'GET',
            cache: true,
           
    


          //  var cluster = L.markerClusterGroup.layerSupport();
          //  function addToCluster(marker){
           
          //    cluster.addLayer(marker);
            
          //    clusterLayer.addLayer(marker);
          //    cluster.addTo(map);
          //  }
            success: function(data) {
                $.each(data, function( key, value ) {

                 marker3 =   L.marker([value.latitude, value.longitude],{icon: yellowIcon})
                        .bindPopup(
                          "<strong> Issue Number: </strong>"+ value.id+ "<br>" +
                          "<strong>Title: </strong>"+ value.title + "<br>" + 
                          "<strong>Description: </strong>" + value.description + "<br>"+
                          "<strong>Date Created: </strong>" + formatDate(value.created_at) + "<br>" + 
                          "<strong>Status: </strong>"+ value.status + "<br" );
                       //  var cluster = L.markerClusterGroup.layerSupport();
                       // addToCluster(marker3);
                       getStatus(value.status,marker3);
                       addToCluster(marker3);
                        layerGroup.addLayer(marker3);
                        marker3.url = '/report/'+ value.id;//creates url for each report.
                        clicked = 0;
                        marker3.on('click', function(event){
                         clicked = clicked + 1;
                        setTimeout(function(){
                          if(clicked == 1){
                         marker3.openPopup();
                    }
                      }, 300);
                   });
                   marker3.on('dblclick', function(event){
                  clicked = 0;
                   window.location = (this.url);
                  
           }); 
                })
            },
            error: function(data) {

            }
        });
    })

    $(function(){
        $.ajax({
            url: "/leafletWASA",

            type: 'GET',
            cache:true,

            success: function(data) {
                $.each(data, function( key, value ) {

                 marker2 =   L.marker([value.latitude, value.longitude],{icon: blueIcon})
                 .bindPopup(
                          "<strong> Issue Number: </strong>"+ value.id+ "<br>" +
                          "<strong>Title: </strong>"+ value.title + "<br>" + 
                          "<strong>Description: </strong>" + value.description + "<br>"+
                          "<strong>Date Created: </strong>" + formatDate(value.created_at) + "<br>" + 
                          "<strong>Status: </strong>"+ value.status + "<br" );
                         layerGroup2.addLayer(marker2);
                         getStatus(value.status,marker2);
                         addToCluster(marker2);
                        marker2.url = '/report/'+ value.id;
                        clicked = 0;
                        marker2.on('click', function(event){
                         clicked = clicked + 1;
                        setTimeout(function(){
                          if(clicked == 1){
                         marker2.openPopup();
                    }
                      }, 300);
                   });
                   marker2.on('dblclick', function(event){
                  clicked = 0;
                   window.location = (this.url);
                  
           }); 
                })
            },
            error: function(data) {

            }
        });
    })

    $(function(){
        $.ajax({
            url: "/leafletTSTT",

            type: 'GET',
            cache: true,
            
            success: function(data) {
                $.each(data, function( key, value ) {

                 marker =   L.marker([value.latitude, value.longitude],{icon: greenIcon}).addTo(map)
                 .bindPopup(
                          "<strong> Issue Number: </strong>"+ value.id+ "<br>" +
                          "<strong>Title: </strong>"+ value.title + "<br>" + 
                          "<strong>Description: </strong>" + value.description + "<br>"+
                          "<strong>Date Created: </strong>" + formatDate(value.created_at) + "<br>" + 
                          "<strong>Status: </strong>"+ value.status + "<br" );
                         addToCluster(marker);
                         layerGroup3.addLayer(marker);
                         getStatus(value.status,marker);
                        marker.url = '/report/'+ value.id;
                        clicked = 0;
                        marker.on('click', function(event){
                         clicked = clicked + 1;
                        setTimeout(function(){
                          if(clicked == 1){
                         marker2.openPopup();
                    }
                      }, 300);
                   });
                   marker.on('dblclick', function(event){
                  clicked = 0;
                   window.location = (this.url);
                  
           }); 
                })
            },
            error: function(data) {

            }
        });
    })

</script>
</div>


@endsection

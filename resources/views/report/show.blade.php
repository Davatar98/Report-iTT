
@extends('layouts.showlayout')
{{--  --}}


@section('content')




  <div class="row justify-content-center">
<div class="col-md-6">
<div class="card" style="">
  <div class="card-header"><strong>Fault Details</strong></div>
  <div class="card-body">
    <table>
     
      <tbody>
        <tr><td>Issue Number: </td><td>{{ $report->id }}</td></tr>
        <tr><td>Fault Type:</td><td>{{ $report->title }}</td> </tr>
        <tr><td>Fault Description:</td><td>{{ $report->description }}</td> </tr>
        <tr><td>Fault Status:</td><td>{{ $report->status }}</td> </tr>
        <tr><td>Report Created:</td><td>{{ $newDate }}</td> </tr>
       
        @can('view-details',$report)
           <tr><td>Reported by:</td><td>{{ $user->name }}</td> </tr>
        <tr><td>Email:</td><td>{{ $user->email }}</td> </tr>
        <tr><td>Phone:</td><td>{{ $user->phone }}</td> </tr>
         
        @endcan
       
       
      </tbody>
      
    </table>
   <div class="magnific-img">
     @foreach ($images as $image)
   
      <a class="pop"  href="#"><img id="thumb" src="{{ asset('reportimages/'.$image->name) }}" alt="" style="width: 100px; "></a>
   
     @endforeach 
    </div>
    <div class="modal fade " id="imagemodal" style="width: 100%;"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

      <div class="modal-dialog modal-dialog-centered  modal-lg">
        <button type="button" class="close" style="position: right;" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-content">              
          <div class="modal-body">
            <img src="" class="imagepreview" style="width: 100%;" >
          </div>
        </div>
      </div>
    </div>
   
    <div class="col-md-2">
    
  @can('update-status',$report)   
     @if ($report->status =="Submitted")
  <form method="POST" action="{{ route('report.status') }}">
    @csrf
   
    <input type="hidden" value="{{ $report->id }}" name="id">
 <button  name="status" value="In-Progress">Mark as In Progress</button>

 <input type="hidden" value="{{ $report->id }}" name="id">
 <button name="status" value="Discarded">Delete Report</button>
 
  </form>

  @endif
  
  @if ($report->status == "In-Progress")
  <form method="POST" action="{{ route('report.status') }}">
    @csrf
  
    <input name="id" type="hidden" value="{{ $report->id }}">
  <button name="status" value="Completed" >Mark as Completed</button>
  </form>
  @endif
 @endcan   




    </div>

    
   @can('isUser')
      @if ($report->status =="Created") 
    @livewire('report-votes',['reportID' => $report->id])
     @endif 
   @endcan
   

@can('isUser')
   @if ( $report->status =="Created")
    @livewire('report-flags',['reportID' => $report->id])
    @endif
@endcan
  
   
    
 
  </div>
</div>
</div>
<div class="col-md-6">
<div class="card" style="">
  <div class="card-header"><strong id="map-label">Fault Location</strong> </div>
  <div class="card-body">
    
    <div id="map" style="height: 360px">
    
  </div>
</div>
</div>


</div>
       
<script>


          // icon 
 var redIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

         var map = L.map("map").setView([{{ $report->latitude }}, {{ $report->longitude }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
             
        var marker4 =  L.marker([{{ $report->latitude }}, {{ $report->longitude }}], {icon:redIcon}).addTo(map)
        .bindPopup('Issue:' +"{{ $report->title }}" + "<br>" + 'Created at: '+ "{{ $newDate }}" + "<br>"+
        'Status: '+ "{{ $report->status }}");
       
              
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
                    //   addToCluster(marker3);
                        layerGroup.addLayer(marker3);
                        marker3.url = '/report/'+ value.id;
                        clicked = 0;

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
                          "<strong>Status: </strong>"+ value.status + "<br");
                         layerGroup2.addLayer(marker2);
                         getStatus(value.status,marker2);
                      //   addToCluster(marker2);
                        marker2.url = '/report/'+ value.id;
                        clicked = 0;
                        
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
                          "<strong>Status: </strong>"+ value.status + "<br");
                        // addToCluster(marker);
                        layerGroup3.addLayer(marker);
                         getStatus(value.status,marker);
                        marker.url = '/report/'+ value.id;
                        clicked = 0;
                       
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

<script>
$(function() {
		$('.pop').on('click', function() {
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
});


</script>

@endsection

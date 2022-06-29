
@extends('layouts.form')




@section('content')

<!-- Dropdown to choose form -->


<!-- Form -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="width: auto;">
                <div class="card-header" >{{ __('Create Report') }}</div>
                <div class="card-body">

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form id="form" method="POST" action="{{ route('report.store')  }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3 justify-content-center">
                        <label for="provider"> Select a Service Provider:</label>
                        <select  name="provider">
                            <option value="">--- Select Provider ---</option>
                            @foreach($organisations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                          
                        </select> </div>

                       <!-- Blade Directive to get the faults by organisation id based on the value selected from the dropdown above. -->
                        
                        <br>    
                        <div  class="row mb-3"><label for="title">Type of Issue:</label>
                            <select name="fault">Type of Fault
                            <option>--Type of Fault--</option></select>
                        </div>
                        
                        <br>
                        <div class="row mb-3"> <label for="description">Provide Details of the Issue:</label>
                            
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea></div> 
                          <!-- <input type="text" id="description" name="description"> -->
                        
                        <br>
                        <div class="row mb-3"></div>
                        <label for="location">Click the Map to Enter Location:</label>
                        
                            <div id="map" class="map-report" >
       
                                <script>
                                    var map = L.map("map").setView([10.6418, -61.3995], 9);

                                 
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);
                


                          map.addControl(L.control.locate({
                                locateOptions: {
                                        enableHighAccuracy: true
                            }}));

                    
                        var marker;
                        map.on('click', function (e) {
                          if (marker) { // check
                          map.removeLayer(marker); // remove
                         }
                            marker = new L.Marker(e.latlng).addTo(map); // set
                            $("#latitude").val(e.latlng.lat);
                            $("#longitude").val(e.latlng.lng);
                             });
  </script>

                        </div>
                        <div class="row mb-3" id="lat"> 
                        <div class="col-sm-3 order-1"> 
                        <label for="latitude">Latitude:</label> <br>
                        <input type="number" step="0.000000000000001" id="latitude" name="latitude"> </div></div> <br>


                        <div class="row mb-3" id="lon"> 
                        <div class="col-sm-3" >
                        <label for="longitude">Longitude:</label><br>
                        <input type="number" step="0.000000000000001" id="longitude" name="longitude"></div> </div><br>
                        
                     <div class="row mb-3">
                     <div class="col-sm-6">
                         
                        <label for="imageFile">Upload Photos of the Problem (Max Image Size:10MB, Max Images:3):</label>
                         <input type="file" id="images" name="images[]"  accept="image/*" multiple="mulitple"></div>
                         
                    </div>

                    
                            
                    <div id="btn-div" class="col-mb-3">
                    <button id="report-btn" type="submit" class="btn btn-primary">
                        {{ __('Create Report') }}
                    </button></div>


                    </div>

 </div>             
                    

               
                </div>
                
             </form>


        
                </div>
              </div>
            
        </div>
    </div>

</div>

<!-- Show Dropdown Depending on the Service Provider Selected -->
<script>
   jQuery(document).ready(function ()
    {
            jQuery('select[name="provider"]').on('change',function(){
               var providerID = jQuery(this).val();
               if(providerID)
               {
                  jQuery.ajax({
                     url : '/report/create/getfaults/' +providerID,
                     type : "GET",
                     dataType : "json",

                     
                     success:function(data)
                     {
                        console.log(data);
                        jQuery('select[name="fault"]').empty();
                        jQuery.each(data, function(key,value){
                           $('select[name="fault"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                     }
                  });
               }
               else
               {
                  $('select[name="fault"]').empty();
               }
            });
            
    });
</script>

<!-- Map Functions -->
<!-- Get user lat and lon by clicking and displaying in respective text field -->

<
@endsection
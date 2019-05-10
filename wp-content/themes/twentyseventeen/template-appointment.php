<?php /* Template Name: Trial */ 
get_header();
?>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script> -->

<script>

jQuery(document).ready(function($) {
$( "#datepicker" ).datepicker();

});

</script>
<style>
      #map {
        height: 200px;
        width: 40%;
       }
    </style>
     <h3>My Google Maps Demo</h3>
    <div col-md-12 id="map"></div>
    <script>
      function initMap() {
        var uluru = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6x5Wj9prJUfDGbRFdLu45XWnq2hZLJxM&callback=initMap">
    </script>


<div class="col-md-4" id="datepicker"></div>


<?php /* Template Name: appointment page template */ 
get_header();
?>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script> -->



  <script  type="text/javascript">

  var array;
  $.get("https://ipinfo.io/json", function (response) {
    $("#ip").html('<span class="iptitle" > Your IP:</span> ' + response.ip);
    var array = response.loc.split(',')
     $("#lat").val(array[0]);
    //$("#lat").val('40.86460000001');
     $("#long").val( array[1]);
   // $("#long").val( '-73.94815239999');  
    $("#address").html(' <span class="loc"> Location:</span> ' + ' ' +response.city   + ',' + response.region);
    //$("#details").html(JSON.stringify(response, null, 4));
}, "jsonp");

  
</script>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<style>
  #map {
    height: 350px;
    width: 100%;
   }

  h6.designation {
font-size: 16px;

/*margin-left:10px;*/
  }

    h6.designation.agcolor {
  color: #777;
  font-weight: bold;
  margin-top: 10px;
  }
  h6.opto.designation {
      font-family: kievit-slab-book,Georgia,serif;
      line-height: 24px;
      
  }
  body{
    background: #403B4A; /* fallback for old browsers */
background: -webkit-linear-gradient(to left, #403B4A , #E7E9BB); /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to left, #403B4A , #E7E9BB); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background-size: 100% 100%;

  }
  body{
     background-image: url("<?php echo get_template_directory_uri();?>/images/noise.png");
    background-repeat: repeat-y;
  }
  .schbody{
   /* background: #f3f3f3;*/
  /* background: repeating-linear-gradient(
  to right,
  #f6ba52,
  #f6ba52 10px,
  #ffd180 10px,
  #ffd180 20px
);
background-size: 100% 100%;*/
/*background: #44A08D; /* fallback for old browsers */
/*background: -webkit-linear-gradient(to left, #44A08D , #093637); /* Chrome 10-25, Safari 5.1-6 */
/*background: linear-gradient(to left, #44A08D , #093637);*/ /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */



  }

  h1.page_header {
      background: white;
      text-align: center;
      /* margin: 10px 0px; */
      padding: 20px 0px;
      border-radius: 10px;
  }
  .col-md-8.desc {
    background: white;
    padding: 15px;
    font-size: 15px;
    line-height: 25px;
    border-radius: 10px;
  }
  .col-md-6.logodesc {
    padding-right: 0px;
  }

  .col-md-5.googmap {
      border-radius: 10px;
      margin-left: 0px;
  }

  h2.schedule.col-md-4 {
    
    /*background: #FFF;*/
    padding: 20px;
    border-radius: 10px;
    margin-top: 0px;
    color: black;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);

  }

  div.schbody {
      margin-top: -40px;
  }
  .row.appbooksection{
    margin-top:0px;
  }
  .col-md-12.docdesc {
    /*background: #d9edf7;*/
    background:rgba(218, 188, 46, 0.45);
    /*padding: 12px;*/
    border-radius: 10px;
    /*margin-left: -20px;*/
    margin-right: 20px;
    margin-bottom: 10px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
  }
  .col-md-7.entry-content-page {  
    /* margin-right: -10px; */
    background: #FFf;
    /* padding-left: 30px; */
    padding-top: 3px;
    padding-left:3px;
    padding-right:3px;
    border-radius: 10px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    /* margin-bottom: 20px; */
    /* padding-bottom: 20px; */
}
 #calendar.fc, #calendar.fc table {
    font-size: 12px;
    color: #004208  !important;
}
.fc-state-highlight {
    background: #e8e8e8;

}
.greyclass{
  color:grey !important;
}
div#ip {
    color: red;
}
div#address {
    font-style: italic;
    color: #008340;
}
  #address span.loc, #ip span.iptitle, p#tdis {
font-size: 15px;
font-style: normal;
font-weight: bolder;
background: #f7f7f7;
padding-left: 10px;
    border-bottom: 1px solid #f1f1f1;
/*padding: 8px;*/
/* margin-top: 22px; */
}
#tdis span#total{
  font-style: italic;
  background: none;
  color:red;
}
#ip span.iptitle{
  color: #008340;
}
div#address , div#ip{
  padding: 0px 0px;
}
pre#right-panel {
display: none;
}
.namedoc.col-md-7 h4 a {
    color: #5bc0de;
}

.fc-header-title h2 {
    font-size: 25px;
}

span.fc-button.fc-button-today.fc-state-default.fc-corner-left.fc-corner-right {
    display: none;
}
.fc-event.fc-event-vert.fc-event-start.fc-event-end{
  min-height: 20px;
}
body{
     font-family: 'Raleway', sans-serif;
}


/*Changes made today heheeh :) */
@media (max-width: 991px) { 
  [class*="col-"]:not(:first-child){
      margin-top: 25px;
  }
    .docpic.col-md-5 {
      float: left;
   }
  .namedoc.col-md-6 {
      text-align: center;
    }
  div#map {
    margin-top: 20px;
  }
  .col-md-12.docdesc {
    width: 100%;
}
}
div#avtime {
    background-color: #d9edf7;
        margin-top: -10px;
        color:#000;
}

</style>


    

  <div class ="schbody">
    <div class="container">
      <div class="row appbooksection">
        <div class="col-md-4"> </div>
        <h2 class="schedule col-md-4"> Book an Appointment</h2>
        <div class="col-md-4"></div>
      </div>

      <div class="row">
        <div class="col-md-7 entry-content-page">
               <?php
               // TO SHOW THE PAGE CONTENTS
                while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
                      <?php the_content(); ?> <!-- Page Content -->
                  <!-- .entry-content-page -->
                <?php
                endwhile; //resetting the page loop
                wp_reset_query(); //resetting the page query
                ?>
          </div>
       <!--  <div class="col-md-6 logodesc">
                <div class="col-md-3 logolc "> 
                  <img src="<?php //echo get_template_directory_uri();?>/logo.jpg" class="img-responsive"/>
                   <h3 class="locationTitle"> Location </h3>
                   <p class="location" >
                     4738 Broadway </br>
                      New York, NY 10040
                   </p>     
                </div>
                <div class="col-md-8  desc">
                  
                  Our Experienced Staff Offers Comprehensive Examinations. We Specialize In The Diagnosis And Treatment Of A Wide Array Of Vision And Eye Health Problems.
                  <div  id="content1" class="collapse">
                  We use advanced diagnostic technology and vision correction products and are committed to improving the quality of life of the people in the Washington Heights and Inwood community. Give yourself the gift of clear vision and schedule an appointment today.
                  </div> -->
                 <!--  <button type="button" class="" data-toggle="collapse" data-target="#content1">
                  <span class="glyphicon glyphicon-chevron-down"></span> Read more
                </button> -->
               <!-- <hr>
                <div id="ip"></div>
                <hr>
                <div id="address"></div>
                <hr> -->
              <!--   <hr/>Full response
                <pre id="details"> </pre>
 -->

            <div class= "col-md-5 googmap">
              <div class="col-md-12 docdesc">
              <div class="row">
                <div class="docpic col-md-5">
                  <img src="<?php echo get_template_directory_uri().'/images/json.png'?>"  class="img-circle"/>
                </div>
                <div class= "namedoc col-md-6">
                  <h4> <a> Dr. Jason Compton OD, FAAO </a> </h4>
                  <h6 class="designation agcolor" > Specialties </h6>
                  <h6 class="opto designation">Optometrist </h6>
                </div>
              </div>
              </div>
              <div  id="map"></div>
              <p id="tdis">Total Distance: <span id="total"> click see route to calculate distance from your place </span></p>
              <button class="btn btn-primary" id="seeroute"> See route </button> 
            </div>
              

                 
                 <!-- <pre id="right-panel"></pre> -->
                 
        </div> 
                     
      </div>

    </div>    
      <!-- <hr>  -->
      
      <!-- <div id="container"> -->
              <!-- <div id="content" class="pageContent"> -->

             <!-- Page Title -->
           
              

          <!--  <div class= "appday"></div> 
                  <div class="row">
                  
                    <div class="col-md-6" >
                      <div class="results"> </div>
                    </div>
                  </div> -->
          <!-- 
              </div> -->
      <!-- </div> -->
 
<input id="lat" type="hidden" name="country" value="1">
<input type="hidden" id ="long" name="country" value="23">

<script src="https://www.gstatic.com/firebasejs/3.7.3/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBseYF486Pjyrra6qfY_5FecJApGRWptSs",
    authDomain: "comptoneyes.firebaseapp.com",
    databaseURL: "https://comptoneyes.firebaseio.com",
    storageBucket: "comptoneyes.appspot.com",
    messagingSenderId: "422158753726"
  };
  firebase.initializeApp(config);
</script>

        <script>
          function initMap() {
            //var location = {lat:40.86473060000001, lng:-73.92815239999999};
            var location = {lat:27.7166, lng:85.3485};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 13,
              center: location,
              scrollwheel:false
            });
            var marker = new google.maps.Marker({
              position: location,
              map: map,
              title:"Compton Eye"
            });
            var directionsService = new google.maps.DirectionsService;
            var directionsDisplay = new google.maps.DirectionsRenderer({
              draggable: true,
              map: map,
              panel: document.getElementById('right-panel')
            });

            directionsDisplay.addListener('directions_changed', function() {
              computeTotalDistance(directionsDisplay.getDirections());
            });
            

            document.getElementById('seeroute').addEventListener('click', function() {
            var orilat = document.getElementById('lat').value;
            var orilong= document.getElementById('long').value;
            var orilatnum = Number(orilat);
            var orilongnum= Number(orilong);
            //document.getElementById('total').innerHTML = orilatnum + 'what' + orilongnum;
            var origclient= {lat:orilatnum, lng:orilongnum};
            displayRoute(origclient , location, directionsService,directionsDisplay); } );
          }
          function displayRoute(origin, destination, service, display) {
            service.route({
              origin: origin,
              destination: destination,
              travelMode: 'DRIVING',
              avoidTolls: true
            }, function(response, status) {
              if (status === 'OK') {
                display.setDirections(response);
              } else {
               alert('Could not display driving directions due to: ' + status);
              // display.setDirections(response);
              }
            });
          }

          function computeTotalDistance(result) {
            var total = 0;
            var myroute = result.routes[0];
            for (var i = 0; i < myroute.legs.length; i++) {
              total += myroute.legs[i].distance.value;
            }
            total = total / 1000;
            document.getElementById('total').innerHTML = total + ' km';
            //document.getElementById('seeroute').hide();
            $("#seeroute").hide();

          }
          
        </script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6x5Wj9prJUfDGbRFdLu45XWnq2hZLJxM&callback=initMap">
        </script>
    



<?php 
function day(){

if( isset($_POST['day'])) {
            global $wpdp;
            $TodaysAllDayEvent = 0;
            $Enable  = array();
            $TimeOffTableName = $wpdb->prefix ."ap_events";

            //if today is any all-day time-off then show msg no time available today
            $TodaysAllDayEventData = $wpdb->get_results( $wpdb->prepare("SELECT `start_time`, `end_time`, `repeat`, `start_date`, `end_date` FROM `$TimeOffTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = %s",'1'), OBJECT);
            
            //check if appointment date in any recurring time-off date
            foreach($TodaysAllDayEventData as $SingleTimeOff) {
                // none check
                if($SingleTimeOff->repeat == 'N') {
                    $TodaysAllDayEvent = 1;
                }

                // daily check
                if($SingleTimeOff->repeat == 'D') {
                    $TodaysAllDayEvent = 1;
                }

                // weekly check
                if($SingleTimeOff->repeat == 'W') {
                    $EventStartDate = $SingleTimeOff->start_date;
                    $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                    if(($diff % 7) == 0) {
                        $TodaysAllDayEvent = 1;
                    }
                }

                //bi-weekly check
                if($SingleTimeOff->repeat == 'BW') {
                    $EventStartDate = $SingleTimeOff->start_date;
                    $diff = ( strtotime($EventStartDate) - strtotime($AppointmentDate)  )/60/60/24;
                    if(($diff % 14) == 0) {
                        $TodaysAllDayEvent = 1;
                    }
                }

                //monthly check
                if($SingleTimeOff->repeat == 'M') {
                    // calculate all monthly dates
                    $EventStartDate = $SingleTimeOff->start_date;
                    $EventEndDate = $SingleTimeOff->end_date;
                    $i = 0;
                    do {
                            $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($EventStartDate)));
                            $AllEventMonthlyDates[] = $NextDate;
                            $i = $i+1;
                    } while(strtotime($EventEndDate) != strtotime($NextDate));

                    //check appointment-date in $AllEventMonthlyDates
                    if(in_array($AppointmentDate, $AllEventMonthlyDates)) {
                        $TodaysAllDayEvent = 1;

                    }
                }
            }//end of event fetching foreach

          echo "<div id='specific_date'>0</div>";
                           
    }
  }
?>
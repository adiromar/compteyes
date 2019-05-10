<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

function sendMessage($data,$target){
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAxOdsypE:APA91bHriZpLzpSHI_Okba4xw7ybqI74WubYqtcgOwue4dyOx3lXMTKUqVl4T-o8M3qTsr1oUlytD7PgBR0P-SC3XK6iQNH4rRcROVXQn5jp8iJcNIJ0Zl7tZquLZoaQ4-T_pmBDyL0e';
                    
        $fields = array();
        $notification= array('notification' =>json_encode($data));
        // $fields['data'] = $notification;
        $fields['data'] = $data;
        if(is_array($target)){
            $fields['registration_ids'] = $target;
        }else{
            $fields['to'] = $target;
        }
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
          'Authorization:key='.$server_key
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        echo $result;
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
}



//short code file with appointment booking button and big calendar(full-calendar)


add_shortcode( 'APCAL', 'comptoneye_appointment_calendar_shortcode' );

function comptoneye_appointment_calendar_shortcode() {
    
    if( get_locale() ) {
        
        $language = get_locale();
        
        if($language) { define('L_LANG',$language); }
        
    } 
    //check events for that date 
        
    
    //save appointment and email admin & client/customer
    if( isset($_POST['Client_Name']) && isset($_POST['Client_Email']) ) {
        
        global $wpdb;
        
        if( !wp_verify_nonce($_POST['wp_nonce'],'appointment_register_nonce_check') ){
            
            print 'Sorry, your nonce did not verify.';  exit;
            
        }
        
        $ClientName      =   sanitize_text_field( $_POST['Client_Name']  );
        
        $ClientEmail     =   sanitize_email(      $_POST['Client_Email'] );
        
        $ClientPhone     =   intval(              $_POST['Client_Phone'] );
        
        $ClientNote      =   sanitize_text_field( $_POST['Client_Note']  );
        
        $AppointmentDate =   date("Y-m-d", strtotime( sanitize_text_field( $_POST['AppDate'] )  ) );
        
        $ServiceId       =   intval(              $_POST['ServiceId'] );
        
        $ServiceDuration =   sanitize_text_field( $_POST['Service_Duration'] );
        
        $StartTime       =   sanitize_text_field( $_POST['StartTime'] );
        
        
        
        //calculate end time according to service duration
        $EndTime           =  date( 'h:i A' , strtotime( "+$ServiceDuration minutes" , strtotime( $StartTime ) ) );
        
        $AppointmentKey    =  md5( date( "F j, Y, g:i a" ) );
        
        $Status            =  __( "pending" , "comptoneye" );
        
        $AppointmentBy     =  __( "user" , "comptoneye" );

        $data = array("title" => "New Appointment", "body" => "New appointment by ".$AppointmentBy ." on ". 
        $AppointmentDate);   
        $token = "clYR9qjzOPg:APA91bEZUDLgdHttI39guJcuIXLH-ij9go06xUwrSTYUduWQTUHqZRsuzLxsUL4qtIkQ7AushflS3km8Pm86BqiogV2CATx6STRMSKBG2oz2q1Gx6ebShJllXV3TmvuEEM1AlTjGNWst"; 
        sendMessage($data , $token);    
        $AppointmentsTable =  $wpdb->prefix . "ap_appointments";
        $query = $wpdb->query( 
            
            $wpdb->prepare(
            
            "
            INSERT INTO $AppointmentsTable 
        
            ( id , name , email , service_id , phone , start_time , end_time , date , note , appointment_key , status , appointment_by )
        
            VALUES ( %d , %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            
            ",
                array(
                    null,
                    $ClientName,
                    $ClientEmail,
                    $ServiceId,
                    $ClientPhone,
                    $StartTime,
                    $EndTime,
                    $AppointmentDate,
                    $ClientNote,
                    $AppointmentKey,
                    $Status,
                    $AppointmentBy
                )
            )


        );
        
        if( $query ) {
            
            
            $BlogName = get_bloginfo();
            
            //get service details
            $ServiceTable = $wpdb->prefix . "ap_services";
            
            $ServiceData = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $ServiceTable WHERE id = %d" , $ServiceId ) , OBJECT);
            
            $ServiceName = $ServiceData->name;
            
            
            //check notification is enabled
            $NotificationStatus = get_option('emailstatus');
            if($NotificationStatus == "on") {
                $Attachments = "";
                $AppointmentTime = $StartTime." - ".$EndTime;
                $AdminSubject = get_option('new_appointment_admin_subject');
                $AdminSubject = str_replace("[blog-name]", ucwords($BlogName), $AdminSubject);
                $AdminSubject = str_replace("[client-name]", ucwords($ClientName), $AdminSubject);
                $AdminSubject = str_replace("[client-email]", ucwords($ClientEmail), $AdminSubject);
                $AdminSubject = str_replace("[client-phone]", ucwords($ClientPhone), $AdminSubject);
                $AdminSubject = str_replace("[client-si]", ucwords($ClientNote), $AdminSubject);
                $AdminSubject = str_replace("[service-name]", ucwords($ServiceName), $AdminSubject);
                $AdminSubject = str_replace("[app-date]", $AppointmentDate, $AdminSubject);
                $AdminSubject = str_replace("[app-status]", ucwords($Status), $AdminSubject);
                $AdminSubject = str_replace("[app-time]", $AppointmentTime, $AdminSubject);
                $AdminSubject = str_replace("[app-key]", $AppointmentKey, $AdminSubject);
                $AdminSubject = str_replace("[app-note]", ucfirst($ClientNote), $AdminSubject);

                $AdminBody = get_option('new_appointment_admin_body');
                $AdminBody = str_replace("[blog-name]", ucwords($BlogName), $AdminBody);
                $AdminBody = str_replace("[client-name]", ucwords($ClientName), $AdminBody);
                $AdminBody = str_replace("[client-email]", ucwords($ClientEmail), $AdminBody);
                $AdminBody = str_replace("[client-phone]", ucwords($ClientPhone), $AdminBody);
                $AdminBody = str_replace("[client-si]", ucwords($ClientNote), $AdminBody);
                $AdminBody = str_replace("[service-name]", ucwords($ServiceName), $AdminBody);
                $AdminBody = str_replace("[app-date]", $AppointmentDate, $AdminBody);
                $AdminBody = str_replace("[app-status]", ucwords($Status), $AdminBody);
                $AdminBody = str_replace("[app-time]", $AppointmentTime, $AdminBody);
                $AdminBody = str_replace("[app-key]", $AppointmentKey, $AdminBody);
                $AdminBody = str_replace("[app-note]", ucfirst($ClientNote), $AdminBody);


                $ClientSubject = get_option('new_appointment_client_subject');
                $ClientSubject = str_replace("[blog-name]", ucwords($BlogName), $ClientSubject);
                $ClientSubject = str_replace("[client-name]", ucwords($ClientName), $ClientSubject);
                $ClientSubject = str_replace("[client-email]", ucwords($ClientEmail), $ClientSubject);
                $ClientSubject = str_replace("[client-phone]", ucwords($ClientPhone), $ClientSubject);
                $ClientSubject = str_replace("[client-si]", ucwords($ClientNote), $ClientSubject);
                $ClientSubject = str_replace("[service-name]", ucwords($ServiceName), $ClientSubject);
                $ClientSubject = str_replace("[app-date]", $AppointmentDate, $ClientSubject);
                $ClientSubject = str_replace("[app-status]", ucwords($Status), $ClientSubject);
                $ClientSubject = str_replace("[app-time]", $AppointmentTime, $ClientSubject);
                $ClientSubject = str_replace("[app-key]", $AppointmentKey, $ClientSubject);
                $ClientSubject = str_replace("[app-note]", ucfirst($ClientNote), $ClientSubject);

                $ClientBody = get_option('new_appointment_client_body');
                $ClientBody = str_replace("[blog-name]", ucwords($BlogName), $ClientBody);
                $ClientBody = str_replace("[client-name]", ucwords($ClientName), $ClientBody);
                $ClientBody = str_replace("[client-email]", ucwords($ClientEmail), $ClientBody);
                $ClientBody = str_replace("[client-phone]", ucwords($ClientPhone), $ClientBody);
                $ClientBody = str_replace("[client-si]", ucwords($ClientNote), $ClientBody);
                $ClientBody = str_replace("[service-name]", ucwords($ServiceName), $ClientBody);
                $ClientBody = str_replace("[app-date]", $AppointmentDate, $ClientBody);
                $ClientBody = str_replace("[app-status]", ucwords($Status), $ClientBody);
                $ClientBody = str_replace("[app-time]", $AppointmentTime, $ClientBody);
                $ClientBody = str_replace("[app-key]", $AppointmentKey, $ClientBody);
                $ClientBody = str_replace("[app-note]", ucfirst($ClientNote), $ClientBody);

                //check email type
                $EmailType = get_option('emailtype');
                $EmailDetails = unserialize(get_option( 'emaildetails'));
                //wp-email
                if($EmailType == "wpmail") {
                    $AdminEmail = $EmailDetails['wpemail'];
                    $Headers[] = "From: Admin <".$AdminEmail.">";
                    //send wp email to client
                    wp_mail( $ClientEmail, $ClientSubject, $ClientBody, $Headers, $Attachments);
                    //send wp email to admin
                    wp_mail( $AdminEmail, $AdminSubject, $AdminBody, $Headers, $Attachments);
                }

                //php-email
                if($EmailType == "phpmail") {
                    $AdminEmail = $EmailDetails['phpemail'];
                    $Headers[] = "From: Admin <".$AdminEmail.">";
                    ///send php email to client
                    mail($ClientEmail, $ClientSubject, $ClientBody, $Headers);
                    //send php email to admin
                    mail( $AdminEmail, $AdminSubject, $AdminBody, $Headers);
                }

                //wp-email
                if($EmailType == "smtp") {
                    require_once('menu-pages/notification/Email.php');
                    $AdminEmail     = $EmailDetails['smtpemail'];
                    $HostName       = $EmailDetails['hostname'];
                    $PortNo         = $EmailDetails['portno'];
                    $SMTPEmail      = $EmailDetails['smtpemail'];
                    $Password       = $EmailDetails['password'];
                    $Headers[] = "From: Admin <".$AdminEmail.">";
                    $Email = new SendEmail();
                    //send smtp email to client
                    $Email->NotifyClient($HostName, $PortNo, $SMTPEmail, $Password, $AdminEmail, $ClientEmail, $ClientSubject, $ClientBody, $BlogName);
                    //send smtp email to admin
                    $Email->NotifyAdmin($HostName, $PortNo, $SMTPEmail, $Password, $AdminEmail, $AdminSubject, $AdminBody, $BlogName);
                }
            } //end of notification enable check if
        } // end og SQL if
    } //end of isset ?>

    <script type='text/javascript'>
    function move(m, y){
            var f = document.calendarform;
            f.m.value = m;
            f.y.value = y;

            this.loading();
            f.submit();

    }

    jQuery(document).ready(function() {

        jQuery('#calendar').fullCalendar({

            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
           viewDisplay: function getDate(date) {
                var lammCurrentDate = new Date();
                var lammMinDate = new Date(lammCurrentDate.getFullYear(), lammCurrentDate.getMonth(), 1, 0, 0, 0, 0);

                if (date.start <= lammMinDate) {
                    $(".fc-button-prev").css("display", "none");
                }
                else {
                    $(".fc-button-prev").css("display", "inline-block");
                }
            },
            eventRender: function (event, element , view){
                var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
                //alert(today);
                // $(element).hide();
                // $('.fc-day [data-date="' + getEventDate(event) + '"]').addClass("orange");
                // console.log(view);
                    // alert(event.start);
                    // // console.log(event.start);
                    //  alert(moment(today));
                $('.fc-widget-content').each(function(){
                    if($(this).is('.fc-past')){ 
                      $(this).addClass('fc-other-month');
                    }
                   });
                if (event.start <  moment(today)){
                   

                 
                    element.hide();
                  }
                if(view.name =="undefined" ||view.name == "month"){
                    $(element).find(".fc-event-inner").remove();
                    $(".fc-event.fc-event-hori.fc-event-start.fc-event-end").css("display","none");
                }
                else if (view.name =="agendaWeek"){
                    $(".fc-event.fc-event-vert.fc-event-start.fc-event-end ").css("min-height" ,"30px !important");
                }
                
                 // $(element).find(".fc-event-inner").remove();

               
            },
            dayClick: function(date, jsEvent, view) {
                var todaydate = jQuery.fullCalendar.formatDate(date,'dd-MM-yyyy');
                jQuery('#appdate').val(todaydate);
               // jQuery('#AppFirstModal').show();

            },

            // eventAfterRender: function (event, element, view) {
            //     var dataHoje = new Date();
            //     if (event.start < dataHoje && event.end > dataHoje) {
            //         //event.color = "#FFB347"; //Em andamento
            //         $(element).parent().css('background-color', '#FFB347');
            //     } else if (event.start < dataHoje && event.end < dataHoje) {
            //         //event.color = "#77DD77"; //Concluído OK
            //         $(element).parent().css('background-color', '#77DD77');
            //     } else if (event.start > dataHoje && event.end > dataHoje) {
            //         //event.color = "#AEC6CF"; //Não iniciado
            //         $)element.parent().css('background-color', '#AEC6CF');
            //     }
            // },


            
            titleFormat: {
                month: ' MMMM yyyy',                                // September 2009
                week: "MMM d[ yyyy]{ '&#8212;'[ MMM] d yyyy}",      // Sep 7 - 13 2009
                day: 'dddd, MMM d, yyyy'                            // Tuesday, Sep 8, 2009
            },
           
            editable: false,
            weekends: true,
            timeFormat: 'h:mm{-h:mmtt }',
            axisFormat: 'h:mm{-h:mmtt }',
            <?php $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings')); ?>
            firstDay: <?php if($AllCalendarSettings['calendar_start_day'] != '') echo $AllCalendarSettings['calendar_start_day']; else echo "1"; ?>,
            slotMinutes: <?php if($AllCalendarSettings['calendar_slot_time'] != '') echo $AllCalendarSettings['calendar_slot_time']; else echo "15"; ?>,
            defaultView: '<?php if($AllCalendarSettings['calendar_view'] != '') echo $AllCalendarSettings['calendar_view']; else echo "month"; ?>',
            minTime: <?php if($AllCalendarSettings['day_start_time'] != '') echo date("G", strtotime($AllCalendarSettings['day_start_time'])); else echo "8"; ?>,

            maxTime: <?php  if($AllCalendarSettings['day_end_time'] != '') echo date("G", strtotime($AllCalendarSettings['day_end_time'])); else echo "20"; ?>,
            monthNames: ["<?php _e("January", "comptoneye"); ?>","<?php _e("February", "comptoneye"); ?>","<?php _e("March", "comptoneye"); ?>","<?php _e("April", "comptoneye"); ?>","<?php _e("May", "comptoneye"); ?>","<?php _e("June", "comptoneye"); ?>","<?php _e("July", "comptoneye"); ?>", "<?php _e("August", "comptoneye"); ?>", "<?php _e("September", "comptoneye"); ?>", "<?php _e("October", "comptoneye"); ?>", "<?php _e("November", "comptoneye"); ?>", "<?php _e("December", "comptoneye"); ?>" ],
            monthNamesShort: ["<?php _e("Jan", "comptoneye"); ?>","<?php _e("Feb", "comptoneye"); ?>","<?php _e("Mar", "comptoneye"); ?>","<?php _e("Apr", "comptoneye"); ?>","<?php _e("May", "comptoneye"); ?>","<?php _e("Jun", "comptoneye"); ?>","<?php _e("Jul", "comptoneye"); ?>","<?php _e("Aug", "comptoneye"); ?>","<?php _e("Sept", "comptoneye"); ?>","<?php _e("Oct", "comptoneye"); ?>","<?php _e("Nov", "comptoneye"); ?>","<?php _e("Dec", "comptoneye"); ?>"],
            dayNames: ["<?php _e("Sunday", "comptoneye"); ?>","<?php _e("Monday", "comptoneye"); ?>","<?php _e("Tuesday", "comptoneye"); ?>","<?php _e("Wednesday", "comptoneye"); ?>","<?php _e("Thursday", "comptoneye"); ?>","<?php _e("Friday", "comptoneye"); ?>","<?php _e("Saturday", "comptoneye"); ?>"],
            dayNamesShort: ["<?php _e("Sun", "comptoneye"); ?>","<?php _e("Mon", "comptoneye"); ?>", "<?php _e("Tue", "comptoneye"); ?>", "<?php _e("Wed", "comptoneye"); ?>", "<?php _e("Thus", "comptoneye"); ?>", "<?php _e("Fri", "comptoneye"); ?>", "<?php _e("Sat", "comptoneye"); ?>"],
            buttonText: {
                today: "<?php _e("Today", "comptoneye"); ?>",
                day: "<?php _e("Day", "comptoneye"); ?>",
                week:"<?php _e("Week", "comptoneye"); ?>",
                month:"<?php _e("Month", "comptoneye"); ?>"
            },
            handleWindowResize: true,
            //defaultView: 'agendaWeek', // Only show week view
            //header: false, // Hide buttons/titles
            // minTime: '07:30:00', // Start time for the calendar
            // maxTime: '22:00:00', // End time for the calendar
            columnFormat: {
                week: 'ddd' // Only show day of the week names
            },
            displayEventTime: true, // Display event time
            
            selectable: true,
            selectHelper: false,
            buttonText: {
            prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
            next: "<span class='fc-text-arrow'>&rsaquo;</span>",
            prevYear: "<span class='fc-text-arrow'>&raquo;</span>",
            nextYear: "<span class='fc-text-arrow'>&laquo;</span>"
            },
            buttonIcons: {
                prev: 'circle-triangle-e',
                next: 'circle-triangle-w'
            },
            // dayRender: function (date, cell) {
            //     console.log(date);
                
            //     jQuery.ajax({
            //         type: 'GET',
            //         url : ajaxurl,
            //         cache: false,
                
            //         data :{
            //             day: "something",
            //             action: "seeDay_and_know"
            //         },
            //         complete : function() {
            //          //    console.log("failure");
            //          // alert("hello world"); 
            //      },
            //         success: function(data) {
            //             // console.log("success");
            //             // alert("hhahha");
            //             console.log(data);
            //             var sdata= data.$
            //             console.log(data);
            //             // if(data=="0"){
            //             //     cell.css("background-color" , "blue");
            //             // }
            //         }
            //      } );    
            // },

            select: function(start, end, allDay) {
                $("#error").hide();
                var check = $.fullCalendar.formatDate(start,'yyyy-MM-dd');
                var today = $.fullCalendar.formatDate(new Date(),'yyyy-MM-dd');
                if(check < today)
                {
                    $("#error").show();
                    $("#error").html("Past date appointment not available");
                }
                else
                {
                    // Its a right date
                            // Do something
                    jQuery('#AppFirstModal').show();

                }
            },
            

            events: [
                <?php 
                //Loading Appointments On Calendar Start
                global $wpdb;
                $AppointmentTableName = $wpdb->prefix . "ap_appointments";
                $AllAppointments = $wpdb->get_results( $wpdb->prepare("select name, start_time, end_time, date FROM $AppointmentTableName where id > %d",null), OBJECT);
                
                if($AllAppointments) {
                    foreach($AllAppointments as $single) {
                        $title = $single->name;
                        $start = date("H, i", strtotime($single->start_time));
                        $end= date("H, i", strtotime($single->end_time));

                        // subtract 1 from month digit coz calendar work on month 0-11
                        $y = date ( 'Y' , strtotime( $single->date ) );
                        $m = date ( 'n' , strtotime( $single->date ) ) - 1;
                        $d = date ( 'd' , strtotime( $single->date ) );
                        $date = "$y-$m-$d";

                        $date = str_replace("-",", ", $date); ?>
                        {
                            title: "<?php _e("Booked", "comptoneye"); ?>",
                            start: new Date(<?php echo "$date, $start"; ?>),
                            end: new Date(<?php echo "$date, $end"; ?>),
                            allDay: false,
                            backgroundColor : "#1FCB4A",
                            textColor: "black",
                        }, <?php
                    }
                }

                
                // Loading Events On Calendar Start
                global $wpdb;
                $EventTableName = $wpdb->prefix . "ap_events";
                $AllEvents = $wpdb->get_results( $wpdb->prepare("select `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` where `repeat` = %s",'N') , OBJECT );
                
                if($AllEvents) {
                    foreach($AllEvents as $Event) {
                        // convert time foramt H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        // change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);

                        $startdate = $Event->start_date;
                        // subtract 1 from $startdate month digit coz calendar work on month 0-11
                        $y = date ( 'Y' , strtotime( $startdate ) );
                        $m = date ( 'n' , strtotime( $startdate ) ) - 1;
                        $d = date ( 'd' , strtotime( $startdate ) );
                        $startdate = "$y-$m-$d";
                        $startdate = str_replace("-",", ", $startdate);     //changing date format

                        $enddate = $Event->end_date;
                        // subtract 1 from $startdate month digit coz calendar work on month 0-11
                        $y2 = date ( 'Y' , strtotime( $enddate ) );
                        $m2 = date ( 'n' , strtotime( $enddate ) ) - 1;
                        $d2 = date ( 'd' , strtotime( $enddate ) );
                        $enddate = "$y2-$m2-$d2";
                        $enddate = str_replace("-",", ", $enddate);         //changing date format ?>
                        {
                            title: "<?php echo $Event->name; ?>",
                            start: new Date(<?php echo "$startdate, $starttime"; ?>),
                            end: new Date(<?php echo "$enddate, $endtime"; ?>),
                            allDay: false,
                            backgroundColor : "#FF7575",
                            textColor: "black"
                        }, <?php
                    }
                }

                //Loading Recurring Events On Calendar Start
                $AllREvents = $wpdb->get_results($wpdb->prepare("select `id`, `name`, `start_time`, `end_time`, `start_date`, `end_date`, `repeat` FROM `$EventTableName` where `repeat` != %s",'N'), OBJECT);
                
                //dont show event on filtering
                if(isset($AllREvents)) {
                    foreach($AllREvents as $Event) {
                        //convert time foramt H:i:s
                        $starttime = date("H:i", strtotime($Event->start_time));
                        $endtime = date("H:i", strtotime($Event->end_time));
                        //change time format according to calendar
                        $starttime = str_replace(":",", ", $starttime);
                        $endtime = str_replace(":", ", ", $endtime);
                        $startdate = $Event->start_date;
                        $enddate = $Event->end_date;

                        if($Event->repeat != 'M') {
                            //if appointment type then calulate RTC(recutting date calulation)
                            if($Event->repeat == 'PD')
                            $RDC = 1;
                            if($Event->repeat == 'D')
                            $RDC = 1;
                            if($Event->repeat == 'W')
                            $RDC = 7;
                            if($Event->repeat == 'BW')
                            $RDC = 14;

                            $Alldates = array();
                            $st_dateTS = strtotime($startdate);
                            $ed_dateTS = strtotime($enddate);
                            for ($currentDateTS = $st_dateTS; $currentDateTS <= $ed_dateTS; $currentDateTS += (60 * 60 * 24 * $RDC)) {
                                $currentDateStr = date("Y-m-d",$currentDateTS);
                                $AlldatesArr[] = $currentDateStr;

                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y = date ( 'Y' , strtotime( $currentDateStr ) );
                                $m = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                                $d = date ( 'd' , strtotime( $currentDateStr ) );
                                $startdate = "$y-$m-$d";
                                $startdate = str_replace("-",", ", $startdate);     //changing date format

                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y2 = date ( 'Y' , strtotime( $currentDateStr ) );
                                $m2 = date ( 'n' , strtotime( $currentDateStr ) ) - 1;
                                $d2 = date ( 'd' , strtotime( $currentDateStr ) );
                                $enddate = "$y2-$m2-$d2";
                                //changing date format
                                $enddate = str_replace("-",", ", $enddate); ?>
                                {
                                    title: "<?php echo ucwords($Event->name); ?>",
                                    start: new Date(<?php echo "$startdate, $starttime"; ?>),
                                    end: new Date(<?php echo "$enddate, $endtime"; ?>),
                                    allDay: false,
                                    backgroundColor : "#FF7575",
                                    textColor: "black",
                                }, <?php
                            }// end of for
                        } else {
                            $i = 0;
                            do {
                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($startdate)));
                                // subtract 1 from $startdate month digit coz calendar work on month 0-11
                                $y = date ( 'Y' , strtotime( $NextDate ) );
                                $m = date ( 'n' , strtotime( $NextDate ) ) - 1;
                                $d = date ( 'd' , strtotime( $NextDate ) );
                                $startdate2 = "$y-$m-$d";
                                $startdate2 = str_replace("-",", ", $startdate2);       //changing date format
                                $enddate2 = str_replace("-",", ", $startdate2); ?>
                                {
                                    title: "<?php echo ucwords($Event->name); ?>",
                                    start: new Date(<?php echo "$startdate2, $starttime"; ?>),
                                    end: new Date(<?php echo "$enddate2, $endtime"; ?>),
                                    allDay: false,
                                    backgroundColor : "#FF7575",
                                    textColor: "black",
                                }, <?php
                                $i = $i+1;
                            } while(strtotime($enddate) != strtotime($NextDate));
                        }//end of else
                    }//end of foreach
                }// end of all-events ?>
                        {
                        }
            ],

         
        });
   <!-- $('#calendar').fullCalendar( 'removeEvents', function(e){ return !e.isUserCreated}); -->
    
<!-- $('.fc-event').css( "display", "none" ); -->
        
            //Modal Form Works - show frist modal
        
        jQuery('#addappointment').click(function(){
            var todaydate = jQuery.fullCalendar.formatDate(new Date(),'dd-MM-yyyy');
            jQuery('#appdate').val(todaydate);
            jQuery('#AppFirstModal').show();
        });
        jQuery('#appdate').click(function(){
            $('#daterror').html(' Click on calendar for date ');
        });
       

        

        //hide modal
        jQuery('#close').click(function(){
            jQuery('#AppFirstModal').hide();
        });
        //jQuery('#next1').hide();   // hide next button
        jQuery('#firsttimesloatbox').hide();
        
        jQuery('#seetime').click(function(){
            jQuery(".apcal-error").hide();
            if(jQuery('#service').val() == 0 ) {
                jQuery("#service").after("<span class='apcal-error'><br><strong><?php _e("Select any service.", "comptoneye"); ?></strong></span>");
                return false;
            }
            if(jQuery('#appdate').val() == 0) {
                jQuery("#appdate").after("<span class='apcal-error'><br><strong><?php _e("Select date from calendar", "comptoneye"); ?></strong></span>");
                return false;
            }
            var ServiceId =  jQuery('#service').val();
            var AppDate =  jQuery('#appdate').val();
            var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate;
            jQuery('#loading1').show(); // loading button onclick next1 at first modal
            jQuery.ajax({
            dataType : 'html',
            type: 'GET',
            url : location.href,
            cache: false,
            data : SecondData,
            complete : function() {  },
            success: function(data) {
                    data = jQuery(data).find('#firsttimesloatbox');
                    jQuery('#loading1').hide();
                   // jQuery('#AppFirstModal').hide();
                   // jQuery('#AppSecondModalDiv').show();
                    //jQuery('#seetime').hide();
                    jQuery('#firsttimesloatbox').show();
                    jQuery('#firsttimesloatbox').html(data);
                    
                    //jQuery('#next1').show();    // show next button
                }
            });
        });
            

        
        //AppFirstModal Validation
        jQuery('#next1').click(function(){          
            var ServiceId =  jQuery('#service').val();
            var AppDate =  jQuery('#appdate').val();
            var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate;
            jQuery('#loading1').show(); // loading button onclick next1 at first modal
            jQuery('#next1').hide();    // hide next button
            jQuery.ajax({
            dataType : 'html',
            type: 'GET',
            url : location.href,
            cache: false,
            data : SecondData,
            complete : function() {  },
            success: function(data) {
                    data = jQuery(data).find('div#AppSecondModal');
                    jQuery('#loading1').hide();
                    jQuery('#AppFirstModal').hide();
                    jQuery('#AppSecondModalDiv').show();
                    jQuery('#AppSecondModalDiv').html(data);
                }
            });
        });

        //Second Modal form validation
        jQuery('#booknowapp').click(function(){
            jQuery(".apcal-error").hide();
            var start_time = jQuery('input[name=start_time]:radio:checked').val();
            if(!start_time) {
                jQuery("#selecttimediv").after("<span class='apcal-error'><br><strong><?php _e("Select any time.", "comptoneye"); ?></strong></span>");
                return false;
            }

            if( !jQuery('#clientname').val() ) {
                jQuery("#clientname").after("<span class='apcal-error'><br><strong><?php _e("Name required.", "comptoneye"); ?></strong></span>");
                return false;
            } else if(!isNaN( jQuery('#clientname').val() )) {
                jQuery("#clientname").after("<span class='apcal-error'><p><strong><?php _e("Invalid name.", "comptoneye"); ?></strong></p></span>");
                return false;
            }

            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if( !jQuery('#clientemail').val() ) {
                jQuery("#clientemail").after("<span class='apcal-error'><br><strong><?php _e("Email required.", "comptoneye"); ?></strong></span>");
                return false;
            } else {
                if(regex.test(jQuery('#clientemail').val()) == false ) {
                    jQuery("#clientemail").after("<span class='apcal-error'><p><strong><?php _e("Invalid Email.", "comptoneye"); ?></strong></p></span>");
                    return false;
                }
            }

            if( !jQuery('#clientphone').val() ) {
                jQuery("#clientphone").after("<span class='apcal-error'><br><strong><?php _e("Phone required.", "comptoneye"); ?></strong></span>");
                return false;
            } else if(isNaN( jQuery('#clientphone').val() )) {
                jQuery("#clientphone").after("<span class='apcal-error'><p><strong><?php _e("Invalid phone number.", "comptoneye"); ?></strong></p></span>");
                return false;
            }
        });

        //back button show first modal
        jQuery('#back').click(function(){
            jQuery('#AppFirstModal').show();
            jQuery('#AppSecondModal').hide();
        });

        $('#firdiv #datepicker').datepicker({
                minDate: 0,
                altField: '#alternate',
                firstDay: <?php if($AllCalendarSettings['calendar_start_day']) echo $AllCalendarSettings['calendar_start_day']; else echo "0";  ?>,
               onSelect: function(dateText, inst) {
                var dateAsString = dateText;
                var seleteddate = jQuery.datepicker.formatDate('dd-mm-yy', new Date(dateAsString));
                document.addnewappointment.appdate.value = seleteddate;
            }
        }); 
});

    

    //Modal Form Works
    function Backbutton() {
        jQuery('#AppFirstModal').show();
        jQuery('#AppSecondModalDiv').hide();
        jQuery('#next1').show();
    }

    //validation on second modal form submissions == appointment_register_nonce_field
    function CheckValidation() {
        jQuery(".apcal-error").hide();
        var start_time = jQuery('input[name=start_time]:radio:checked').val();
        if(!start_time) {
            jQuery("#selecttimediv").after("<p style='width:350px; padding:2px;' class='apcal-error'><strong><?php _e("Select any time.", "comptoneye"); ?></strong></p>");
            return false;
        }

        if( !jQuery('#clientname').val() ) {
            jQuery("#clientname").after("<span class='apcal-error'><br><strong><?php _e("Name required.", "comptoneye"); ?></strong></span>");
            return false;
        } else if(!isNaN( jQuery('#clientname').val() )) {
            jQuery("#clientname").after("<span class='apcal-error'><br><strong><?php _e("Invalid Name", "comptoneye"); ?></strong></span>");
            return false;
        }

        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if( !jQuery('#clientemail').val() ) {
            jQuery("#clientemail").after("<span class='apcal-error'><br><strong><?php _e("Email required.", "comptoneye"); ?></strong></span>");
            return false;
        } else {
            if(regex.test(jQuery('#clientemail').val()) == false ) {
                jQuery("#clientemail").after("<span class='apcal-error'><br><strong><?php _e("Invalid Email", "comptoneye"); ?></strong></span>");
                return false;
            }
        }

        if( !jQuery('#clientphone').val() ) {
            jQuery("#clientphone").after("<span class='apcal-error'><br><strong><?php _e("Phone required.", "comptoneye"); ?></strong></span>");
            return false;
        } else if(isNaN( jQuery('#clientphone').val() )) {
            jQuery("#clientphone").after("<span class='apcal-error'><br><strong><?php _e("Invalid phone number.", "comptoneye"); ?></strong></span>");
            return false;
        }
        
        var wp_nonce = jQuery('#appointment_register_nonce_field').val();
         
        var ServiceId = jQuery('#serviceid').val();
        var AppDate = jQuery('#appointmentdate').val();
        var  ServiceDuration =  jQuery('#serviceduration').val();
        var StartTime = jQuery('input[name=start_time]:radio:checked').val();
        var Client_Name =  jQuery('#clientname').val();
        var Client_Email =  jQuery('#clientemail').val();
        var Client_Phone =  jQuery('#clientphone').val();
        var Client_Note =  jQuery('#clientnote').val();
        var currenturl = jQuery(location).attr('href');
        var SecondData = "ServiceId=" + ServiceId + "&AppDate=" + AppDate + "&StartTime=" + StartTime + '&Client_Name=' + Client_Name +'&Client_Email=' + Client_Email +'&Client_Phone=' + Client_Phone +'&Client_Note=' + Client_Note+'&Service_Duration=' + ServiceDuration + '&wp_nonce=' + wp_nonce;
        var currenturl = jQuery(location).attr('href');
        var url = currenturl;
        jQuery('#loading2').show();     // loading button onclick next1 at first modal
        jQuery('#buttonbox').hide();    // loading button onclick book now at first modal
        jQuery.ajax({
            dataType : 'html',
            type: 'POST',
            url : url,
            cache: false,
            data : SecondData,
            complete : function() {  },
            success: function() {
                
               
                $("#submissioninfo").html("<?php _e("Thank you for scheduling appointment with us. A confirmation mail will be forward to you soon after admin approval.", "comptoneye"); ?>");

                jQuery('#AppSecondModalDiv').delay( 111800 ).hide();
                var currenturl = jQuery(location).attr('href');
                var url = currenturl.replace("#","");
                window.location = url;
            }
        });
    }
    </script>
    <style type='text/css'>
    .apcal-error{
        color: #FF0000;
    }
    .ui-datepicker-inline.ui-datepicker.ui-widget.ui-widget-content.ui-helper-clearfix.ui-corner-all {
    max-width: 300px;
    }

    button#next1 {
        float: right;
    }
    .ui-datepicker {
    
    font-size: 16px;
}
.ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span {
            background-image: url("<?php echo get_template_directory_uri()?>/images/ui-icons_d8e7f3_256x240.png");
        }
       a#back, button#booknowapp {
    margin-top: 40px;
}
.apcal_modal{
    width: 600px;
  
}
div#secdiv {
    margin-left: 40px;
}
button#seetime , button#next1{
    margin-top: 70px;
}
p.apcal_alert.apcal_alert-error {
    margin-top: -20px;
    margin-bottom: 30px;
}
.timeavai {
    text-align: center;
    margin-bottom: 10px;
}
.apcal_modal-body {
    max-height: 500px;
    /* padding: 15px; */
    overflow-y: auto;
}
.orange{
    color:orange;
}
.fc-event.fc-event-hori.fc-event-start.fc-event-end {
    border-radius: 10px;
}
.fc-state-default {
   border-color: #5bc0de;
    color: #5bc0de;
}
 .fc-state-active {
    background-color: #608000;
    background-image: none;
    outline: 0;
    color: #FFFFFF;
}
/*re*/
.fc-text-arrow {
    /* margin: 0 .4em; */
    font-size: 3em;
    line-height: 16px;
    /* vertical-align: baseline; */
    color: #2e6da4;
}
.ui-datepicker-header.ui-widget-header.ui-helper-clearfix.ui-corner-all {
    background: #57b157;
}
a.ui-state-default {
    color: green !important;
}

.apcal_modal-body {
    text-align: center;
}
table#bordercssremove {
    margin: 80px;
}
button#booknowapp {
    margin-left: 50px;
}

/*table.fc-header  {
    background-color: #d9edf7;
}*/
table.fc-header {
    background-color: #57b157;
}
thead tr.fc-first {
    background:transparent;
}


.fc-border-separate tr.fc-last th{
    border-color: #5bc0de;
    border-radius: 2px;
}
.ui-datepicker-unselectable .ui-state-default {
    background: #f4f4f4;
    color: #0bad0b;
}
/*Changes made today heheh :) */
.fc-widget-content {
    border: 1px solid #5bc0de;
}
.fc-sat ,.fc-sat.fc-other-month {
        color: #004208 !important;
}
.fc-sat.fc-other-month.fc-past{
    color:#b8b8b8;
}
.fc-sun{
    color: #670f0f;
}

.fc-header-left .fc-button.fc-state-default {
    background: transparent;
    border:none;
}
.fc-header-left .fc-button.fc-state-hover , .fc-header-right .fc-button.fc-state-hover{
    background:#51A551;
    color:white;
}
.fc-header-left .fc-button.fc-state-down , .fc-header-right .fc-button.fc-state-down{
   background: rgba(206, 109, 101, 0.84);
}
.ui-datepicker-header {
    /* background: url(../img/dark_leather.png) repeat 0 0 #000; */
    color: #fff;
    font-weight: bold;
    text-shadow: 1px -1px 0px #000;
    
}
.apcal_alert-info {
    color: #3a87ad;
    
}
.apcal_alert.apcal_alert-info.modal-title ,.apcal_alert.apcal_alert-info{
    text-align: center;
  
}
.apcal_alert.apcal_alert-info.modal-title {
      font-size: 20px;
    color: #D9853B;
    background-color: #dddddd;
    border-color: #dddddd;
}
/*table.fc-header {
    margin-top: -25px;
}*/
.fc-header-title h2 {
    font-size: 23px;
}
div#error {
    color: red;
    text-align: center;
}
div#daterror {
    color: red;
    padding-bottom: 10px;
}
.fc-day.fc-past{

}

    </style>

   

    <!---Schedule New New Appointment Button-->
  <!--   <div id="bkbtndiv" align="center" style="padding:5px;">
        <button name="addappointment" class="apcal_btn apcal_btn-primary apcal_btn-large" type="submit" id="addappointment">
            <strong></strong><i class="icon-calendar icon-white"></i> -->
                <?php //if($AllCalendarSettings['booking_button_text']) {
                   // echo $AllCalendarSettings['booking_button_text'];
                //} else {
                  //  echo _e("Schedule New Appointment", "comptoneye");
                //} ?>
          <!--  </strong>
        </button>
    </div> -->

    <!---Show Comptoneye Appointment-->
    <div id="error"> </div>
    <div id='calendar'>
        
        <!---AppSecondModal For Schedule New Appointment-->
        <div id="AppSecondModalDiv" style="display:none;"></div>
    </div>


    <!---AppFirstModal For Schedule New Appointment-->
    <div id="AppFirstModal" style="display:none">
        <div class="apcal_modal" id="myModal" style="z-index:99999;">
            <form action="" method="post" name="addnewappointment" id="addnewappointment" >
                <div class="apcal_modal-info">
                    <div class="apcal_alert apcal_alert-info modal-title ">
                        <div><a href="#" style="float:right; margin-right:0px;" id="close"><i class="icon-remove"></i></a>
                        </div>
                        <p><strong><?php _e("Schedule New Appointment", "comptoneye"); ?></strong></p>
                        <div><?php _e("Select Date & Service", "comptoneye"); ?></div>
                    </div>
                </div>

                <div class="apcal_modal-body">
                       
                        
                     <div id="firsttimesloatbox" class="apcal_alert apcal_alert-block" style="height:auto; background: none ; padding: 0px; margin:0px; width:100%;border:none">
                            <!---slots time calculation-->
                            <?php
                            // time-slots calculation
                
                            $ServiceId =  intval( $_GET["ServiceId"] );
                            $ServiceTableName = $wpdb->prefix . "ap_services";
                            $ServiceData = $wpdb->get_row($wpdb->prepare("SELECT `name`, `duration` FROM `$ServiceTableName` WHERE `id` = %d",$ServiceId), OBJECT);
                            
                            $ServiceDuration = $ServiceData->duration;

                            $AppointmentDate = date("Y-m-d", strtotime($_GET['AppDate'])); //assign selected date by user
                            $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
                            $Biz_start_time = $AllCalendarSettings['day_start_time'];
                            $Biz_end_time = $AllCalendarSettings['day_end_time'];
                            if(isset($AllCalendarSettings['booking_time_slot'])) {
                                $UserDefineTimeSlot = $AllCalendarSettings['booking_time_slot'];
                            } else {
                                $UserDefineTimeSlot = $ServiceDuration;
                            }
                            $AllSlotTimesList = array();
                            $Enable = array();
                            $AppPreviousTimes = array();
                            $AppNextTimes = array();
                            $AppBetweenTimes = array();
                            $EventPreviousTimes = array();
                            $EventBetweenTimes = array();
                            $DisableSlotsTimes = array();
                            $BusinessEndCheck =array();
                            $AllSlotTimesList_User = array();
                            $TodaysAllDayEvent = 0;
                            
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


                            if($TodaysAllDayEvent) { ?>
                                <div class='apcal_alert apcal_alert-error'><?php _e("Sorry! No time available today.", "comptoneye"); ?></div>
                                <a class="apcal_btn" id="back" onclick="return Backbutton()"><i class="icon-arrow-left"></i> <?php _e("Back", "comptoneye"); ?></a><?php
                            } else {
                                echo "<div class='apcal_alert apcal_alert-info' id='avtime' align='center'>". __("Available Time For", "comptoneye") ." <strong>'$ServiceData->name'</strong> ". __("On", "comptoneye"). " <strong>'".date("d-m-Y", strtotime($AppointmentDate))."'</strong></div>";

                                //Calculate all time slots according to today's biz hours
                                $start = strtotime($Biz_start_time);
                                $end = strtotime($Biz_end_time);

                                if($UserDefineTimeSlot) {
                                    $UserTimeSlot = $UserDefineTimeSlot;
                                } else {
                                    $UserTimeSlot = 30;
                                }
                                for( $i = $start; $i < $end; $i += (60*$UserTimeSlot)) {
                                    $AllSlotTimesList_User[] = date('h:i A', $i);
                                }
                                // Business end check
                                $Business_end = strtotime($Biz_end_time);
                                $ServiceDuration_Biss= $ServiceDuration-5;
                                $ServiceDuration_Biss = $ServiceDuration_Biss *60;
                                $EndStartTime = $Business_end - $ServiceDuration_Biss;
                                for( $i = $EndStartTime; $i < $Business_end; $i += (60*5)) {
                                    $BusinessEndCheck[] = date('h:i A', $i);
                                }

                                // Create Business Time slot for calculation
                                for( $i = $start; $i < $end; $i += (60*5)) {
                                    $AllSlotTimesList[] = date('h:i A', $i);
                                }

                                //Fetch All today's appointments and calculate disable slots
                                $AppointmentTableName = $wpdb->prefix . "ap_appointments";
                                $AllAppointmentsData = $wpdb->get_results( $wpdb->prepare("SELECT `start_time`, `end_time` FROM `$AppointmentTableName` WHERE `date`= %s",$AppointmentDate) , OBJECT);
                                
                                if($AllAppointmentsData) {
                                    foreach($AllAppointmentsData as $Appointment) {
                                        $AppStartTimes[] = date('h:i A', strtotime( $Appointment->start_time ) );
                                        $AppEndTimes[] = date('h:i A', strtotime( $Appointment->end_time ) );

                                        //now calculate 5min slots between appointment's start_time & end_time
                                        $start_et = strtotime($Appointment->start_time);
                                        $end_et = strtotime($Appointment->end_time);
                                        //make 15-10=5min slot
                                        for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                            $AppBetweenTimes[] = date('h:i A', $i);
                                        }
                                    }

                                        //calculating  Next & Previous time of booked appointments
                                        foreach($AllSlotTimesList as $single) {
                                            if(in_array($single, $AppStartTimes)) {
                                                //get next time
                                                $time = $single;
                                                $event_length = $ServiceDuration-5;     // Service duration time    -  slot time
                                                $timestamp = strtotime("$time");
                                                $endtime = strtotime("+$event_length minutes", $timestamp);
                                                $next_time = date('h:i A', $endtime);
                                                //calculate next time
                                                $start = strtotime($single);
                                                $end = strtotime($next_time);
                                                //making 5min diffrance slot
                                                for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                    $AppNextTimes[] = date('h:i A', $i);
                                                }

                                                //calculate previous time
                                                $time1 = $single;
                                                $event_length1 = $ServiceDuration-5;    // 60min Service duration time - 15 slot time
                                                $timestamp1 = strtotime("$time1");
                                                $endtime1 = strtotime("-$event_length1 minutes", $timestamp1);
                                                $next_time1 = date('h:i A', $endtime1);
                                                $start1 = strtotime($next_time1);
                                                $end1 = strtotime($single);
                                                //making 5min diff slot
                                                for( $i = $start1; $i <= $end1; $i += (60*(5))) {
                                                    $AppPreviousTimes[] = date('h:i A', $i);
                                                }
                                            }
                                        }//end calculating Next & Previous time of booked appointments
                                } // end if $AllAppointmentsData

                                //Fetch All today's time-off and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` != 'W' AND `repeat` != 'BW' AND `repeat` != %s",'M'), OBJECT);
                                
                                if($AllEventsData)
                                {
                                    foreach($AllEventsData as $Event)
                                    {
                                        //calculate previous time (event start time to back service-duration-5)
                                        $minustime = $ServiceDuration - 5;
                                        $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                        $start = strtotime($start);
                                        $end =  $Event->start_time;
                                        $end = strtotime($end);
                                        for( $i = $start; $i <= $end; $i += (60*(5))) //making 5min difference slot
                                        {
                                            $EventPreviousTimes[] = date('h:i A', $i);
                                        }

                                        //calculating between time (start - end)
                                        $start_et = strtotime($Event->start_time);
                                        $end_et = strtotime($Event->end_time);
                                        for( $i = $start_et; $i < $end_et; $i += (60*(5))) //making 5min slot
                                        {
                                            $EventBetweenTimes[] = date('h:i A', $i);
                                        }
                                    }
                                }

                                //Fetch All 'WEEKLY' tim-eoff and calculate disable slots
                                $EventTableName = $wpdb->prefix . "ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'W'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;
                                        $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                                        $Current_Re_End_Date = strtotime($Current_Re_End_Date);
                                        //make weekly dates
                                        for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 7)) {
                                            $AllEventWeelylyDates[] = date('Y-m-d', $i);
                                        }
                                        if(in_array($AppointmentDate, $AllEventWeelylyDates)) {
                                            //calculate previous time (event start time to back service-duration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            //calculating between time (start - end)
                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                        unset($AllEventWeelylyDates);
                                    }
                                }

                                //Fetch All 'BI-WEEKLY' time-off and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'BW'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;

                                        $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                                        $Current_Re_End_Date = strtotime($Current_Re_End_Date);
                                        //make bi-weekly dates
                                        for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 14)) {
                                            $AllEventBiWeelylyDates[] = date('Y-m-d', $i);
                                        }
                                        if(in_array($AppointmentDate, $AllEventBiWeelylyDates)) {
                                            //calculate previous time (event start time to back ServiceDuration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            //calculating between time (start - end)
                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                    }
                                }

                                //Fetch All 'MONTHLY' timeoff and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'M'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;
                                        $i = 0;
                                        do {
                                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($Current_Re_Start_Date)));
                                                $AllEventMonthlyDates[] = $NextDate;
                                                $i = $i+1;
                                        } while(strtotime($Current_Re_End_Date) != strtotime($NextDate));

                                        if(in_array($AppointmentDate, $AllEventMonthlyDates)) {
                                            //calculate previous time (event start time to back service-duration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min difference slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                    }
                                }

                                $DisableSlotsTimes = array_merge($AppBetweenTimes, $AppPreviousTimes, $EventPreviousTimes, $EventBetweenTimes, $BusinessEndCheck);
                                unset($AppBetweenTimes);
                                unset($AppPreviousTimes);
                                unset($AppNextTimes);
                                unset($EventBetweenTimes);
                                unset($BusinessEndCheck);
                                // compare All Business Time slot with  with DisableSlotsTimes
                                foreach($AllSlotTimesList as $Single) {
                                    if(in_array($Single, $DisableSlotsTimes)) {
                                        $Disable[] = $Single;
                                    } else {
                                        $Enable[] = $Single;
                                    }
                                }// end foreach
                                echo '<div class="timeavai" >';
                                // Show All Enable Time Slot
                                foreach($AllSlotTimesList_User as $Single) {
                                    if(isset($Enable)) {
                                        if(in_array($Single, $Enable)) { ?>
                                            <!-- enable slots-->
                                            <div class=" btn-success" style="width:90px; padding:10px ;margin-right:10px; margin-bottom:10px; display:inline-block; border-radius:10px;">
                                            <?php echo esc_attr($Single); ?>
                                                
                                            </div><?php
                                        } else { ?>
                                            <!-- disable slots-->
                                            <!-- <div style="width:90px; float:left; padding:1px; display:inline-block;"> -->
                                                <!-- <input name="start_time" id="start_time"  disabled="disabled" type="radio"  value="<?php //echo esc_attr($Single); ?>"/>&nbsp;<del><?php //echo $Single; ?></del> -->
                                            <!-- </div> -->
                                            <?php
                                        }
                                    }// end of enable isset
                                }// end foreach
                                echo '</div>';
                                unset($DisableSlotsTimes);
                            } // end else ?><br />
                            <div id="selecttimediv"><!--display select time error --></div>
                        
                             <?php if(!$Enable && !$TodaysAllDayEvent ) { ?>
                            <p align=center class='apcal_alert apcal_alert-error' style='width:auto;'><strong><?php _e("Sorry! Today's all appointments has been booked.", "comptoneye"); ?></strong></p>
                            <style>
                            #avtime{
                                display:none;
                            }
                            </style>
                            
                           <?php
                        } ?>
                        </div>

                    <div id="firdiv" style="float:left;">
                      <div id="datepicker"></div>

                        
                    </div>

                    <div id="secdiv" style="float:left;">
                        <strong><?php _e("Your Appointment Date", "comptoneye"); ?>:</strong><br>
                        <div id="daterror"></div>
                        <input name="appdate" id="appdate" type="text" readonly="" height="30px;" style="height:30px;" />
                        
                        <?php 
                        global $wpdb;
                        $ServiceTable = $wpdb->prefix . "ap_services";
                        $AllService = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$ServiceTable` WHERE `availability` = %s",'yes'), OBJECT);  ?>
                        
                        <br /><br />
                        <strong><?php _e("Select Service", "comptoneye"); ?>:</strong><br />
                        <select name="service" id="service">
                            <option value="0"><?php _e("Select Service", "comptoneye"); ?></option>
                                <?php foreach($AllService as $Service) { ?>
                                    <?php if($AllCalendarSettings['show_service_cost'] == 'yes') $ShowCost = 1; else  $ShowCost = 0; ?>
                                    <?php if($AllCalendarSettings['show_service_duration'] == 'yes') $ShowDuration = 1; else  $ShowDuration = 0; ?>
                                    <option value="<?php echo esc_attr($Service->id); ?>">
                                        <?php echo ucwords($Service->name);
                                        if($ShowDuration || $ShowCost) echo " (";
                                        if($ShowDuration) { echo $Service->duration."min"; } if($ShowDuration && $ShowCost) echo "/";
                                        if($ShowCost) { echo "$". $Service->cost; }
                                        if($ShowDuration || $ShowCost) echo ")"; ?>
                                    </option>
                                <?php }?>
                        </select>
                        <br>

                      
                        <button name="seetime" id="seetime" type="button" class="btn btn-primary"
                            value="seetime"><?php _e("View time", "comptoneye"); ?> <i class="icon-arrow-up"></i></button>
                        <button name="next1" class="apcal_btn btn btn-success" type="button" id="next1" value="next1"><?php _e("Next", "comptoneye"); ?> <i class="icon-arrow-right"></i></button>
                        <div id="loading1" style="display:none;"><?php _e("Loading...", "comptoneye"); ?><img src="<?php echo plugins_url()."/appointment-calendar/images/loading.gif"; ?>" /></div>
                    
                    </div>

                </div>
            </form>
          </div>
    </div>
    <!---AppSecondModal For Schedule New Appointment-->
    
    <?php if( isset($_GET["ServiceId"]) && isset($_GET["AppDate"])) {  ?>
        <div id="AppSecondModal">
            <div class="apcal_modal" id="myModal" style="z-index:99999;">
                <form method="post" name="appointment-form2" id="appointment-form2" action="" onsubmit="return CheckValidation()">
                    <?php wp_nonce_field('appointment_register_nonce_check','appointment_register_nonce_field'); ?>
                    <div class="apcal_modal-info">
                      <div class="apcal_alert apcal_alert-info modal-title">
                            <a href="" style="float:right; margin-right:-4px;" id="close"><i class="icon-remove"></i></a>
                            <p><strong><?php _e("Schedule New Appointment", "comptoneye"); ?></strong></p>
                            <div><?php _e("Select Time & Fill Out Form", "comptoneye"); ?></div>
                        </div>
                    </div>

                    <div class="apcal_modal-body">
                        <div id="timesloatbox" class="apcal_alert apcal_alert-block" style="float:left; height:auto; width:100%;">
                            <!---slots time calculation-->
                            <?php
                            // time-slots calculation
                            global $wpdb;
                            $ServiceId =  intval( $_GET["ServiceId"] );
                            $ServiceTableName = $wpdb->prefix . "ap_services";
                            $ServiceData = $wpdb->get_row($wpdb->prepare("SELECT `name`, `duration` FROM `$ServiceTableName` WHERE `id` = %d",$ServiceId), OBJECT);
                            
                            $ServiceDuration = $ServiceData->duration;

                            $AppointmentDate = date("Y-m-d", strtotime($_GET['AppDate'])); //assign selected date by user
                            $AllCalendarSettings = unserialize(get_option('apcal_calendar_settings'));
                            $Biz_start_time = $AllCalendarSettings['day_start_time'];
                            $Biz_end_time = $AllCalendarSettings['day_end_time'];
                            if(isset($AllCalendarSettings['booking_time_slot'])) {
                                $UserDefineTimeSlot = $AllCalendarSettings['booking_time_slot'];
                            } else {
                                $UserDefineTimeSlot = $ServiceDuration;
                            }
                            $AllSlotTimesList = array();
                            $Enable = array();
                            $AppPreviousTimes = array();
                            $AppNextTimes = array();
                            $AppBetweenTimes = array();
                            $EventPreviousTimes = array();
                            $EventBetweenTimes = array();
                            $DisableSlotsTimes = array();
                            $BusinessEndCheck =array();
                            $AllSlotTimesList_User = array();
                            $TodaysAllDayEvent = 0;
                            
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


                            if($TodaysAllDayEvent) { ?>
                                <div class='apcal_alert apcal_alert-error'><?php _e("Sorry! No time available today.", "comptoneye"); ?></div>
                                <a class="apcal_btn" id="back" onclick="return Backbutton()"><i class="icon-arrow-left"></i> <?php _e("Back", "comptoneye"); ?></a><?php
                            } else {
                                echo "<div class='apcal_alert apcal_alert-info' id='avtime' align='center'>". __("Available Time For", "comptoneye") ." <strong>'$ServiceData->name'</strong> ". __("On", "comptoneye"). " <strong>'".date("d-m-Y", strtotime($AppointmentDate))."'</strong></div>";

                                //Calculate all time slots according to today's biz hours
                                $start = strtotime($Biz_start_time);
                                $end = strtotime($Biz_end_time);

                                if($UserDefineTimeSlot) {
                                    $UserTimeSlot = $UserDefineTimeSlot;
                                } else {
                                    $UserTimeSlot = 30;
                                }
                                for( $i = $start; $i < $end; $i += (60*$UserTimeSlot)) {
                                    $AllSlotTimesList_User[] = date('h:i A', $i);
                                }
                                // Business end check
                                $Business_end = strtotime($Biz_end_time);
                                $ServiceDuration_Biss= $ServiceDuration-5;
                                $ServiceDuration_Biss = $ServiceDuration_Biss *60;
                                $EndStartTime = $Business_end - $ServiceDuration_Biss;
                                for( $i = $EndStartTime; $i < $Business_end; $i += (60*5)) {
                                    $BusinessEndCheck[] = date('h:i A', $i);
                                }

                                // Create Business Time slot for calculation
                                for( $i = $start; $i < $end; $i += (60*5)) {
                                    $AllSlotTimesList[] = date('h:i A', $i);
                                }

                                //Fetch All today's appointments and calculate disable slots
                                $AppointmentTableName = $wpdb->prefix . "ap_appointments";
                                $AllAppointmentsData = $wpdb->get_results( $wpdb->prepare("SELECT `start_time`, `end_time` FROM `$AppointmentTableName` WHERE `date`= %s",$AppointmentDate) , OBJECT);
                                
                                if($AllAppointmentsData) {
                                    foreach($AllAppointmentsData as $Appointment) {
                                        $AppStartTimes[] = date('h:i A', strtotime( $Appointment->start_time ) );
                                        $AppEndTimes[] = date('h:i A', strtotime( $Appointment->end_time ) );

                                        //now calculate 5min slots between appointment's start_time & end_time
                                        $start_et = strtotime($Appointment->start_time);
                                        $end_et = strtotime($Appointment->end_time);
                                        //make 15-10=5min slot
                                        for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                            $AppBetweenTimes[] = date('h:i A', $i);
                                        }
                                    }

                                        //calculating  Next & Previous time of booked appointments
                                        foreach($AllSlotTimesList as $single) {
                                            if(in_array($single, $AppStartTimes)) {
                                                //get next time
                                                $time = $single;
                                                $event_length = $ServiceDuration-5;     // Service duration time    -  slot time
                                                $timestamp = strtotime("$time");
                                                $endtime = strtotime("+$event_length minutes", $timestamp);
                                                $next_time = date('h:i A', $endtime);
                                                //calculate next time
                                                $start = strtotime($single);
                                                $end = strtotime($next_time);
                                                //making 5min diffrance slot
                                                for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                    $AppNextTimes[] = date('h:i A', $i);
                                                }

                                                //calculate previous time
                                                $time1 = $single;
                                                $event_length1 = $ServiceDuration-5;    // 60min Service duration time - 15 slot time
                                                $timestamp1 = strtotime("$time1");
                                                $endtime1 = strtotime("-$event_length1 minutes", $timestamp1);
                                                $next_time1 = date('h:i A', $endtime1);
                                                $start1 = strtotime($next_time1);
                                                $end1 = strtotime($single);
                                                //making 5min diff slot
                                                for( $i = $start1; $i <= $end1; $i += (60*(5))) {
                                                    $AppPreviousTimes[] = date('h:i A', $i);
                                                }
                                            }
                                        }//end calculating Next & Previous time of booked appointments
                                } // end if $AllAppointmentsData

                                //Fetch All today's time-off and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` != 'W' AND `repeat` != 'BW' AND `repeat` != %s",'M'), OBJECT);
                                
                                if($AllEventsData)
                                {
                                    foreach($AllEventsData as $Event)
                                    {
                                        //calculate previous time (event start time to back service-duration-5)
                                        $minustime = $ServiceDuration - 5;
                                        $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                        $start = strtotime($start);
                                        $end =  $Event->start_time;
                                        $end = strtotime($end);
                                        for( $i = $start; $i <= $end; $i += (60*(5))) //making 5min difference slot
                                        {
                                            $EventPreviousTimes[] = date('h:i A', $i);
                                        }

                                        //calculating between time (start - end)
                                        $start_et = strtotime($Event->start_time);
                                        $end_et = strtotime($Event->end_time);
                                        for( $i = $start_et; $i < $end_et; $i += (60*(5))) //making 5min slot
                                        {
                                            $EventBetweenTimes[] = date('h:i A', $i);
                                        }
                                    }
                                }

                                //Fetch All 'WEEKLY' tim-eoff and calculate disable slots
                                $EventTableName = $wpdb->prefix . "ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'W'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;
                                        $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                                        $Current_Re_End_Date = strtotime($Current_Re_End_Date);
                                        //make weekly dates
                                        for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 7)) {
                                            $AllEventWeelylyDates[] = date('Y-m-d', $i);
                                        }
                                        if(in_array($AppointmentDate, $AllEventWeelylyDates)) {
                                            //calculate previous time (event start time to back service-duration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            //calculating between time (start - end)
                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                        unset($AllEventWeelylyDates);
                                    }
                                }

                                //Fetch All 'BI-WEEKLY' time-off and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'BW'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;

                                        $Current_Re_Start_Date = strtotime($Current_Re_Start_Date);
                                        $Current_Re_End_Date = strtotime($Current_Re_End_Date);
                                        //make bi-weekly dates
                                        for( $i = $Current_Re_Start_Date; $i <= $Current_Re_End_Date; $i += (60 * 60 * 24 * 14)) {
                                            $AllEventBiWeelylyDates[] = date('Y-m-d', $i);
                                        }
                                        if(in_array($AppointmentDate, $AllEventBiWeelylyDates)) {
                                            //calculate previous time (event start time to back ServiceDuration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            //calculating between time (start - end)
                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                    }
                                }

                                //Fetch All 'MONTHLY' timeoff and calculate disable slots
                                $EventTableName = $wpdb->prefix."ap_events";
                                $AllEventsData = $wpdb->get_results($wpdb->prepare("SELECT `start_time`, `end_time`, `start_date`, `end_date` FROM `$EventTableName` WHERE date('$AppointmentDate') between `start_date` AND `end_date` AND `allday` = '0' AND `repeat` = %s",'M'), OBJECT);
                                
                                if($AllEventsData) {
                                    foreach($AllEventsData as $Event) {
                                        //calculate all weekly dates between recurring_start_date - recurring_end_date
                                        $Current_Re_Start_Date = $Event->start_date;
                                        $Current_Re_End_Date = $Event->end_date;
                                        $i = 0;
                                        do {
                                                $NextDate = date("Y-m-d", strtotime("+$i months", strtotime($Current_Re_Start_Date)));
                                                $AllEventMonthlyDates[] = $NextDate;
                                                $i = $i+1;
                                        } while(strtotime($Current_Re_End_Date) != strtotime($NextDate));

                                        if(in_array($AppointmentDate, $AllEventMonthlyDates)) {
                                            //calculate previous time (event start time to back service-duration-5)
                                            $minustime = $ServiceDuration - 5;
                                            $start = date('h:i A', strtotime("-$minustime minutes", strtotime($Event->start_time)));
                                            $start = strtotime($start);
                                            $end =  $Event->start_time;
                                            $end = strtotime($end);
                                            //making 5min difference slot
                                            for( $i = $start; $i <= $end; $i += (60*(5))) {
                                                $EventPreviousTimes[] = date('h:i A', $i);
                                            }

                                            $start_et = strtotime($Event->start_time);
                                            $end_et = strtotime($Event->end_time);
                                            //making 5min difference slot
                                            for( $i = $start_et; $i < $end_et; $i += (60*(5))) {
                                                $EventBetweenTimes[] = date('h:i A', $i);
                                            }
                                        }
                                    }
                                }

                                $DisableSlotsTimes = array_merge($AppBetweenTimes, $AppPreviousTimes, $EventPreviousTimes, $EventBetweenTimes, $BusinessEndCheck);
                                unset($AppBetweenTimes);
                                unset($AppPreviousTimes);
                                unset($AppNextTimes);
                                unset($EventBetweenTimes);
                                unset($BusinessEndCheck);
                                // compare All Business Time slot with  with DisableSlotsTimes
                                foreach($AllSlotTimesList as $Single) {
                                    if(in_array($Single, $DisableSlotsTimes)) {
                                        $Disable[] = $Single;
                                    } else {
                                        $Enable[] = $Single;
                                    }
                                }// end foreach

                                // Show All Enable Time Slot
                                foreach($AllSlotTimesList_User as $Single) {
                                    if(isset($Enable)) {
                                        if(in_array($Single, $Enable)) { ?>
                                            <!-- enable slots-->
                                            <div style="width:120px; float:left; padding:1px; display:inline-block;font-size: 16px;">
                                                <input name="start_time" id="start_time" type="radio" value="<?php echo esc_attr($Single); ?>"/>&nbsp;&nbsp;<?php echo $Single; ?>
                                            </div><?php
                                        } else { ?>
                                            <!-- disable slots-->
                                            <div style="width:120px; float:left; padding:1px; display:inline-block;font-size: 16px;">
                                                <input name="start_time" id="start_time"  disabled="disabled" type="radio"  value="<?php echo esc_attr($Single); ?>"/>&nbsp;&nbsp;<del><?php echo $Single; ?></del>
                                            </div><?php
                                        }
                                    }// end of enable isset
                                }// end foreach
                                unset($DisableSlotsTimes);
                            } // end else ?><br />
                            <div id="selecttimediv"><!--display select time error --></div>
                        </div>

                        <?php if(!$Enable && !$TodaysAllDayEvent ) { ?>
                            <p align=center class='apcal_alert apcal_alert-error' style='width:auto;'><strong><?php _e("Sorry! Today's all appointments has been booked.", "comptoneye"); ?></strong></p>
                            <a class="apcal_btn apcal_btn-primary" id="back" onclick="Backbutton()"><i class="icon-arrow-left"></i> <?php _e("Back", "comptoneye"); ?></a><?php
                        } else if(!$TodaysAllDayEvent && $Enable) { ?>
                        <input type="hidden" name="serviceid" id="serviceid" value="<?php echo esc_attr($_GET['ServiceId']); ?>" />
                        <input type="hidden" name="appointmentdate" id="appointmentdate"  value="<?php echo esc_attr($_GET['AppDate']); ?>" />
                        <input type="hidden" name="serviceduration" id="serviceduration"  value="<?php echo esc_attr($ServiceDuration); ?>" />
                        <div class="submissioninfo"></div>
                        <table width="100%" id="bordercssremove">
                            <tr>
                                <td width="30%" align="left" scope="row"><strong><?php _e("Name", "comptoneye"); ?></strong></td>
                                <td width="5%" align="center" valign="top"><strong>:</strong></td>
                                <td width="65%"><input type="text" name="clientname" id="clientname" height="30px;" style="height:30px;" /></td>
                            </tr>
                            <tr>
                                <td align="left" scope="row"><strong><?php _e("Email", "comptoneye"); ?></strong></td>
                                <td align="center" valign="top"><strong>:</strong></td>
                                <td><input type="text" name="clientemail" id="clientemail" height="30px;" style="height:30px;" ></td>
                            </tr>
                            <tr>
                                <td align="left" scope="row"><strong><?php _e("Phone", "comptoneye"); ?></strong></td>
                                <td align="center" valign="top"><strong>:</strong></td>
                                <td><input name="clientphone" type="text" id="clientphone" maxlength="12" height="30px;" style="height:30px;" />
                            <br/>
                            <label><?php _e("Eg: 1234567890", "comptoneye"); ?></label></td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" scope="row"><strong><?php _e("Special Instruction", "comptoneye"); ?></strong></td>
                                <td align="center" valign="top"><strong>:</strong></td>
                                <td valign="top"><textarea name="clientnote" id="clientnote"></textarea></td>
                            </tr>
                            <tr id="buttonbox">
                                <td><a class="apcal_btn apcal_btn-alert" id="back" onclick="Backbutton()"><i class="icon-arrow-left"></i> <?php _e("Back", "comptoneye"); ?></a>
                                </td>
                                <td>&nbsp;</td>
                                <td >
                                    <button name="booknowapp" class="apcal_btn apcal_btn-success" type="button" id="booknowapp" onclick="CheckValidation()"><i class="icon-ok icon-white"></i> <?php _e("Book Now", "comptoneye"); ?></button>
                                </td>
                            </tr>
                        </table>
                        <div id="loading2" style="display:none; color:#1FCB4A;"><?php _e('Scheduling your appointment please wait...', 'comptoneye'); ?><img src="<?php echo plugins_url()."/appointment-calendar/images/loading.gif"; ?>" /></div>
                        <style type="text/css">
                            #bordercssremove tr td {
                              border-top: 0 solid #DDDDDD;
                            }
                        </style><?php
                        } ?>
                    </div>
                    <!--</div>-->
                </form>
            </div>
        </div><?php
    }// end of isset next1 servicId and AppDate


    
}//end of short code function ?>
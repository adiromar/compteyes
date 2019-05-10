<?php 
$args = array(
        'posts_per_page' => -1,
        'post_type' => 'cmpt_events',
        'post_status' => 'publish',

    );   
    $my_query = null;
    $my_query = new WP_Query($args);
    $check_date = get_the_title();
    $count = 'my name';
 if( $my_query->have_posts() ) :
        while ($my_query->have_posts()) : $my_query->the_post(); 
  setup_postdata($post);
  $distro_location_array = get_post_meta( $post->ID, 'Appointment', true );
  $distro_location = get_post_custom(get_the_ID());
  //print_r($distro_location);

  $fields = $distro_location['Appointment'];
    // Loop through the fields and uneseralize them
    foreach ( $fields as $field =>$value) {
      $rpt = unserialize( $value );
      print_r($rpt);
      $some_field = $rpt['Date'];
      //$another_field = $rpt['another_field_name'];
      print_r($some_field);
    }

  //echo '<pre>';
 // wp_send_json($distro_location_array);
  //print_r($distro_location_array);
  //echo '</pre>';
  $main_count = 0;
     //echo "something else";
     //$someval = array('noerror' => 'you');
     // echo '<pre>';
     // print_r( $distro_location_array);
     // echo '</pre>';
        $timeval = array();
        $temval = array();
        $title = get_the_title();
        $count = 0 ; 
     foreach( $distro_location_array as $main => $key ) {       
     //echo 'inside the text';
        // echo 'the title is '.$distro_location_array[$main_count]['subgroup']['title'];
        $goman = "time" .$count ;
        $end_date_col = $key['Date'];
        $time_for_that_date = $key['subgroup'];
        $some_date [$goman] = $key['Date'];
        //print_r($end_date_col);
        // echo ;
        //  echo '<pre>';
        // // print_r($end_date_col);
        //  print_r($time_for_that_date);
        // echo '</pre>';
        // echo 'one loop end';

        $date = mysql2date( 'l, F j, Y', $end_date_col);
        
        //print_r($main);
        // if($end_date_col == $check_date){
          
            foreach ($time_for_that_date as $time => $time_value ) {
                 $timeval[]= '<a>'.$time_value['title'] .'</a>';
               // $count++;
               //print_r($someval);
            }

            $tempval[$end_date_col] =  $timeval;
            $count ++;
      }
      
      $date_for_event[$title] = $some_date;
      $some_date= array();
      // echo "the title is " . $title;
      // echo '<pre>';
       //print_r($date_for_event[$title]);
       // echo '</pre>';
       // echo 'the time is ';
       // echo '<pre>';
       //print_r($tempval['1488931200']);
       //echo '</pre>';

endwhile;
endif;

 




wp_reset_postdata(); 
//wp_send_json( $ajax_time);

?>
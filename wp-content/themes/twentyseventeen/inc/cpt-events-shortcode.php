<?php
/* ------------------- THEME FORCE ---------------------- */

/*
 * EVENTS SHORTCODES (CUSTOM POST TYPE)
 * http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-events-pt-2/
 */

// 1) FULL EVENTS
//***********************************************************************************

function tf_events_full ( $atts ) {

// - define arguments -
extract(shortcode_atts(array(
    'limit' => '10', // # of events to show
 ), $atts));

// ===== OUTPUT FUNCTION =====

ob_start();

// ===== LOOP: FULL EVENTS SECTION =====

// - hide events that are older than 6am today (because some parties go past your bedtime) -

$today6am = strtotime('today 1:00') + ( get_option( 'gmt_offset' ) * 3600 );

// - query -
$args = array(
        'posts_per_page' => -1,
        'post_type' => 'tf_events',
        'post_status' => 'publish',
       
    );   
    $my_query = null;
    $my_query = new WP_Query($args); 




// - declare fresh day -
$daycheck = null;

// - loop -
 if( $my_query->have_posts() ) :
        while ($my_query->have_posts()) : $my_query->the_post(); 
setup_postdata($post);

// - custom variables -
$custom = get_post_custom(get_the_ID());
$sd = $custom["tf_events_startdate"][0];
$ed = $custom["tf_events_enddate"][0];

// - determine if it's a new day -
$longdate = date("l, F j, Y", $sd);
// if ($daycheck == null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }
// if ($daycheck != $longdate && $daycheck != null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }
if ($daycheck == null) { echo '<h2 class="ull-events">' .  get_the_title() . '</h2>'; }
 if ($daycheck != $longdate && $daycheck != null) { echo '<h2 class="full-events">' .  get_the_title() . '</h2>'; }

// - local time format -
$time_format = get_option('time_format');
$stime = date($time_format, $sd);
$etime = date($time_format, $ed);

// - output - ?>
<div class="full-events">
    <div class="text">
        <div class="title">
            <div class="time"><a href= "#"><?php echo $stime?> </a></div>
            <div class="eventtext"><?php echo $longdate ?></div>
        </div>
    </div>
    </div>
<?php

// - fill daycheck with the current day -
$daycheck = get_the_title();

endwhile;
wp_reset_postdata(); 
else :
endif;

// ===== RETURN: FULL EVENTS SECTION =====

$output = ob_get_contents();
ob_end_clean();
return $output;
}

add_shortcode('tf-events-full', 'tf_events_full'); // You can now call onto this shortcode with [tf-events-full limit='20']

?>
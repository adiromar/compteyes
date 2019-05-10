<?php

/**
 * Enqueue scripts and styles.
 */

function comptomeye_scripts() {

  
	wp_enqueue_style( 'designcalendar', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
wp_enqueue_style( 'bootstrapmincss', get_template_directory_uri().'/css/jquery-ui.css');
	wp_enqueue_style( 'bootstraptheme-min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css');
	
	//wp_enqueue_script( 'somejquery', get_template_directory_uri().'/js/jquery-1.10.2.js', array( 'jquery' ));
	wp_enqueue_script( 'calendar-ui', get_template_directory_uri().'/js/jquery-ui.js', array( 'jquery' ));


	//wp_enqueue_script( 'bootstrap-min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ));
    wp_enqueue_script( 'jscustomused', get_template_directory_uri().'/js/custom.js', array( 'jquery' ));
	
 wp_enqueue_style('datepicker', get_template_directory_uri().'/css/datepicker.css');
// wp_enqueue_script('timespecific_send' ,get_template_directory_uri().'/js/timeajax.js');
// wp_localize_script( 'timespecific_send', 'time_send_ajax', array( 'ajaxurl' => admin_url('admin-ajax.php') ) ); 

 // Theme stylesheet.
	wp_enqueue_style( 'contompteyee-style', get_stylesheet_uri() );





	
}
 add_action( 'wp_enqueue_scripts', 'comptomeye_scripts' );



function register_jquery() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}     
add_action('wp_enqueue_scripts', 'register_jquery');


// add_action('admin_init', 'tf_functions_css');

// function tf_functions_css() {
//     wp_enqueue_style('tf-functions-css', get_bloginfo('template_directory') . '/assets/css/tf-functions.css');
// }

// 1. Custom Post Type Registration (Events)

add_action( 'init', 'create_event_postype' );

function create_event_postype() {

$labels = array(
    'name' => _x('Events', 'post type general name'),
    'singular_name' => _x('Event', 'post type singular name'),
    'add_new' => _x('Add New', 'events'),
    'add_new_item' => __('Add New Event'),
    'edit_item' => __('Edit Event'),
    'new_item' => __('New Event'),
    'view_item' => __('View Event'),
    'search_items' => __('Search Events'),
    'not_found' =>  __('No events found'),
    'not_found_in_trash' => __('No events found in Trash'),
    'parent_item_colon' => '',
);

$args = array(
    'label' => __('Events'),
    'labels' => $labels,
    'public' => true,
    'can_export' => true,
    'show_ui' => true,
    '_builtin' => false,
    '_edit_link' => 'post.php?post=%d', // ?
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-admin-plugins',
    'hierarchical' => false,
    'rewrite' => array( "slug" => "events" ),
    'supports'=> array('title') ,
    'show_in_nav_menus' => true,
    'taxonomies' => array( 'tf_eventcategory', 'post_tag')
);

register_post_type( 'cmpt_events', $args);

}

add_action( 'fm_post_cmpt_events', function() {
  // $fm = new Fieldmanager_Group( array(
  //   'name' => 'slideshow',
  //   'limit' => 0,
  //   'label' => 'New appointment',
  //   'label_macro' => array( 'Amointment: %s', 'date '),
  //   'add_more_label' => 'Add another appointment',
  //   'sortable' => false,
  //   'children' => array(
    	 // new Fieldmanager_Datepicker( array(
      //   'name' => 'date',
      //   'label' => 'Date',
      //   ) ),
		//     new Fieldmanager_Group( array(
		// 			    'name' => 'slideshow',
		// 			    'limit' => 0,
		// 			    'label' => 'time',
		// 			    'label_macro' => array( 'Time: %s', 'title' ),
		// 			    'add_more_label' => 'Add time',
		// 			    'sortable' => true,
		// 			    'children' =>array(
		// 			    'title' => new Fieldmanager_Textfield( '' ),
					    

		//     )
		//     )
  //     )
  //   )
  // ) 
  //   );
	$fm = new Fieldmanager_Group( array(
	'name'           => 'Appointment',
	'limit' => 0,
	'add_more_label' => 'Add another appointment',

	'children'       => array(

		'Date' =>	 new Fieldmanager_Datepicker( array(
						
						'label' => 'Date',
			)
        				),
		'subgroup' => new Fieldmanager_Group( array(
			'add_more_label' =>'add time',
			'limit' => 0,
			'label' =>'time',
			'label_macro' => array( 'Time: %s', 'title' ),
			'children'       => array(
				'title' => new Fieldmanager_Textfield( '' ),
			),
		) ),
	),
) );

  $fm->add_meta_box( 'Appointment', array('cmpt_events') );
} );

// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

// // Setup Ajax action hook
// add_action( 'wp_ajax_cmt_time_send',  'cmt_time_send');
// add_action("wp_ajax_nopriv_cmt_time_send", "cmt_time_send");

// function cmt_time_send() {
  
// //   include (get_template_directory().'/include/timelogic.php');

// //   $post_date = $_POST['post_date'];
// //     $check_date= mysql2date( 'U', $post_date );
// //     $json_array= array('value' => 'default');

// //     $no_val = array('gone_hell' => 'you');

// // $args = array(
// //         'posts_per_page' => -1,
// //         'post_type' => 'tf_events',
// //         'post_status' => 'publish',    
// //     );   
// //     $my_query = null;
// //     $my_query = new WP_Query($args);
// //     $check_date = get_the_title();
// //     $count = 'my name';
// //  if( $my_query->have_posts() ) :
// //         while ($my_query->have_posts()) : $my_query->the_post(); 
// //   setup_postdata($post);
// //   $distro_location_array = get_post_meta( $post->ID, 'Appointment', true );
// //   $array_location = 
// //    // $ajax_time = timegive($distro_location_array);
// //    //  echo $count;
// //   $json_array = array ('someval' => 'noval');
// //    wp_send_json_success($json_array);
// //   // echo '<pre>';
// //   //  echo '<a>  dd something </a>';
// //   // echo '</pre>';
  
  
// // endwhile;
// // endif;



// // wp_reset_postdata(); 
// // //wp_send_json( $ajax_time);

// // wp_die(1);

// include (get_template_directory().'/dateTime.php');

//  $args = array(
//         'posts_per_page' => 1,
//         'post_type' => 'cmpt_events',
//         'post_status' => 'publish', 
//     );   
//     $my_query = null;
//     $my_query = new WP_Query($args);
//      if( $my_query->have_posts() ) :
//         while ($my_query->have_posts()) : $my_query->the_post(); 
//       setup_postdata($post);
//       //the_title();

//       // foreach ($date_for_event[get_the_title()] as $date ) {
//       //   $someval[$date] = $date;
//       //   // the_title();
//       //   // echo '</br>';
//       //   // echo $date;
//       //   // echo '</br>';
//       // }
//        header('Content-type:application/json;charset=utf-8');
//       echo json_encode($date_for_event[get_the_title()]);
 

//   endwhile;
//      die;
// endif;
// wp_reset_postdata();
 
   
// }


function disable_plugin_updates( $value ) {
   unset( $value->response['appointment-calendar/appointment-calendar.php'] );
   return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );


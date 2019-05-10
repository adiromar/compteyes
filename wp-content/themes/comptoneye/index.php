<?php
get_header();
//  include (get_template_directory().'/dateTime.php');

//  $args = array(
//         'posts_per_page' => 1,
//         'post_type' => 'cmpt_events',
//         'post_status' => 'publish', 
//         's' => 'Some event'
//     );   
//     $my_query = null;
//     $my_query = new WP_Query($args);
//      if( $my_query->have_posts() ) :
//         while ($my_query->have_posts()) : $my_query->the_post(); 
//       setup_postdata($post);

//       print_r($date_for_event[get_the_title()]);
//       foreach ($date_for_event[get_the_title()] as $date =>$value) {
//         # code...
//         the_title();
//         echo '</br>';
//         echo $value;
//         echo $temp;
//         echo '</br>';
//       }
//  echo json_encode($date_for_event[get_the_title()]);

//   endwhile;
// endif;

 
// wp_die();

 if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <h2><?php the_title() ;?></h2>
  <?php //the_post_thumbnail(); ?>
  <div class='col-md-4'>
  <?php the_content(); ?>
  </div>

<?php endwhile; else: ?>

  <p>Sorry, no posts to list</p>

<?php endif; ?>

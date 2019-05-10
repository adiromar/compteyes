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
 	?> <select>
     <?php    while ($my_query->have_posts()) : $my_query->the_post(); 
  setup_postdata($post); ?>
  
          <option value="<?php the_title(); ?>"><?php the_title(); ?></option>
 
    <?php
    endwhile; ?>
      </select>
   <?php  endif;
    wp_reset_postdata();
    ?>
<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentyseventeen' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentyseventeen' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<?php

// - query -
$args = array(
        'posts_per_page' => -1,
        'post_type' => 'tf_events',
        'post_status' => 'publish',
       
    );   
    $my_query = null;
    $my_query = new WP_Query($args); 
// - loop -
 if( $my_query->have_posts() ) :
        while ($my_query->have_posts()) : $my_query->the_post(); 
	setup_postdata($post);
	$distro_location_array = get_post_meta( $post->ID, 'Appointment', true );
	 $index = $my_query->current_post;
	 echo $index;


	if(isset($distro_location_array[$index]['Date'])){
			  $end_date_col = $distro_location_array[$index]['Date'];
	  $date = mysql2date( 'l, F j, Y', $end_date_col);
	echo  $date;
	
}
	echo '<pre>';
	
	print_r($distro_location_array);
	echo '</pre>';
endwhile;
endif;
wp_reset_postdata(); 


?>

<?php get_footer();

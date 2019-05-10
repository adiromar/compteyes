<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php else : ?>
	<header class="page-header">
		<h2 class="page-title"><?php _e( 'Posts', 'twentyseventeen' ); ?></h2>
	</header>
	<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/post/content', get_post_format() );

				endwhile;

				the_posts_pagination( array(
					'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
				) );

			else :

				get_template_part( 'template-parts/post/content', 'none' );

			endif;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php 
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
        while ($my_query->have_posts()) : $my_query->the_post(); ?>
    
<?php endwhile; endif;
?>
<?php
// - hide events that are older than 6am today (because some parties go past your bedtime) -

$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );

// - query -
global $wpdb;
$querystr = "
    SELECT *
    FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
    WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
    AND (metaend.meta_key = 'tf_events_enddate' AND metaend.meta_value > $today6am )
    AND metastart.meta_key = 'tf_events_enddate'
    AND wposts.post_type = 'tf_events'
    AND wposts.post_status = 'publish'
    ORDER BY metastart.meta_value ASC LIMIT $limit
 ";

$events = $wpdb->get_results($querystr, OBJECT);

// - declare fresh day -
$daycheck = null;

// - loop -
if ($events):
global $post;
foreach ($events as $post):
setup_postdata($post);

// - custom variables -
$custom = get_post_custom(get_the_ID());
$sd = $custom["tf_events_startdate"][0];
$ed = $custom["tf_events_enddate"][0];

// - determine if it's a new day -
$longdate = date("l, F j, Y", $sd);
if ($daycheck == null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }
if ($daycheck != $longdate && $daycheck != null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }

// - local time format -
$time_format = get_option('time_format');
$stime = date($time_format, $sd);
$etime = date($time_format, $ed);

// - output - ?>
<div class="full-events">
    <div class="text">
        <div class="title">
            <div class="time"><?php echo $stime . ' - ' . $etime; ?></div>
            <div class="eventtext"><?php the_title(); ?></div>
        </div>
    </div>
     <div class="desc"><?php if (strlen($post->post_content) > 150) { echo substr($post->post_content, 0, 150) . '...'; } else { echo $post->post_content; } ?></div>
</div>
<?php

// - fill daycheck with the current day -
$daycheck = $longdate;

endforeach;
else :
endif;
?>

<?php get_footer();

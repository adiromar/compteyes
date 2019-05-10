<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/content', get_post_format() );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

					the_post_navigation( array(
						'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
						'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
					) );

				endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php 
$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );
echo $today6am;

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
echo $events;

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
endif; ?>
<?php get_footer();

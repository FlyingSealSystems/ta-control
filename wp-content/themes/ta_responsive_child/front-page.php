<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Home Page
 *
 * File: home.php
 * Author: Bob Spies
 * Part of ta_responsive_child theme.
 * Takes place of equivalent file in parent theme (Responsive).
 */
?>
<?php get_header(); ?>

<div id="featured" class="grid col-940">

	<?php $options = get_option('responsive_theme_options');
	// First let's check if content is in place
		if (!empty($options['home_content_area'])) {
			echo '<p>';
			echo do_shortcode($options['home_content_area']);
			echo '</p>';
	// If not let's show dummy content for demo purposes
		  } else {
			echo '<p>';
			echo __('Your title, subtitle and this very content is editable from Theme Option. Call to Action button and its destination link as well. Image on your right can be an image or even YouTube video if you like.','responsive');
			echo '</p>';
		  }
	?>

</div><!-- end of #featured -->

<div id="content-blog-home">

<div class="grid col-620">

<?php

$ta_post_type_array = array(
	'post_type' => get_query_var('post_type')
);

if ( get_query_var('paged') )
	$paged = get_query_var('paged');
elseif ( get_query_var('page') )
	$paged = get_query_var('page');
else
	$paged = 1;
$ta_paged_array = array('paged'=> $paged);

$ta_posts_per_page_array = array('posts_per_page' => get_option('posts_per_page'));

/* Exclude Events that have don't show on home page option enabled. */
$ta_exclude_events_frontpage = array (
	'meta_query' => array( 'relation' => 'OR',
		array( 'key' => '_ecp_custom_2', 'value' => '', 'compare' => 'NOT EXISTS' ),
		array( 'key' => '_ecp_custom_2', 'value' => array('Yes','Home Page','Home and Archive Pages'), 'compare' => 'NOT IN' )
	)
);

/* Exclude Classifieds */
$ta_exclude_categories_frontpage = array (
	'cat' => '-137');

/*
echo "<pre>";
var_dump($ta_exclude_homepage_query);
echo "</pre>";
*/

$qp_args = array_merge(
	$ta_post_type_array,
	$ta_paged_array,
	$ta_posts_per_page_array,
	$ta_exclude_events_frontpage,
	$ta_exclude_categories_frontpage
);

$ta_Posts = new WP_Query($qp_args);

?>

<?php if ($ta_Posts->have_posts()) : ?>
		<?php while ($ta_Posts->have_posts()) : $ta_Posts->the_post(); ?>
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__( 'Permanent Link to %s', 'responsive' ), the_title_attribute( 'echo=0' )); ?>"><?php the_title(); ?></a></h1>
				<?php if ( tribe_get_custom_field('Event Sponsor') ) : ?>
					<div class="ta-event-sponsor-postlist">
						(Event Sponsored by <?php tribe_custom_field('Event Sponsor'); ?>)
					</div>
				<?php endif; ?>
                <div class="ta-event-date-and-details">
				<?php if (get_post_type( $post ) == 'tribe_events' ) : ?>
					<span class="event-meta event-meta-date"><meta itemprop="startDate" content="<?php echo tribe_get_start_date( null, false, 'Y-m-d-h:i:s' ); ?>"/><?php echo tribe_get_start_date(); ?> - </span>
					<a class="event_details" href="<?php tribe_event_link($post); ?>" ;>Event Details</a><br />
				<?php endif; ?>
				</div>
                <div class="post-meta">
                <?php ta_responsive_post_meta_byline(get_the_id()); ?>

				    <?php if ( comments_open() && ($post->comment_count > 0) ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', '1 Comment &darr;', '% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?>
                </div><!-- end of .post-meta -->

                <div class="post-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('thumbnail'); ?>
                        </a>
                    <?php endif; ?>

                    <?php the_content(); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->

                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?>
				    <?php if (get_post_type( $post ) == 'post' ) : ?>
						<?php printf(__('Posted in %s', 'responsive'), get_the_category_list(', ')); ?>
				    <?php elseif (get_post_type( $post ) == 'tribe_events' ) : ?>
				    	<?php printf(__('Posted in %s', 'responsive'), get_the_term_list( $post->ID, 'tribe_events_cat', '', ', ', '' ));  ?>
					<?php endif; ?>
                	<?php ta_responsive_post_meta_posted(); ?>
                </div><!-- end of .post-data -->

            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>
            </div><!-- end of #post-<?php the_ID(); ?> -->

        <?php endwhile; ?>

        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
        <h6>
		    <?php _e( 'You can return', 'responsive' ); ?>
            <a href="<?php echo home_url(); ?>" title="<?php esc_attr_e( 'Home', 'responsive' ); ?>">
			<?php _e( '&larr; Home', 'responsive' ); ?></a>
			<?php _e( 'or search for the page you were looking for', 'responsive' ); ?>
        </h6>
        <?php get_search_form(); ?>

<?php endif; ?>

</div><!-- end of .grid col-620-->

<?php get_sidebar('right'); ?>

</div><!-- end of #content-blog-home -->

<?php get_sidebar('home'); ?>
<?php get_footer(); ?>

<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Archive Template
 *
 * File: archive.php
 * Author: Bob Spies
 * Part of ta_responsive_child theme.
 * Takes place of equivalent file in parent theme (Responsive).
 */
?>
<?php get_header(); ?>

        <div id="content-archive" class="grid col-620">

<?php

if ( get_query_var('paged') )
	$paged = get_query_var('paged');
elseif ( get_query_var('page') )
	$paged = get_query_var('page');
else
	$paged = 1;
$ta_paged_array = array('paged'=> $paged);

$ta_posts_per_page_array = array('posts_per_page' => get_option('posts_per_page'));

if ( is_day()) {
    $ta_archive = array (  
        'date_query' => array(
            'year' => intval(get_the_date( 'Y' )) ,
            'month' => intval(get_the_date( 'm' )) ,
            'day' => intval(get_the_date( 'd' )) 
            ));
}
elseif ( is_month() ) {
    $ta_archive = array (  
        'date_query' => array(
            'year' => intval(get_the_date( 'Y' )) ,
            'month' => intval(get_the_date( 'm' )) ));
}
elseif ( is_year() ) {
    $ta_archive = array (  
        'date_query' => array(
            'year' => intval(get_the_date( 'Y' )) ));
} else { 
    $cat  = single_cat_title( '', false );
    if ($cat == "Classifieds") {
        $category = '137';
    } else {
        $category = $cat;
    }
    $ta_archive = array (
            'cat' => $category);  
}



/* Exclude Events that have don't show on archive page option is selecteds. */
$ta_exclude_events_frompage = array (
	'meta_query' => array( 'relation' => 'OR',
		array( 'key' => '_ecp_custom_2', 'value' => '', 'compare' => 'NOT EXISTS' ),
		array( 'key' => '_ecp_custom_2', 'value' => 'Home and Archive Pages', 'compare' => '!=' )
	)
);

$qp_args = array_merge(
	$ta_paged_array,
        $ta_posts_per_page_array,
	$ta_exclude_events_frompage,
	$ta_archive
);

$ta_Posts = new WP_Query($qp_args);

?>

<?php if ($ta_Posts->have_posts()) : ?>
        <?php $options = get_option('responsive_theme_options'); ?>
		<?php if ($options['breadcrumb'] == 0): ?>
		<?php echo responsive_breadcrumb_lists(); ?>
        <?php endif; ?>

		    <h6>
			    <?php if ( is_day() ) : ?>
				    <?php printf( __( 'Daily Archives: %s', 'responsive' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'responsive' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'responsive' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
				<?php else : ?>
					<?php ?>
					<?php /* _e( 'Blog Archives', 'responsive' ); */ ?>
					<?php single_cat_title( "Postings Related To: ", true ); ?>

				<?php endif; ?>
			</h6>

	<?php while ($ta_Posts->have_posts()) : $ta_Posts->the_post(); ?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__( 'Permanent Link to %s', 'responsive' ), the_title_attribute( 'echo=0' )); ?>"><?php the_title(); ?></a></h1>

				<?php if ( tribe_get_custom_field('Event Sponsor') ) : ?>
					<div class="ta-event-sponsor-postlist">
						(Event Sponsored by <?php tribe_custom_field('Event Sponsor'); ?>)
					</div>
				<?php endif; ?>

				<?php if (get_post_type( $post ) == 'tribe_events' ) : ?>
					<div class="ta-event-date-and-details">
						<span class="event-meta event-meta-date"><meta itemprop="startDate" content="<?php echo tribe_get_start_date( null, false, 'Y-m-d-h:i:s' ); ?>"/><?php echo tribe_get_start_date(); ?> - </span>
						<a class="event_details" href="<?php tribe_event_link($post); ?>" ;>Event Details</a><br />
					</div>
				<?php endif; ?>

                <div class="post-meta">
                <?php ta_responsive_post_meta_byline(get_the_id()); ?>

				    <?php if ( comments_open() && ($post->comment_count > 0) ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
						<?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?>
                </div><!-- end of .post-meta -->

                <div class="post-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail('thumbnail', array('class' => 'alignleft')); ?>
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

            <?php comments_template( '', true ); ?>

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

        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
	                ));
			 ?></h6>

        <?php get_search_form(); ?>

<?php endif; ?>
<?php wp_reset_query(); ?>

        </div><!-- end of #content-archive -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

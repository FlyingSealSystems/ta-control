<?php
/*  Custom functions for Transition Albany Website
	Included by parent (responsive) theme. */

/**
 * File: functions.php
 * Author: Bob Spies
 * Part of ta_responsive_child theme.
 * Child theme additions to Responsive Theme functions.php.
 */

/**
When user clicks on Post or Event "More" link, take him/her to the top of the
individual Post/Event--not to the place where the More link wa located.
*/
function ta_more_link_to_top($link) {
	$offset = strpos($link, '#more-');
	if ($offset) {
		$end = strpos($link, '"',$offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
}

add_filter('the_content_more_link', 'ta_more_link_to_top');

/**
Add Events to Post listings. In the case of Category archives, also requires
adding tribe_events_cat to tax_query.
*/
add_action( 'pre_get_posts', 'ta_main_query_mods' );

function ta_main_query_mods($query) {
	$post_type = $query->get( 'post_type' );
	if ((is_main_query()) && !(is_page()) && !is_admin() &&  !is_preview() &&
		( empty( $post_type ) ||  'post' == $post_type )) {
			$query->set( 'post_type', array( 'post', 'tribe_events' ) );
			if (is_category()) {
				$ta_tax_query = array(
					'relation' => 'OR',
					/* Don't need to include taxonomy => category array because this
					is automatically created by WP from $wp_query->query_vars[category_name]
					when it sets up the database query. */
					array(
						'taxonomy' => 'tribe_events_cat',
						'terms' => array(get_query_var('category_name')),
						'field' => 'slug'
						),
				);
				$query->set('tax_query', $ta_tax_query);
			} // if is_category
	} // if post
}

/**
Diagnostics on the wp_query object that gets created.
Used 2013-03-23--might be useful in future.
*/
//add_action( 'wp', 'ta_diag1' );

function ta_diag1() {
	global $wp_query;
	if ((is_main_query()) && !(is_page()) && !is_admin() &&  !is_preview()) {
			echo "<pre>";
			echo 'var_dump($wp_query->query);'."</br>";
			var_dump($wp_query->query);
			echo 'var_dump($wp_query->query_vars[category_name]);'."</br>";
			var_dump($wp_query->query_vars[category_name]);
			echo 'var_dump($wp_query->query_vars[tax_query]);'."</br>";
			var_dump($wp_query->query_vars[tax_query]);
			echo 'var_dump($wp_query->tax_query->queries);'."</br>";
			var_dump($wp_query->tax_query->queries);
			echo 'var_dump($wp_query->request);'."</br>";
			var_dump($wp_query->request);
			echo "</pre>";
	}
}

/**
Posted date.
Partially replaces responsive_post_meta_data().
*/
function ta_responsive_post_meta_posted() {
	/* Start with "on" because appears right after list of categories */
	printf( __( '<span class="%1$s">on </span>%2$s', 'responsive' ),
	'meta-prep meta-prep-author posted',
	sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="timestamp">%3$s</span></a>',
		get_permalink(),
		esc_attr( get_the_time() ),
		get_the_date()
	)  // sprintf
	); // printf
}

/**
Posted by.
Partially replaces responsive_post_meta_data().
*/
function ta_responsive_post_meta_byline($fss_post_id) {
	$fss_post = get_post($fss_post_id);
	printf( __( '<span class="%1$s"> Posted by </span>%2$s', 'responsive' ),
	'byline',
	sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
		get_author_posts_url( get_the_author_meta( 'ID', $fss_post->post_author ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'responsive' ), get_the_author_meta('display_name',$fss_post->post_author) ),
		get_the_author_meta('display_name',$fss_post->post_author)
	)  //sprintf
	); //printf
}

/**
Don't allow comments unless it's a Post or a Page.
If it's a Post or Page, depends whether editor/administrator checked "Allow comments".
This became an issue when comment boxes were showing up for post type "attachment" in the photo carousel.
See example http://transitionalbany.dev/2013/building-community-with-mud-and-mandarins.
*/

function fss_comments_allowed( $allowed, $post_id ) {
    $post = get_post( $post_id );
    $allowed_types = array('post', 'tribe_events');
    if (!in_array($post->post_type, $allowed_types))
    	$allowed = false;
    return $allowed;
}

add_filter( 'comments_open', 'fss_comments_allowed', 10 , 2 );

/**
In a query the includes Events and Posts, for Events use Post Date rather than Event-Date-as-Post-Date
2015-11-22 - Added if ($fields[0]) since $fields now an associative array on single event.
	(Adding the $fields[0] string there totally messes up the query.)
*/
add_filter('tribe_events_query_posts_fields', 'fss_event_use_post_date');

function fss_event_use_post_date($fields) {
	global $wpdb;
	if ($fields[0]) {
		$fields[0] = "{$wpdb->posts}.post_date AS post_date";
	}
	return $fields;
}

/**
Get rid of the You have venues for which we don't have Geolocation information message
*/

/* $fss_TribeEventsGeoLoc = TribeEventsGeoLoc::instance();
echo "<pre>";
var_dump($fss_TribeEventsGeoLoc);
echo "</pre>"; */
if ( class_exists( 'TribeEventsGeoLoc' ) ) {
	remove_action( 'admin_init', array( TribeEventsGeoLoc::instance(), 'maybe_offer_generate_geopoints' ) );
}

/**
Add page slug to body class
*/

add_filter( 'body_class', 'fss_body_class' );

function fss_body_class ($classes) {
	global $post;
	if (is_page()) {
		$classes[] = $post->post_name;
	}
	return $classes;
}

/**
Limit number of months displayed by archive widget.
*/

add_filter( 'widget_archives_args', 'fss_limit_archives' );

function fss_limit_archives($args){
    $args['limit'] = 12;
    return $args;
}

/**
Fix poorly-worded strings in plugins.
*/

// User Submitted Values => Information Submitted
load_plugin_textdomain( 'ninja-forms', false, '/fsslangs/' );

?>
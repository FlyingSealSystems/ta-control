<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>

<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<sy:updatePeriod><?php
		$duration = 'hourly';

		/**
		 * Filter how often to update the RSS feed.
		 *
		 * @since 2.1.0
		 *
		 * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
		 *                         'yearly'. Default 'hourly'.
		 */
		echo apply_filters( 'rss_update_period', $duration );
	?></sy:updatePeriod>
	<sy:updateFrequency><?php
		$frequency = '1';

		/**
		 * Filter the RSS update frequency.
		 *
		 * @since 2.1.0
		 *
		 * @param string $frequency An integer passed as a string representing the frequency
		 *                          of RSS updates within the update period. Default '1'.
		 */
		echo apply_filters( 'rss_update_frequency', $frequency );
	?></sy:updateFrequency>
	<?php
	/**
	 * Fires at the end of the RSS2 Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_head');

	while( have_posts()) : the_post();
	?>
<?php 
    // This section is to filter the RSS feed items for recurring events.
    // We want recurring to get posted as RSS feed items 3 days prior to the event Start date.
    $displayItem = false;
    if (is_event() && tribe_is_recurring_event()) {
        $name = get_the_title_rss();
        $pdate = get_post_time( 'Y-m-d H:i:s', true );
        $start_date = strtotime($pdate);  
        $gm = gmdate('Y-m-d H:i:s') ; // this is when the MailChimp campaign gets out 8pm
        $todaysCutoff = strtotime($gm);
        $threedays =  $todaysCutoff + (3 * 24 * 60 * 60); 
        $twodays = $todaysCutoff + (2* 24 * 60 * 60);
        if (($start_date <= $threedays) &&
                ($start_date >= $twodays))  {
            $displayItem = true;
            $pubDate = mysql2date( 'D, d M Y H:i:s +0000', $gm, false );
        }
    }
    // for non-recurring events and posts
    else { 
        // we want the original post date (the date the event was created, not eventStartDate
        $displayItem = true;
        $pubDate = mysql2date( 'D, d M Y H:i:s +0000', get_post_time_org( 'Y-m-d H:i:s', true ), false );
        
    } 
    ?>    
        
<?php if ($displayItem) : ?>        
        <item>
		<title> <?php the_title_rss() ?><?php  if (is_event()) : echo ' '.tribe_get_start_date( null, true,'m/d/y @h:ia'); endif; ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link_feed(); ?></comments>
		<pubDate><?php echo $pubDate; ?></pubDate>
		<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
		<?php the_category_rss('rss2') ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
    <?php if (get_option('rss_use_excerpt')) : ?>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
    <?php else : ?>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
	<?php $content = get_the_content_feed('rss2'); ?>
	<?php if ( strlen( $content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
	<?php else : ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
	<?php endif; ?>
    <?php endif; ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
    <?php rss_enclosure(); ?>
	<?php
	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
    <?php endif; ?>
	<?php endwhile; ?>
</channel>
</rss>

<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Header Template
 *
 *
 * File: header.php
 * Author: Bob Spies
 * Part of ta_responsive_child theme.
 * Takes place of equivalent file in parent theme (Responsive).
 */
?>
<!doctype html>
<!--[if !IE]>      <html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?php wp_title('&#124;', true, 'right'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php responsive_container(); // before container hook ?>
<div id="container" class="hfeed">

    <?php responsive_header(); // before header hook ?>
    <div id="header">

		<?php responsive_header_top(); // before header content hook ?>

        <?php if (has_nav_menu('top-menu', 'responsive')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'top-menu',
					'theme_location'  => 'top-menu')
					);
				?>
        <?php } ?>

    <?php responsive_in_header(); // header hook ?>

        <div id="logo">
            <a href="<?php echo home_url('/'); ?>"><img src="<?php header_image(); ?>" width="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> width;} else { echo HEADER_IMAGE_WIDTH;} ?>" height="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> height;} else { echo HEADER_IMAGE_HEIGHT;} ?>" alt="<?php bloginfo('name'); ?>" /></a>
        </div><!-- end of #logo -->

		<div id="header_text">
        Inviting all of Albany, California to create a positive transition from fossil fuel
        dependency to vital, engaged, resilient and interdependent community.
        </div>

    <?php get_sidebar('top'); ?>
				<?php wp_nav_menu(array(
				    'container'       => 'div',
						'container_class'	=> 'main-nav',
						'fallback_cb'	  =>  'responsive_fallback_menu',
						'theme_location'  => 'header-menu')
					);
				?>

            <?php if (has_nav_menu('sub-header-menu', 'responsive')) { ?>
	            <?php wp_nav_menu(array(
				    'container'       => '',
					'menu_class'      => 'sub-header-menu',
					'theme_location'  => 'sub-header-menu')
					);
				?>
            <?php } ?>

			<?php responsive_header_bottom(); // after header content hook ?>

    </div><!-- end of #header -->
    <?php responsive_header_end(); // after header container hook ?>

	<?php responsive_wrapper(); // before wrapper container hook ?>
    <div id="wrapper" class="clearfix">
		<?php responsive_wrapper_top(); // before wrapper content hook ?>
		<?php responsive_in_wrapper(); // wrapper hook ?>
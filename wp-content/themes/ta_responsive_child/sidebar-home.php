<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Home Widgets Template
 *
 * File: sidebar-home.php
 * Author: Bob Spies
 * Part of ta_responsive_child theme.
 * Takes place of equivalent file in parent theme (Responsive).
 */
?>
    <div id="widgets" class="home-widgets">
        <div class="grid col-620">
        <?php responsive_widgets(); // above widgets hook ?>

            <?php if (!dynamic_sidebar('home-widget-1')) : ?>
            <div class="widget-wrapper">

                <div class="widget-title-home"><h3><?php _e('Home Widget 1', 'responsive'); ?></h3></div>
                <div class="textwidget"><?php _e('This is your first home widget box. To edit please go to Appearance > Widgets and choose 6th widget from the top in area 6 called Home Widget 1. Title is also manageable from widgets as well.','responsive'); ?></div>

			</div><!-- end of .widget-wrapper -->
			<?php endif; //end of home-widget-1 ?>

        <?php responsive_widgets_end(); // responsive after widgets hook ?>
        </div><!-- end of .col-300 -->

<!-- Removed home-widget-2 RBS 12-3-2012  -->

        <div class="grid col-300 fit">
        <?php responsive_widgets(); // above widgets hook ?>

			<?php if (!dynamic_sidebar('home-widget-3')) : ?>
            <div class="widget-wrapper">

                <div class="widget-title-home"><h3><?php _e('Home Widget 3', 'responsive'); ?></h3></div>
                <div class="textwidget"><?php _e('This is your third home widget box. To edit please go to Appearance > Widgets and choose 8th widget from the top in area 8 called Home Widget 3. Title is also manageable from widgets as well.','responsive'); ?></div>

			</div><!-- end of .widget-wrapper -->
			<?php endif; //end of home-widget-3 ?>

        <?php responsive_widgets_end(); // after widgets hook ?>
        </div><!-- end of .col-300 fit -->
    </div><!-- end of #widgets -->
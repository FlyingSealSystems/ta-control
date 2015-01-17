<?php
/*
Plugin Name: FSS Archive Widget
Description: Monthly archive links for recent months followed by link to archive page.
*/

// Creating the widget
class fss_archive_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'fss_archive_widget',

// Widget name will appear in UI
//__('FSS Archive Widget', 'fss_archive_widget_domain'),
'FSS Archive Widget',

// Widget description
//array( 'description' => __( 'Custom monthly archive links', 'fss_archive_widget_domain' ), )
array( 'description' => 'Custom monthly archive links' )
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

echo '<ul>';
wp_get_archives( apply_filters( 'widget_archives_args', array(
	'type'            => 'monthly',
) ) );
$archive_page = get_bloginfo('wpurl').'/archives-by-month/';
echo '<li><a href='.$archive_page.'>Earlier</a></li>';
echo '</ul>';
echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
//$title = __( 'New title', 'fss_archive_widget_domain' );
$title = 'New title';
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class fss_archive_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'fss_archive_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );



?>

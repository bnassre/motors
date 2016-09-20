<?php
class STM_WP_Widget_Text extends WP_Widget {

public function __construct() {
$widget_ops = array('classname' => 'stm_wp_widget_text', 'description' => __('STM Arbitrary text or HTML.', 'motors'));
$control_ops = array('width' => 400, 'height' => 350);
parent::__construct('stm_text', __('STM Text', 'motors'), $widget_ops, $control_ops);
}

public function widget( $args, $instance ) {
/** This filter is documented in wp-includes/default-widgets.php */
$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

$text = apply_filters( 'stm_wp_widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
echo $args['before_widget'];
if ( ! empty( $title ) ) {
echo $args['before_title'] . $title . $args['after_title'];
} ?>
<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
<?php
echo $args['after_widget'];
}

public function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = $new_instance['title'];
	if ( current_user_can('unfiltered_html') )
		$instance['text'] =  $new_instance['text'];
	else
		$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
	$instance['filter'] = ! empty( $new_instance['filter'] );
	return $instance;
}

public function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
	$title = $instance['title'];
	$text = esc_textarea($instance['text']);
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'motors'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

	<p><label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php esc_html_e( 'Content:', 'motors' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>

	<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php esc_html_e('Automatically add paragraphs', 'motors'); ?></label></p>
<?php
}
}

function register_stm_text_widget() {
	register_widget( 'STM_WP_Widget_Text' );
}
add_action( 'widgets_init', 'register_stm_text_widget' );
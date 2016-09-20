<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if(empty($align)) {
	$align = 'text-center';
}

?>

<div class="colored-separator<?php echo esc_attr($css_class.' '.$align); ?>">
	<div class="first-long stm-base-background-color" <?php if(!empty($color)): ?> style="background-color: <?php echo esc_attr($color); ?>" <?php endif; ?>></div>
	<div class="last-short stm-base-background-color" <?php if(!empty($color)): ?> style="background-color: <?php echo esc_attr($color); ?>" <?php endif; ?>></div>
</div>
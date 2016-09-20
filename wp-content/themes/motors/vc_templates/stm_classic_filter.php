<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)) {
	wp_enqueue_script( 'stm_grecaptcha' );
}
?>

<div class="archive-listing-page <?php echo esc_attr($css_class); ?>">
	<?php
		if(stm_is_listing()) {
			get_template_part( 'partials/listing-cars/listing-directory', 'archive' );
		} else {
			get_template_part( 'partials/listing-cars/listing', 'archive' );
		}
	?>
</div>
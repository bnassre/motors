<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$city_mpg = get_post_meta(get_the_ID(),'city_mpg',true);
$highway_mpg = get_post_meta(get_the_ID(),'highway_mpg',true);

if(!empty($city_mpg) and !empty($highway_mpg)): ?>

	<div class="single-car-mpg heading-font<?php echo esc_attr($css_class); ?>">
		<div class="text-center">
			<div class="clearfix dp-in text-left mpg-mobile-selector">
				<div class="mpg-unit">
					<div class="mpg-value"><?php echo esc_attr($city_mpg); ?></div>
					<div class="mpg-label"><?php esc_html_e('city mpg', 'motors'); ?></div>
				</div>
				<div class="mpg-icon">
					<i class="stm-icon-fuel"></i>
				</div>
				<div class="mpg-unit">
					<div class="mpg-value"><?php echo esc_attr($highway_mpg); ?></div>
					<div class="mpg-label"><?php esc_html_e('hwy mpg', 'motors'); ?></div>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>
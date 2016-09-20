<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$data = stm_get_single_car_listings();
$post_id = get_the_ID();
$vin_num = get_post_meta(get_the_id(),'vin_number', true);
?>

<?php if(!empty($data)): ?>
	<div class="single-car-data<?php echo esc_attr($css_class); ?>">
		<table>
			<?php foreach($data as $data_value):?>
				<?php if($data_value['slug'] != 'price'): ?>
					<?php $data_meta = get_post_meta($post_id,$data_value['slug'], true); ?>
					<?php if(!empty($data_meta) and $data_meta != 'none'): ?>
						<tr>
							<td class="t-label"><?php esc_html_e($data_value['single_name'], 'motors'); ?></td>
							<?php if(!empty($data_value['numeric']) and $data_value['numeric']): ?>
								<td class="t-value h6"><?php echo esc_attr_e(ucfirst($data_meta), 'motors'); ?></td>
							<?php else: ?>
								<?php 
									$data_meta_array = explode(',',$data_meta); 
									$datas = array();
								?>
								<?php 
									if(!empty($data_meta_array)){
										foreach($data_meta_array as $data_meta_single) {
											$data_meta = get_term_by('slug', $data_meta_single, $data_value['slug']);
											if(!empty($data_meta->name)) {
												$datas[] = esc_attr__($data_meta->name, 'motors');
											}
										}
									}
								?>
								<td class="t-value h6"><?php echo implode(', ', $datas); ?></td>
								
							<?php endif; ?>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<!--VIN NUMBER-->
			<?php if(!empty($vin_num)): ?>
				<tr>
					<td class="t-label"><?php esc_html_e('Vin', 'motors'); ?></td>
					<td class="t-value t-vin h6"><?php echo esc_attr($vin_num); ?></td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
<?php endif; ?>

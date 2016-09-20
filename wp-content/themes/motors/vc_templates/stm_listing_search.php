<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( isset( $atts['items'] ) && strlen( $atts['items'] ) > 0 ) {
	$items = vc_param_group_parse_atts( $atts['items'] );
	if ( ! is_array( $items ) ) {
		$temp = explode( ',', $atts['items'] );
		$paramValues = array();
		foreach ( $temp as $value ) {
			$data = explode( '|', $value );
			$newLine = array();
			$newLine['title'] = isset( $data[0] ) ? $data[0] : 0;
			$newLine['sub_title'] = isset( $data[1] ) ? $data[1] : '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$colorIndex += 1;
				$newLine['title'] = (float) str_replace( '%', '', $data[1] );
				$newLine['sub_title'] = isset( $data[2] ) ? $data[2] : '';
			}
			$paramValues[] = $newLine;
		}
		$atts['items'] = urlencode( json_encode( $paramValues ) );
	}
}

$active_taxonomy_tab = true;
$active_taxonomy_tab_active = 'active';

if(!empty($show_all) and $show_all == 'yes') {
	$active_taxonomy_tab = false;
	$active_taxonomy_tab_active = '';
}

if(empty($filter_all)) {
	$active_taxonomy_tab = true;
	$active_taxonomy_tab_active = 'active';
}

$count_posts = wp_count_posts('listings');
$all = $count_posts->publish;

function stm_listing_filter_get_selects($select_strings, $tab_name='') {

	if(!empty($select_strings)) {
		$select_strings = explode(',', $select_strings);

		if(!empty($select_strings)) {
			$output = '';
			$output .= '<div class="row">';
				foreach($select_strings as $select_string) {

					$output .= '<div class="col-md-3 col-sm-6 col-xs-12 stm-select-col">';
						//if price
						if($select_string == 'price') {
							$args = array(
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
								'fields'     => 'all',
							);

							$prices = array();

							$terms = get_terms( 'price', $args );

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$prices[] = intval( $term->name );
								}
								sort( $prices );
							}

							$output .= '<select class="stm-filter-ajax-disabled-field" name="max_' . $select_string . '" data-class="stm_select_overflowed">';
							$output .= '<option value="">' . esc_html__('Max', 'motors') . ' ' . stm_get_name_by_slug( $select_string ) . '</option>';
							if ( ! empty( $terms ) ) {
								foreach ( $prices as $price ) {
									$output .= '<option value="' . $price . '">' . stm_listing_price_view($price) . '</option>';
								}
							}
							$output .= '</select>';
						} else {
							$taxonomy_info = stm_get_taxonomies_with_type($select_string);
							//If numeric
							if(!empty($taxonomy_info['numeric']) and $taxonomy_info['numeric']) {
								$args = array(
									'orderby'    => 'name',
									'order'      => 'ASC',
									'hide_empty' => false,
									'fields'     => 'all',
								);
								$numbers = array();

								$terms = get_terms($select_string, $args);

								if ( ! empty( $terms ) ) {
									foreach ( $terms as $term ) {
										$numbers[] = intval( $term->name );
									}
								}
								sort( $numbers );

								if(!empty($numbers)) {
									$output .= '<select name="max_' . $select_string . '" data-class="stm_select_overflowed">';
									$output .= '<option value="">' . stm_get_name_by_slug( $select_string ) . '</option>';
									foreach($numbers as $number_key => $number_value) {
										if($number_key == 0) {
											$output .= '<option value=">'.$number_value.'">> '. $number_value .'</option>';
										} elseif(count($numbers) - 1 == $number_key ) {
											$output .= '<option value="<'.$number_value.'">< '. $number_value .'</option>';
										} else {
											$option_value = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
											$option_name = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
											$output .= '<option value="'.$option_value.'"> '. $option_name .'</option>';
										}
									}
									$output .= '</select>';
								}
							//other default values
							} else {
								if($select_string == 'location') {
									$output .= '<div class="stm-location-search-unit">';
										$output .= '<input type="text" class="stm_listing_filter_text stm_listing_search_location" id="stm-car-location-'. $tab_name .'" name="ca_location" />';
										$output .= '<input type="hidden" name="stm_lat"/>';
										$output .= '<input type="hidden" name="stm_lng"/>';
									$output .= '</div>';
								} else {
									$terms = stm_get_category_by_slug_all( $select_string );
									/*usort($terms, function($a, $b) {
										return strcmp($a->name, $b->name);
									});*/

									$output .= '<div class="stm-ajax-reloadable">';
									$output .= '<select name="' . $select_string . '" data-class="stm_select_overflowed">';
									$output .= '<option value="">'.esc_html__("Choose", "motors") . ' ' . stm_get_name_by_slug( $select_string ) . '</option>';
									if ( ! empty( $terms ) ) {
										foreach ( $terms as $term ) {
											$output .= '<option value="' . $term->slug . '">' . $term->name . ' (' . $term->count . ') </option>';
										}
									}
									$output .= '</select>';
									$output .= '</div>';
								}
							}
						}
					$output .= '</div>';
				}
			$output .= '</div>';

			if(!empty($output)) {
				echo $output;
			}
		}
	}

}

?>

<div class="stm_dynamic_listing_filter filter-listing stm-vc-ajax-filter animated fadeIn <?php echo esc_attr($css_class); ?>">
	<!-- Nav tabs -->
	<ul class="stm_dynamic_listing_filter_nav clearfix heading-font" role="tablist">
		<?php if(!$active_taxonomy_tab): ?>
			<li role="presentation" class="active">
				<a href="#stm_all_listing_tab" aria-controls="stm_all_listing_tab" role="tab" data-toggle="tab">
					<?php echo esc_attr($show_all_label); ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if(is_array($items)): ?>
			<?php foreach($items as $key => $item): ?>
				<?php if(!empty($item['taxonomy_tab']) and !empty($item['tab_title_single']) and !empty($item['filter_selected'])): ?>
				<?php $slug = sanitize_title($item['tab_title_single']); ?>

					<li>
						<a href="#<?php echo esc_attr($slug); ?>" aria-controls="<?php echo esc_attr($slug); ?>" role="tab" data-toggle="tab">
							<?php echo esc_attr($item['tab_title_single']); ?>
						</a>
					</li>

				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>


	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<?php if(!$active_taxonomy_tab): ?>
			<div role="tabpanel" class="tab-pane fade in active" id="stm_all_listing_tab">
				<form action="<?php echo esc_url(stm_get_listing_archive_link()); ?>" method="GET">
					<button type="submit" class="heading-font"><i class="fa fa-search"></i> <?php echo '<span>' . $all . '</span> ' . $search_button_postfix; ?> </button>
					<div class="stm-filter-tab-selects">
						<?php stm_listing_filter_get_selects($filter_all, 'stm_all_listing_tab'); ?>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if(is_array($items)): ?>
			<?php foreach($items as $key => $item): ?>
				<?php if(!empty($item['taxonomy_tab']) and !empty($item['tab_title_single']) and !empty($item['filter_selected'])): ?>
					<?php $slug = sanitize_title($item['tab_title_single']); ?>
					<div role="tabpanel" class="tab-pane fade" id="<?php echo esc_attr($slug); ?>">
						<?php
							$tax_term = explode(',', $item['taxonomy_tab']);
							$tax_term = explode(' | ', $tax_term[0]);

							$taxonomy_count = stm_get_custom_taxonomy_count($tax_term[0], $tax_term[1]);

						?>
						<form action="<?php echo esc_url(stm_get_listing_archive_link()); ?>" method="GET">
							<button type="submit" class="heading-font"><i class="fa fa-search"></i> <?php echo '<span>'.$taxonomy_count . '</span> ' . $search_button_postfix; ?></button>
							<div class="stm-filter-tab-selects">
								<div class="hidden">
									<select name="<?php echo esc_attr($tax_term[1]); ?>">
										<option value="<?php echo esc_attr($tax_term[0]); ?>" selected></option>
									</select>
								</div>
								<?php stm_listing_filter_get_selects($item['filter_selected'], $slug); ?>
							</div>
						</form>
					</div>

				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>

<?php
	$bind_tax = stm_data_binding();
?>

<script type="text/javascript">
	var stmTaxRelations = <?php echo $bind_tax; ?>

	jQuery(document).ready(function(){
		var $ = jQuery;
		$('.filter-listing.stm-vc-ajax-filter select:not(.hide,.stm-filter-ajax-disabled-field)').select2().on('change', function(){

			var tabName = $(this).closest('.tab-pane').attr('id');
			tabName = '#' + tabName;

			var stmCurVal = $(this).val();
			var stmCurSelect = $(this).attr('name');

			if (stmTaxRelations[stmCurSelect]) {

				var key = stmTaxRelations[stmCurSelect]['dependency'];
				$('.filter-listing.stm-vc-ajax-filter ' + tabName + ' select[name="' + key + '"]').val('');

				if(stmCurVal == '') {
					$(tabName + ' select[name="' + key + '"] > option').each(function () {
						$(this).removeAttr('disabled');
					});
				} else {
					var allowedTerms = stmTaxRelations[stmCurSelect][stmCurVal];

					if(typeof(allowedTerms) == 'object') {
						$(tabName + ' select[name="' + key + '"] > option').removeAttr('disabled');

						$(tabName + ' select[name="' + key + '"] > option').each(function () {
							var optVal = $(this).val();
							if (optVal != '' && $.inArray(optVal, allowedTerms) == -1) {
								$(this).attr('disabled', '1');
							}
						});
					} else {
						$(tabName + ' select[name="' + key + '"]').val(allowedTerms);
					}
				}

				$('.filter-listing.stm-vc-ajax-filter ' + tabName + ' select[name="' + key + '"]').select2("destroy");

				$('.filter-listing.stm-vc-ajax-filter ' + tabName + ' select[name="' + key + '"]').select2();
			}
		});
	});
</script>
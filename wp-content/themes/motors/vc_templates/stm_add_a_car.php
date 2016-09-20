<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

$terms_args = array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => false,
	'fields'     => 'all',
	'pad_counts' => true,
);

if(!empty($taxonomy)) {
	$taxonomy = array_filter(array_unique(explode(',', str_replace(' ', '', $taxonomy))));
}

$data = stm_get_single_car_listings();

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

if(empty($items)) {
	$items = array();
}

if(empty($stm_title_user)) {
	$stm_title_user = '';
}

if(empty($stm_text_user)) {
	$stm_text_user = '';
}

if(empty($stm_histories)) {
	$stm_histories = '';
}

if(empty($stm_phrases)) {
	$stm_phrases = '';
}


$car_edit = false;

if(!empty($_GET['edit_car']) and $_GET['edit_car']) {
	$car_edit = true;
}

$restricted = false;

if(is_user_logged_in()) {
	$user = wp_get_current_user();
	$user_id = $user->ID;
	$restrictions = stm_get_post_limits($user_id);
} else {
	$restrictions = stm_get_post_limits('');
}

if($restrictions['posts'] < 1) {
	$restricted = true;
}

?>

<?php if($restricted and !$car_edit): ?>
	<div class="stm-no-available-adds-overlay"></div>
	<div class="stm-no-available-adds">
		<h3><?php esc_html_e('Posts Available', 'motors'); ?>: <span>0</span></h3>
		<p><?php esc_html_e('You ended the limit of free classified ads. Please select one of the following', 'motors'); ?></p>
		<div class="clearfix">
			<?php if($restrictions['role'] == 'user'): ?>
				<a href="<?php echo esc_url(add_query_arg(array('become_dealer' => 1), stm_get_author_link(''))); ?>" class="button stm-green"><?php esc_html_e('Become a Dealer', 'motors'); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url(stm_get_author_link('')); ?>" class="button stm-green-dk"><?php esc_html_e('My inventory', 'motors'); ?></a>
		</div>
	</div>
<?php endif; ?>

<?php
	$bind_tax = stm_data_binding();
?>

<script type="text/javascript">
	var stmTaxRelations = <?php echo $bind_tax; ?>

	jQuery(document).ready(function(){
		var $ = jQuery;
		$('.stm_add_car_form .stm_add_car_form_1 .stm-form-1-selects select:not(.hide)').select2().on('change', function(){

			/*Remove disabled*/

			var stmCurVal = $(this).val();
			var stmCurSelect = $(this).attr('name');
			stmCurSelect = stmCurSelect.match(/\[(.*?)\]/)[1];

			if (stmTaxRelations[stmCurSelect]) {


				var key = stmTaxRelations[stmCurSelect]['dependency'];
				$('select[name="stm_f_s[' + key + ']"]').val('');
				if(stmCurVal == '') {
					$('select[name="stm_f_s[' + key + ']"] > option').each(function () {
						$(this).removeAttr('disabled');
					});
				} else {
					var allowedTerms = stmTaxRelations[stmCurSelect][stmCurVal];

					if(typeof(allowedTerms) == 'object') {
						$('select[name="stm_f_s[' + key + ']"] > option').removeAttr('disabled');

						$('select[name="stm_f_s[' + key + ']"] > option').each(function () {
							var optVal = $(this).val();
							if (optVal != '' && $.inArray(optVal, allowedTerms) == -1) {
								$(this).attr('disabled', '1');
							}
						});
					} else {
						$('select[name="stm_f_s[' + key + ']"]').val(allowedTerms);
					}
				}

				$('.stm_add_car_form .stm_add_car_form_1 .stm-form-1-selects select[name="stm_f_s[' + key + ']"]').select2("destroy");

				$('.stm_add_car_form .stm_add_car_form_1 .stm-form1-intro-unit select[name="stm_f_s[' + key + ']"]').select2();
			}
		});
	});
</script>


<!--CAR ADD-->

<?php if(!$car_edit): ?>
	<div class="stm_add_car_form<?php echo esc_attr($css_class); ?>">

		<form method="POST" action="" enctype="multipart/form-data" id="stm_sell_a_car_form">
			<div class="stm_add_car_form_1">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Car Details', 'motors'); ?></div>
					<span class="step_number step_number_1 heading-font"><?php esc_html_e('step','motors'); ?> 1</span>
				</div>
				<?php if(!empty($taxonomy)): ?>
					<div class="stm-form1-intro-unit">
						<div class="row">
							<?php foreach($taxonomy as $tax): ?>
								<?php
									$terms = stm_get_category_by_slug_all($tax);
									/*usort($terms, function($a, $b) {
										return strcmp($a->name, $b->name);
									});*/
								?>
								<div class="col-md-3 col-sm-3 stm-form-1-selects">
									<div class="stm-label heading-font"><?php esc_html_e(stm_get_name_by_slug($tax), 'motors'); ?>*</div>
									<select data-class="stm_select_overflowed" name="stm_f_s[<?php echo esc_attr($tax); ?>]">
										<option value=""><?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?></option>
										<?php if(!empty($terms)):
											foreach($terms as $term): ?>
												<option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<style type="text/css">
						<?php foreach($taxonomy as $tax): ?>

						.stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?>"] {
							background-color: transparent !important;
							border: 1px solid rgba(255,255,255,0.5);
							color: #fff !important;
						}
						.stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?>"] + .select2-selection__arrow b {
							color: rgba(255,255,255,0.5);
						}
						<?php endforeach; ?>
					</style>
				<?php endif; ?>

				<div class="stm-form-1-end-unit clearfix">
					<?php if(!empty($data)): ?>
						<?php foreach($data as $data_key => $data_unit): ?>
							<?php $terms = get_terms($data_unit['slug'], $terms_args); ?>
							<div class="stm-form-1-quarter">
								<?php if(!empty($data_unit['numeric']) and $data_unit['numeric']): ?>
									<input
										type="text"
										name="stm_s_s_<?php echo esc_attr($data_unit['slug']); ?>"
									    placeholder="<?php esc_html_e('Enter', 'motors'); ?> <?php esc_html_e($data_unit['single_name'], 'motors'); ?> <?php if(!empty($data_unit['number_field_affix'])){echo'(';esc_html_e($data_unit['number_field_affix'], 'motors');echo')';} ?>"
									/>
								<?php else: ?>
									<select name="stm_s_s_<?php echo esc_attr($data_unit['slug']) ?>">
										<option value=""><?php esc_html_e('Select', 'motors') ?> <?php esc_html_e($data_unit['single_name']); ?></option>
										<?php if(!empty($terms)):
											foreach($terms as $term): ?>
												<option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_attr($term->name); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								<?php endif; ?>
								<div class="stm-label">
									<?php if(!empty($data_unit['font'])): ?>
										<i class="<?php echo esc_attr($data_unit['font']); ?>"></i>
									<?php endif; ?>
									<?php esc_html_e($data_unit['single_name']) ?>
								</div>
							</div>
						<?php endforeach; ?>

						<style type="text/css">
							<?php foreach($data as $data_unit): ?>

							.stm-form-1-end-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__($data_unit['single_name'], 'motors'); ?>"] {
								background-color: transparent !important;
								border: 1px solid rgba(255,255,255,0.5);
								color: #888 !important;
							}
							<?php endforeach; ?>
						</style>

						<?php stm_add_a_car_addition_fields(false, $stm_histories); ?>

					<?php endif; ?>
				</div>
			</div>

			<div class="stm-form-2-features clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Select Your Car Features', 'motors'); ?></div>
					<span class="step_number step_number_2 heading-font"><?php esc_html_e('step','motors'); ?> 2</span>
				</div>
				<?php stm_add_a_car_features($items); ?>
			</div>

			<div class="stm-form-3-photos clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Upload photo', 'motors'); ?></div>
					<span class="step_number step_number_3 heading-font"><?php esc_html_e('step','motors'); ?> 3</span>
				</div>

				<div class="row">

					<div class="col-md-3 col-sm-12 col-md-push-9">
						<div class="stm-media-car-add-nitofication">
							<?php if(!empty($content)) {
								echo wpb_js_remove_wpautop( $content, true );
							} ?>
						</div>
					</div>

					<div class="col-md-9 col-sm-12 col-md-pull-3">
						<div class="stm-add-media-car">
							<div class="stm-media-car-main-input">
								<input type="file" name="stm_car_gallery_add[]" multiple />

								<div class="stm-placeholder">
									<i class="stm-service-icon-photos"></i>
									<a href="#" class="button stm_fake_button"><?php esc_html_e('Choose files', 'motors'); ?></a>
								</div>
							</div>
							<div class="stm-media-car-gallery clearfix">
								<?php $i = 1; while($i <= 5): $i++; ?>
									<div class="stm-placeholder stm-placeholder-native">
										<div class="inner">
											<i class="stm-service-icon-photos"></i>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="stm-form-4-videos clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Add Videos', 'motors'); ?></div>
					<span class="step_number step_number_4 heading-font"><?php esc_html_e('step','motors'); ?> 4</span>
				</div>
				<div class="stm-add-videos-unit">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="stm-video-units">
								<div class="stm-video-link-unit-wrap">
									<div class="heading-font">
										<span class="video-label"><?php esc_html_e('Video link', 'motors');?></span> <span class="count">1</span></div>
									<div class="stm-video-link-unit">
										<input type="text" name="stm_video[]" />
										<div class="stm-after-video"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="stm-simple-notice">
								<i class="fa fa-info-circle"></i>
								<?php esc_html_e('If you don\'t have the videos handy, don\'t worry. You can add or edit them after you complete your ad using the "Manage Your Ad" page.', 'motors'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="stm-form-5-notes clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Enter Seller\'s notes', 'motors'); ?></div>
					<span class="step_number step_number_5 heading-font"><?php esc_html_e('step','motors'); ?> 5</span>
				</div>
				<div class="row stm-relative">
					<div class="col-md-9 col-sm-9 stm-non-relative">
						<div class="stm-phrases-unit">
							<?php if(!empty($stm_phrases)): $stm_phrases = explode(',',$stm_phrases); ?>
							<div class="stm_phrases">
								<div class="inner">
									<i class="fa fa-close"></i>
									<h5><?php esc_html_e('Select all the phrases that apply to your vehicle.', 'motors'); ?></h5>
									<?php if(!empty($stm_phrases)): ?>
										<div class="clearfix">
											<?php foreach($stm_phrases as $phrase): ?>
												<label>
													<input type="checkbox" name="stm_phrase" value="<?php echo esc_attr($phrase); ?>" />
													<span><?php echo esc_attr($phrase); ?></span>
												</label>
											<?php endforeach; ?>
										</div>
										<a href="#" class="button"><?php esc_html_e('Apply', 'motors'); ?></a>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
							<textarea placeholder="<?php esc_html_e('Enter Seller\'s notes', 'motors'); ?>" name="stm_seller_notes"></textarea>
						</div>
					</div>
					<?php if(!empty($stm_phrases)): ?>
						<div class="col-md-3 col-sm-3 hidden-xs">

							<div class="stm-seller-notes-phrases heading-font"><span><?php esc_html_e('Add the Template Phrases', 'motors'); ?></span></div>

						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="stm-form-price-edit">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Set Your Asking Price', 'motors'); ?></div>
					<span class="step_number step_number_5 heading-font"><?php esc_html_e('step','motors'); ?> 6</span>
				</div>
				<div class="row stm-relative">
					<div class="col-md-4 col-sm-6">
						<div class="stm_price_input">
							<div class="stm_label heading-font"><?php esc_html_e('Price', 'motors'); ?>* (<?php echo stm_get_price_currency(); ?>)</div>
							<input type="number" class="heading-font" name="stm_car_price" required/>
						</div>
					</div>
					<div class="col-md-8 col-sm-6">
						<?php if(!empty($stm_title_price)): ?>
							<h4><?php echo esc_attr($stm_title_price); ?></h4>
						<?php endif; ?>
						<?php if(!empty($stm_title_desc)): ?>
							<p><?php echo esc_attr($stm_title_desc); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</form>

		<div class="stm-form-checking-user">
			<div class="stm-form-inner">
				<i class="stm-icon-load1"></i>
				<?php $restricted = false; ?>
				<?php if(is_user_logged_in()):
					$disabled = 'enabled';
					?>
					<div id="stm_user_info">
						<?php stm_add_a_car_user_info('','','',$user_id); ?>
					</div>
				<?php else:
					$disabled = 'disabled'; ?>
						<div id="stm_user_info" style="display:none;"></div>
					<?php
				endif; ?>

				<div class="stm-not-<?php echo $disabled; ?>">
					<?php stm_add_a_car_registration($stm_title_user, $stm_text_user); ?>
					<div class="stm-add-a-car-login-overlay"></div>
					<div class="stm-add-a-car-login">
						<div class="stm-login-form">
							<form method="post">
								<input type="hidden" name="redirect" value="disable">
								<div class="form-group">
									<h4><?php esc_html_e('Login or E-mail', 'motors'); ?></h4>
									<input type="text" name="stm_user_login" placeholder="<?php esc_html_e('Enter login or E-mail', 'motors'); ?>">
								</div>

								<div class="form-group">
									<h4><?php esc_html_e('Password', 'motors'); ?></h4>
									<input type="password" name="stm_user_password" placeholder="<?php esc_html_e('Enter password', 'motors'); ?>">
								</div>

								<div class="form-group form-checker">
									<label>
										<input type="checkbox" name="stm_remember_me">
										<span><?php esc_html_e('Remember me', 'motors'); ?></span>
									</label>
								</div>
								<input type="submit" value="Login">
								<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
								<div class="stm-validation-message"></div>
							</form>
						</div>
					</div>
				</div>

				<button type="submit" class="<?php echo esc_attr($disabled); ?>">
					<i class="stm-service-icon-add_check"></i><?php esc_html_e('Submit listing', 'motors'); ?>
				</button>
				<span class="stm-add-a-car-loader"><i class="stm-icon-load1"></i></span>

				<div class="stm-add-a-car-message heading-font"></div>
			</div>
		</div>

	</div>



<?php else: ?>
	<?php if(!is_user_logged_in()) {
		echo '<h4>' . esc_html__('Please login.', 'motors') . '</h4>';
		return false;
	}

	if(!empty($_GET['item_id'])) {
		$item_id = intval($_GET['item_id']);

		$car_user = get_post_meta($item_id, 'stm_car_user', true);

		if(intval($user_id) != intval($car_user)) {
			echo '<h4>' . esc_html__('You are not the owner of this car.', 'motors') . '</h4>';
			return false;
		}
	} else {
		echo '<h4>' . esc_html__('No car to edit.', 'motors') . '</h4>';
		return false;
	}

	?>
<!--CAR EDIT-->
	<div class="stm_edit_car_form stm_add_car_form<?php echo esc_attr($css_class); ?>">

		<form method="POST" action="" enctype="multipart/form-data" id="stm_sell_a_car_form">
			<input type="hidden" value="<?php echo intval($item_id); ?>" name="stm_current_car_id"/>
			<div class="stm_add_car_form_1">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Car Details', 'motors'); ?></div>
					<span class="step_number step_number_1 heading-font"><?php esc_html_e('step','motors'); ?> 1</span>
				</div>
				<?php if(!empty($taxonomy)): ?>
					<div class="stm-form1-intro-unit">
						<div class="row">
							<?php foreach($taxonomy as $tax): ?>
								<?php
								$has_selected = '';
								$terms = get_terms($tax, $terms_args);
								$post_terms = wp_get_post_terms($item_id, $tax);
								if(!empty($post_terms[0])) {
									$has_selected = $post_terms[0]->slug;
								}
								?>
								<div class="col-md-3 col-sm-3 stm-form-1-selects">
									<div class="stm-label heading-font"><?php esc_html_e(stm_get_name_by_slug($tax), 'motors'); ?>*</div>
									<select name="stm_f_s[<?php echo esc_attr($tax); ?>]">
										<option value=""><?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?></option>
										<?php if(!empty($terms)):
											foreach($terms as $term): ?>
												<option value="<?php echo esc_attr($term->slug); ?>" <?php if($term->slug == $has_selected) echo 'selected'; ?>><?php echo esc_attr($term->name); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<style type="text/css">
						<?php foreach($taxonomy as $tax): ?>

						.stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?>"] {
							background-color: transparent !important;
							border: 1px solid rgba(255,255,255,0.5);
							color: #fff !important;
						}
						.stm-form1-intro-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__(stm_get_name_by_slug($tax), 'motors'); ?>"] + .select2-selection__arrow b {
							color: rgba(255,255,255,0.5);
						}
						<?php endforeach; ?>
					</style>
				<?php endif; ?>

				<div class="stm-form-1-end-unit clearfix">
					<?php if(!empty($data)): ?>
						<?php foreach($data as $data_key => $data_unit): ?>
							<?php
								$terms = get_terms($data_unit['slug'], $terms_args);
							?>
							<div class="stm-form-1-quarter">
								<?php if(!empty($data_unit['numeric']) and $data_unit['numeric']): ?>
									<?php
										$data_value = '';
										$data_value = get_post_meta($item_id, $data_unit['slug'], true);
									?>
									<input
										type="text"
										name="stm_s_s_<?php echo esc_attr($data_unit['slug']); ?>"
										<?php if(!empty($data_value)): ?>class="stm_has_value" <?php endif; ?>
										value="<?php echo $data_value; ?>"
										placeholder="<?php esc_html_e('Enter', 'motors'); ?> <?php esc_html_e($data_unit['single_name'], 'motors'); ?> <?php if(!empty($data_unit['number_field_affix'])){echo'(';esc_html_e($data_unit['number_field_affix'], 'motors');echo')';} ?>"
										/>
								<?php else: ?>
									<?php
									$post_terms = wp_get_post_terms($item_id, $data_unit['slug']);
									$has_selected = '';
									if(!empty($post_terms[0])) {
										$has_selected = $post_terms[0]->slug;
									}
									?>
									<select name="stm_s_s_<?php echo esc_attr($data_unit['slug']) ?>">
										<option value=""><?php esc_html_e('Select', 'motors') ?> <?php esc_html_e($data_unit['single_name']); ?></option>
										<?php if(!empty($terms)):
											foreach($terms as $term): ?>
												<option value="<?php echo esc_attr($term->slug); ?>" <?php if($term->slug == $has_selected) echo 'selected'; ?>><?php echo esc_attr($term->name); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								<?php endif; ?>
								<div class="stm-label">
									<?php if(!empty($data_unit['font'])): ?>
										<i class="<?php echo esc_attr($data_unit['font']); ?>"></i>
									<?php endif; ?>
									<?php esc_html_e($data_unit['single_name']) ?>
								</div>
							</div>
						<?php endforeach; ?>

						<style type="text/css">
							<?php foreach($data as $data_unit): ?>

							.stm-form-1-end-unit .select2-selection__rendered[title="<?php esc_html_e('Select', 'motors'); ?> <?php echo esc_html__($data_unit['single_name'], 'motors'); ?>"] {
								background-color: transparent !important;
								border: 1px solid rgba(255,255,255,0.5);
								color: #888 !important;
							}
							<?php endforeach; ?>
						</style>

						<?php stm_add_a_car_addition_fields(false, $stm_histories, $item_id); ?>

					<?php endif; ?>
				</div>
			</div>

			<div class="stm-form-2-features clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Select Your Car Features', 'motors'); ?></div>
					<span class="step_number step_number_2 heading-font"><?php esc_html_e('step','motors'); ?> 2</span>
				</div>
				<?php stm_add_a_car_features($items, false, $item_id); ?>
			</div>

			<div class="stm-form-3-photos clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Upload photo', 'motors'); ?></div>
					<span class="step_number step_number_3 heading-font"><?php esc_html_e('step','motors'); ?> 3</span>
				</div>

				<div class="row">

					<div class="col-md-3 col-sm-12 col-md-push-9">
						<div class="stm-media-car-add-nitofication">
							<?php if(!empty($content)) {
								echo wpb_js_remove_wpautop( $content, true );
							} ?>
						</div>
					</div>

					<div class="col-md-9 col-sm-12 col-md-pull-3">
						<div class="stm-add-media-car">
							<div class="stm-media-car-main-input">
								<input type="file" name="stm_car_gallery_add[]" multiple />

								<div class="stm-placeholder hasPreviews">
									<?php
										$featured_image = get_post_thumbnail_id($item_id);
										$image = '';
										if(!empty($featured_image)) {
											$image = wp_get_attachment_image_src($featured_image, 'stm-img-796-466');
											if(!empty($image[0])) {
												$image = 'style=background:url("' . $image[0] . '")';
											}
										}
									?>

									<i class="stm-service-icon-photos"></i>
									<a href="#" class="button stm_fake_button"><?php esc_html_e('Choose files', 'motors'); ?></a>
								</div>
								<?php if(!empty($image)): ?>
									<div class="stm-image-preview stm-placeholder-generated-php" <?php echo esc_attr($image); ?>></div>
								<?php endif; ?>
							</div>

							<?php $images_js = array(); ?>
							<?php if(!empty($image)): $images_js[] = $featured_image; ?>
								<div class="stm-media-car-gallery clearfix">
									<div class="stm-placeholder stm-placeholder-generated stm-placeholder-generated-php">
										<div class="inner">
											<div class="stm-image-preview" data-media="<?php echo esc_attr($featured_image); ?>" data-id="0" <?php echo esc_attr($image); ?>>
												<i class="fa fa-remove fa-remove-loaded" data-id="0" data-media="<?php echo esc_attr($featured_image); ?>"></i>
											</div>
										</div>
									</div>
									<?php $gallery = get_post_meta($item_id, 'gallery', true); ?>
									<?php if(!empty($gallery)): ?>
										<?php foreach($gallery as $gallery_key => $gallery_id): ?>
											<?php $image = '';
											if(!empty($gallery_id)) {
												$image = wp_get_attachment_image_src($gallery_id, 'stm-img-796-466');
												if(!empty($image[0])) {
													$image = 'style=background:url("' . $image[0] . '")';
													$images_js[] = intval($gallery_id);
												}
											}

											if(!empty($image)): ?>
												<div class="stm-placeholder stm-placeholder-generated stm-placeholder-generated-php">
													<div class="inner">
														<div class="stm-image-preview" data-media="<?php echo intval($gallery_id); ?>" data-id="<?php echo esc_attr($gallery_key + 1); ?>" <?php echo esc_attr($image); ?>>
															<i class="fa fa-remove fa-remove-loaded" data-id="<?php echo esc_attr($gallery_key + 1); ?>" data-media="<?php echo intval($gallery_id); ?>"></i>
														</div>
													</div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

								<script type="text/javascript">
									var stmUserFilesLoaded = [
										<?php foreach($images_js as $image_js) {
											echo intval($image_js) . ',';
										} ?>
									]
								</script>
							<?php else: ?>
								<div class="stm-media-car-gallery clearfix">
									<?php $i = 1; while($i <= 5): $i++; ?>
										<div class="stm-placeholder stm-placeholder-native">
											<div class="inner">
												<i class="stm-service-icon-photos"></i>
											</div>
										</div>
									<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

				</div>

			</div>

			<div class="stm-form-4-videos clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Add Videos', 'motors'); ?></div>
					<span class="step_number step_number_4 heading-font"><?php esc_html_e('step','motors'); ?> 4</span>
				</div>
				<?php $has_videos = false; ?>
				<div class="stm-add-videos-unit">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="stm-video-units">
								<div class="stm-video-link-unit-wrap">
									<div class="heading-font">
										<span class="video-label"><?php esc_html_e('Video link', 'motors');?></span> <span class="count">1</span>
									</div>
									<?php
										$video = get_post_meta($item_id, 'gallery_video', true);
										if(empty($video)) {
											$video = '';
										} else {
											$has_videos = true;
										}
									?>
									<div class="stm-video-link-unit">
										<input type="text" name="stm_video[]" value="<?php echo esc_url($video); ?>"/>
										<div class="stm-after-video active"></div>
									</div>
									<?php if($has_videos): ?>
										<?php $gallery_videos = get_post_meta($item_id, 'gallery_videos', true);
										if(!empty($gallery_videos)): ?>
											<?php foreach($gallery_videos as $gallery_video): ?>
												<div class="stm-video-link-unit">
													<input type="text" name="stm_video[]" value="<?php echo esc_url($gallery_video); ?>"/>
													<div class="stm-after-video active"></div>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<div class="stm-simple-notice">
								<i class="fa fa-info-circle"></i>
								<?php esc_html_e('If you don\'t have the videos handy, don\'t worry. You can add or edit them after you complete your ad using the "Manage Your Ad" page.', 'motors'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="stm-form-5-notes clearfix">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Enter Seller\'s notes', 'motors'); ?></div>
					<span class="step_number step_number_5 heading-font"><?php esc_html_e('step','motors'); ?> 5</span>
				</div>
				<div class="row stm-relative">
					<div class="col-md-9 col-sm-9 stm-non-relative">
						<div class="stm-phrases-unit">
							<?php if(!empty($stm_phrases)): $stm_phrases = explode(',',$stm_phrases); ?>
								<div class="stm_phrases">
									<div class="inner">
										<i class="fa fa-close"></i>
										<h5><?php esc_html_e('Select all the phrases that apply to your vehicle.', 'motors'); ?></h5>
										<?php if(!empty($stm_phrases)): ?>
											<div class="clearfix">
												<?php foreach($stm_phrases as $phrase): ?>
													<label>
														<input type="checkbox" name="stm_phrase" value="<?php echo esc_attr($phrase); ?>" />
														<span><?php echo esc_attr($phrase); ?></span>
													</label>
												<?php endforeach; ?>
											</div>
											<a href="#" class="button"><?php esc_html_e('Apply', 'motors'); ?></a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php
								$note = get_post_field('post_content', $item_id);
								if(empty($note)) {
									$note = '';
								} else {
									$note_title = array(
										'<',
										'>',
										'div class="stm-car-listing-data-single stm-border-top-unit"',
										'div class="title heading-font"',
										esc_html__('Seller Note', 'motors'),
										'/div',
									);

									$post_data['post_content'] = '<div class="stm-car-listing-data-single stm-border-top-unit">';
									$post_data['post_content'] .= '<div class="title heading-font">'.esc_html__('Seller Note', 'motors').'</div></div>';

									$note = substr(str_replace($note_title, '', $note), 4);
								}
							?>
							<textarea placeholder="<?php esc_html_e('Enter Seller\'s notes', 'motors'); ?>" name="stm_seller_notes"><?php echo esc_attr($note); ?></textarea>
						</div>
					</div>
					<?php if(!empty($stm_phrases)): ?>
						<div class="col-md-3 col-sm-3 hidden-xs">

							<div class="stm-seller-notes-phrases heading-font"><span><?php esc_html_e('Add the Template Phrases', 'motors'); ?></span></div>

						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="stm-form-price-edit">
				<div class="stm-car-listing-data-single stm-border-top-unit ">
					<div class="title heading-font"><?php esc_html_e('Set Your Asking Price', 'motors'); ?></div>
					<span class="step_number step_number_5 heading-font"><?php esc_html_e('step','motors'); ?> 6</span>
				</div>
				<?php
					$price = get_post_meta($item_id, 'price', true);
					if(empty($price)) {
						$price = '';
					}
				?>
				<div class="row stm-relative">
					<div class="col-md-4 col-sm-4">
						<div class="stm_price_input">
							<div class="stm_label heading-font"><?php esc_html_e('Price', 'motors'); ?>* (<?php echo stm_get_price_currency(); ?>)</div>
							<input type="number" class="heading-font" name="stm_car_price" value="<?php echo intval($price); ?>" required/>
						</div>
					</div>
					<div class="col-md-8 col-sm-8">
						<?php if(!empty($stm_title_price)): ?>
							<h4><?php echo esc_attr($stm_title_price); ?></h4>
						<?php endif; ?>
						<?php if(!empty($stm_title_desc)): ?>
							<p><?php echo esc_attr($stm_title_desc); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</form>

		<div class="stm-form-checking-user">
			<div class="stm-form-inner">
				<i class="stm-icon-load1"></i>
				<?php $restricted = false; ?>
				<?php if(is_user_logged_in()):
					$disabled = 'enabled';
					?>
					<div id="stm_user_info">
						<?php stm_add_a_car_user_info('','','',$user_id); ?>
					</div>
				<?php else:
					$disabled = 'disabled'; ?>
					<div id="stm_user_info" style="display:none;"></div>
				<?php
				endif; ?>

				<div class="stm-not-<?php echo $disabled; ?>">
					<?php stm_add_a_car_registration($stm_title_user, $stm_text_user); ?>
					<div class="stm-add-a-car-login-overlay"></div>
					<div class="stm-add-a-car-login">
						<div class="stm-login-form">
							<form method="post">
								<input type="hidden" name="redirect" value="disable">
								<div class="form-group">
									<h4><?php esc_html_e('Login or E-mail', 'motors'); ?></h4>
									<input type="text" name="stm_user_login" placeholder="<?php esc_html_e('Enter login or E-mail', 'motors'); ?>">
								</div>

								<div class="form-group">
									<h4><?php esc_html_e('Password', 'motors'); ?></h4>
									<input type="password" name="stm_user_password" placeholder="<?php esc_html_e('Enter password', 'motors'); ?>">
								</div>

								<div class="form-group form-checker">
									<label>
										<input type="checkbox" name="stm_remember_me">
										<span><?php esc_html_e('Remember me', 'motors'); ?></span>
									</label>
								</div>
								<input type="submit" value="Login">
								<span class="stm-listing-loader"><i class="stm-icon-load1"></i></span>
								<div class="stm-validation-message"></div>
							</form>
						</div>
					</div>
				</div>

				<button type="submit" class="<?php echo esc_attr($disabled); ?>">
					<i class="stm-service-icon-add_check"></i><?php esc_html_e('Edit Ads', 'motors'); ?>
				</button>
				<span class="stm-add-a-car-loader"><i class="stm-icon-load1"></i></span>

				<div class="stm-add-a-car-message heading-font"></div>
			</div>
		</div>

	</div>

<?php endif; ?>
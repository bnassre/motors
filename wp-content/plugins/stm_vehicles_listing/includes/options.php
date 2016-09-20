<?php
// Add options page to menu
function add_theme_menu_item() {
	add_submenu_page(
		'edit.php?post_type=listings',
		__("Listing Categories", STM_VEHICLE_LISTING),
		__("Listing Categories", STM_VEHICLE_LISTING),
		'manage_options',
		'listing_categories',
		'stm_vehicle_listing_settings_page'
	);
}

add_action("admin_menu", "add_theme_menu_item");

function stm_vehicle_listing_settings_page() {
	$stm_timetable_options['rewrite'] = '';

	$options = array();

	$options = get_option( 'stm_vehicle_listing_options' );

	//Add new
	if ( ! empty( $_POST['stm_vehicle_listing_options'] ) ) {
		$getted_post_array = $_POST['stm_vehicle_listing_options'];

		if ( $getted_post_array['single_name'] != '' and $getted_post_array['plural_name'] != '' and $getted_post_array['slug'] != ''  ) {

			if(taxonomy_exists($getted_post_array['slug'])) {
				$new_taxonomy_slug = sanitize_title('ca-'.$getted_post_array['slug']);
			} else {
				$new_taxonomy_slug = sanitize_title($getted_post_array['slug']);
			}

			$edited_post = array(
				'single_name' => esc_attr( stripslashes($getted_post_array['single_name']) ),
				'plural_name' => esc_attr( stripslashes($getted_post_array['plural_name']) ),
				'slug'        => $new_taxonomy_slug,
				'font'        => esc_attr( $getted_post_array['font_icon'] ),
			);

			//Check if number
			if(!empty($getted_post_array['numeric']) and $getted_post_array['numeric'] == 'on') {
				$edited_post['numeric'] = true;
			} else {
				$edited_post['numeric'] = false;
			}

			//If category will be used on single page
			if(!empty($getted_post_array['use_on_single_listing_page']) and $getted_post_array['use_on_single_listing_page'] == 'on') {
				$edited_post['use_on_single_listing_page'] = true;
			} else {
				$edited_post['use_on_single_listing_page'] = false;
			}

			//If category will be used on listing archive page
			if(!empty($getted_post_array['use_on_car_listing_page']) and $getted_post_array['use_on_car_listing_page'] == 'on') {
				$edited_post['use_on_car_listing_page'] = true;
			} else {
				$edited_post['use_on_car_listing_page'] = false;
			}

			//If category will be used on listing archive page
			if(!empty($getted_post_array['use_on_car_archive_listing_page']) and $getted_post_array['use_on_car_archive_listing_page'] == 'on') {
				$edited_post['use_on_car_archive_listing_page'] = true;
			} else {
				$edited_post['use_on_car_archive_listing_page'] = false;
			}

			//If category will be used on single car page
			if(!empty($getted_post_array['use_on_single_car_page']) and $getted_post_array['use_on_single_car_page'] == 'on') {
				$edited_post['use_on_single_car_page'] = true;
			} else {
				$edited_post['use_on_single_car_page'] = false;
			}

			//Will be used in filter
			if(!empty($getted_post_array['use_on_car_modern_filter']) and $getted_post_array['use_on_car_modern_filter'] == 'on') {
				$edited_post['use_on_car_modern_filter'] = true;
			} else {
				$edited_post['use_on_car_modern_filter'] = false;
			}

			if(!empty($getted_post_array['use_on_car_modern_filter_view_images']) and $getted_post_array['use_on_car_modern_filter_view_images'] == 'on') {
				$edited_post['use_on_car_modern_filter_view_images'] = true;
			} else {
				$edited_post['use_on_car_modern_filter_view_images'] = false;
			}

			//Will be used in filter
			if(!empty($getted_post_array['use_on_car_filter']) and $getted_post_array['use_on_car_filter'] == 'on') {
				$edited_post['use_on_car_filter'] = true;
			} else {
				$edited_post['use_on_car_filter'] = false;
			}

			//Will be used in filter as links
			if(!empty($getted_post_array['use_on_car_filter_links']) and $getted_post_array['use_on_car_filter_links'] == 'on') {
				$edited_post['use_on_car_filter_links'] = true;
			} else {
				$edited_post['use_on_car_filter_links'] = false;
			}

			//Listing adds

			//Will be used in Listing Filter title
			if(!empty($getted_post_array['use_on_directory_filter_title']) and $getted_post_array['use_on_directory_filter_title'] == 'on') {
				$edited_post['use_on_directory_filter_title'] = true;
			} else {
				$edited_post['use_on_directory_filter_title'] = false;
			}

			if(!empty($getted_post_array['number_field_affix'])) {
				$edited_post['number_field_affix'] = esc_attr($getted_post_array['number_field_affix']);
			}

			if(!empty($getted_post_array['listing_rows_numbers'])) {
				$edited_post['listing_rows_numbers'] = esc_attr($getted_post_array['listing_rows_numbers']);
			}

			if(!empty($getted_post_array['listing_taxonomy_parent'])) {
				$edited_post['listing_taxonomy_parent'] = esc_attr($getted_post_array['listing_taxonomy_parent']);
			}

			if(!empty($getted_post_array['enable_checkbox_button']) and $getted_post_array['enable_checkbox_button'] == 'on') {
				$edited_post['enable_checkbox_button'] = true;
			} else {
				$edited_post['enable_checkbox_button'] = false;
			}

			if(!empty($getted_post_array['use_in_footer_search']) and $getted_post_array['use_in_footer_search'] == 'on') {
				$edited_post['use_in_footer_search'] = true;
			} else {
				$edited_post['use_in_footer_search'] = false;
			}


			$options[] = $edited_post;

			update_option( 'stm_vehicle_listing_options', $options );
		}
	}

	//	Deleting item
	if(isset($_GET['delete'])){
		unset($options[$_GET['delete']]);
		update_option( 'stm_vehicle_listing_options', $options );
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				window.history.pushState('','',"<?php echo get_home_url().remove_query_arg('delete', false); ?>");
			})
		</script>
		<?php
	}

	//Editing item
	if ( ! empty( $_POST['stm_listing_edit'] ) ) {
		$edit_exist_item = $_POST['stm_listing_edit'];

		if ( $edit_exist_item['new_single_name'] != '' and $edit_exist_item['new_plural_name'] != '' and $edit_exist_item['old_slug'] != '' ) {
			$edit_exist = array(
				'single_name' => esc_attr( stripslashes($edit_exist_item['new_single_name']) ),
				'plural_name' => esc_attr( stripslashes($edit_exist_item['new_plural_name']) ),
				'slug'        => sanitize_title( $edit_exist_item['old_slug'] ),
				'font'        => esc_attr( $edit_exist_item['font_icon'] ),
			);

			if(!empty($edit_exist_item['edit_numeric']) and $edit_exist_item['edit_numeric'] == 'on') {
				$edit_exist['numeric'] = true;
			} else {
				$edit_exist['numeric'] = false;
			}

			if(!empty($edit_exist_item['use_on_single_listing_page']) and $edit_exist_item['use_on_single_listing_page'] == 'on') {
				$edit_exist['use_on_single_listing_page'] = true;
			} else {
				$edit_exist['use_on_single_listing_page'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_listing_page']) and $edit_exist_item['use_on_car_listing_page'] == 'on') {
				$edit_exist['use_on_car_listing_page'] = true;
			} else {
				$edit_exist['use_on_car_listing_page'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_archive_listing_page']) and $edit_exist_item['use_on_car_archive_listing_page'] == 'on') {
				$edit_exist['use_on_car_archive_listing_page'] = true;
			} else {
				$edit_exist['use_on_car_archive_listing_page'] = false;
			}

			if(!empty($edit_exist_item['use_on_single_car_page']) and $edit_exist_item['use_on_single_car_page'] == 'on') {
				$edit_exist['use_on_single_car_page'] = true;
			} else {
				$edit_exist['use_on_single_car_page'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_filter']) and $edit_exist_item['use_on_car_filter'] == 'on') {
				$edit_exist['use_on_car_filter'] = true;
			} else {
				$edit_exist['use_on_car_filter'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_modern_filter']) and $edit_exist_item['use_on_car_modern_filter'] == 'on') {
				$edit_exist['use_on_car_modern_filter'] = true;
			} else {
				$edit_exist['use_on_car_modern_filter'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_modern_filter_view_images']) and $edit_exist_item['use_on_car_modern_filter_view_images'] == 'on') {
				$edit_exist['use_on_car_modern_filter_view_images'] = true;
			} else {
				$edit_exist['use_on_car_modern_filter_view_images'] = false;
			}

			if(!empty($edit_exist_item['use_on_car_filter_links']) and $edit_exist_item['use_on_car_filter_links'] == 'on') {
				$edit_exist['use_on_car_filter_links'] = true;
			} else {
				$edit_exist['use_on_car_filter_links'] = false;
			}

			if(!empty($edit_exist_item['use_on_directory_filter_title']) and $edit_exist_item['use_on_directory_filter_title'] == 'on') {
				$edit_exist['use_on_directory_filter_title'] = true;
			} else {
				$edit_exist['use_on_directory_filter_title'] = false;
			}

			if(!empty($edit_exist_item['number_field_affix'])) {
				$edit_exist['number_field_affix'] = esc_attr($edit_exist_item['number_field_affix']);
			}

			if(!empty($edit_exist_item['listing_rows_numbers'])) {
				$edit_exist['listing_rows_numbers'] = esc_attr($edit_exist_item['listing_rows_numbers']);
			}

			if(!empty($edit_exist_item['listing_taxonomy_parent'])) {
				$edit_exist['listing_taxonomy_parent'] = esc_attr($edit_exist_item['listing_taxonomy_parent']);
			}

			if(!empty($edit_exist_item['enable_checkbox_button']) and $edit_exist_item['enable_checkbox_button'] == 'on') {
				$edit_exist['enable_checkbox_button'] = true;
			} else {
				$edit_exist['enable_checkbox_button'] = false;
			}

			if(!empty($edit_exist_item['use_in_footer_search']) and $edit_exist_item['use_in_footer_search'] == 'on') {
				$edit_exist['use_in_footer_search'] = true;
			} else {
				$edit_exist['use_in_footer_search'] = false;
			}

			$options[$edit_exist_item['edit_id']] = $edit_exist;
			update_option( 'stm_vehicle_listing_options', $options );
		}
	}


	//	Save new order
	if(!empty( $_POST['stm_new_posts_order'] )) {
		$new_options = explode(",", $_POST['stm_new_posts_order']);

		$original_order = $options;

		$new_order = array();

		foreach ($new_options as $old_option_key => $new_option_key) {
			$new_order[$old_option_key + 1] = $original_order[$new_option_key];
		}


		$options = $new_order;

		update_option( 'stm_vehicle_listing_options', $new_order );
	}


	?>

	<div id="col-container">
		<h2 class="clear"><?php _e( 'Vehicle listing Settings', STM_VEHICLE_LISTING ); ?></h2>
		<div id="col-right">
			<div class="col-wrap">
				<form method="post" id="stm_posts_filter" action="<?php echo get_home_url(); ?>/wp-admin/edit.php?post_type=listings&page=listing_categories">
					<table class="wp-list-table widefat listing_categories">
						<thead>
						<tr>
							<th scope="col" class="manage-column column-singular">
								<span><?php _e('Singular', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-plural">
								<span><?php _e('Plural', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-slug">
								<span><?php _e('Slug', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-number-field">
								<span><?php _e('Numeric', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-manage">
								<span><?php _e('Manage', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-edit">
								<span><?php _e('Edit', STM_VEHICLE_LISTING ); ?></span>
							</th>
							<th scope="col" class="manage-column column-delete">
								<span><?php _e('Delete', STM_VEHICLE_LISTING ); ?></span>
							</th>
						</tr>
						</thead>
						<tbody class="stm-ui-sortable">
						<?php
						if ( ! empty( $options ) ):
							foreach ( $options as $option_key => $option ): ?>

								<tr id="<?php echo esc_attr($option_key); ?>">
									<?php foreach ( $option as $single_option_key => $single_option ): ?>
										<?php if($single_option_key == 'numeric'): ?>
											<td>
												<?php if(empty($single_option)) {
													echo esc_attr( __( 'No', STM_VEHICLE_LISTING  ) );
												} else {
													echo esc_attr( __( 'Yes', STM_VEHICLE_LISTING  ) );
												}
												?>
											</td>
										<?php elseif($single_option_key == 'use_on_single_listing_page' or $single_option_key =='use_on_car_listing_page' or $single_option_key =='use_on_car_archive_listing_page' or $single_option_key =='use_on_single_car_page' or $single_option_key =='use_on_car_filter' or $single_option_key =='use_on_car_modern_filter'  or $single_option_key =='use_on_car_modern_filter_view_images' or $single_option_key =='use_on_car_filter_links' or $single_option_key == 'use_on_directory_filter_title' or $single_option_key == 'number_field_affix' or $single_option_key == 'listing_rows_numbers' or $single_option_key == 'enable_checkbox_button' or $single_option_key == 'use_in_footer_search' or $single_option_key == 'listing_taxonomy_parent' or $single_option_key == 'font'): ?>

										<?php else: ?>
											<td>
												<?php echo __($single_option, STM_VEHICLE_LISTING ); ?>
											</td>
										<?php endif; ?>
									<?php endforeach; ?>
									<td>
										<a href="<?php echo get_home_url(); ?>/wp-admin/edit-tags.php?taxonomy=<?php echo esc_attr($option['slug']); ?>&post_type=listings">
											<i class="dashicons dashicons-admin-tools"></i>
										</a>
									</td>
									<td>
										<a
											class="stm_edit_item"
											data-id="<?php echo esc_attr($option_key); ?>"
											data-name="<?php echo esc_attr($option['single_name']) ?>"
											data-plural="<?php echo esc_attr($option['plural_name']) ?>"
											data-slug="<?php echo esc_attr($option['slug']) ?>"
											data-numeric="<?php echo esc_attr($option['numeric']) ?>"
											data-use-on-listing="<?php echo esc_attr($option['use_on_single_listing_page']) ?>"
											data-use-on-car-listing="<?php echo esc_attr($option['use_on_car_listing_page']) ?>"
											data-use-on-single-car-page="<?php echo esc_attr($option['use_on_single_car_page']) ?>"
											data-use-on-car-filter="<?php echo esc_attr($option['use_on_car_filter']) ?>"
											data-use-on-car-modern-filter="<?php echo esc_attr($option['use_on_car_modern_filter']) ?>"
											data-use-on-car-modern-filter-view-images="<?php echo esc_attr($option['use_on_car_modern_filter_view_images']) ?>"
											data-use-on-car-filter-links="<?php echo esc_attr($option['use_on_car_filter_links']) ?>"


											<?php if(!empty($option['use_on_directory_filter_title'])): ?>
												data-use-on-directory-filter-title="<?php echo esc_attr($option['use_on_directory_filter_title']) ?>"
											<?php endif; ?>

											<?php if(!empty($option['number_field_affix'])): ?>
												data-number-field-affix="<?php echo esc_attr($option['number_field_affix']) ?>"
											<?php endif; ?>

											<?php if(!empty($option['font'])){ ?>
												data-font="<?php echo $option['font']; ?>"
											<?php } ?>

											<?php if(!empty($option['use_on_car_archive_listing_page'])){ ?>
												data-use-on-car-archive-listing="<?php echo esc_attr($option['use_on_car_archive_listing_page']) ?>"
											<?php } ?>

											<?php if(!empty($option['listing_rows_numbers'])){ ?>
												data-listing-rows-numbers="<?php echo esc_attr($option['listing_rows_numbers']) ?>"
											<?php } ?>

											<?php if(!empty($option['listing_taxonomy_parent'])){ ?>
												data-use-listing_taxonomy_parent="<?php echo esc_attr($option['listing_taxonomy_parent']) ?>"
											<?php } ?>

											<?php if(!empty($option['enable_checkbox_button'])){ ?>
												data-enable-checkbox-button="<?php echo esc_attr($option['enable_checkbox_button']) ?>"
											<?php } ?>

											<?php if(!empty($option['use_in_footer_search'])){ ?>
												data-use-in-footer-search="<?php echo esc_attr($option['use_in_footer_search']) ?>"
											<?php } ?>

											href=""
											>
											<i class="dashicons dashicons-edit"></i>
										</a>
									</td>
									<td>
										<a class="stm_delete_item" href="<?php echo esc_url( add_query_arg( 'delete', $option_key, 'edit.php?post_type=listings&amp;page=listing_categories' ) ); ?>">
											<i class="dashicons dashicons-no"></i>
										</a>
									</td>
								</tr>

							<?php
							endforeach;
						endif;
						?>
						</tbody>
					</table>
					<input type="hidden" id="stm_new_posts_order" name="stm_new_posts_order" />
					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Order">
					</p>
				</form>
			</div>
		</div>
		<div id="col-left">
			<div class="col-wrap">

				<!--Edit post-->
				<div class="form-wrap stm_edit_item_wrap">
					<form class="" action="<?php echo get_home_url(); ?>/wp-admin/edit.php?post_type=listings&page=listing_categories" method="post">
						<div class="form-field">
							<label><?php _e( 'New Singular name', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_listing_edit[new_single_name]"
								id="stm_edit_item_single_name"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'New Plural name', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_listing_edit[new_plural_name]"
								id="stm_edit_item_plural_name"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Number field', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[edit_numeric]"
								id="stm_edit_item_numeric"
								/>
						</div>
						<div class="form-field hidden">
							<label><?php _e( 'Use on single listing page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_single_listing_page]"
								id="use_on_single_listing_page"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car listing page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_listing_page]"
								id="use_on_car_listing_page"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car listing archive page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_archive_listing_page]"
								id="use_on_car_archive_listing_page"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on single car page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_single_car_page]"
								id="use_on_single_car_page"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car filter', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_filter]"
								id="use_on_car_filter"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car modern filter', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_modern_filter]"
								id="use_on_car_modern_filter"
								/>
						</div>
						<div class="form-field stm-modern-filter-unit">
							<label><?php _e( 'Use images for this category', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_modern_filter_view_images]"
								id="use_on_car_modern_filter_view_images"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car filter as block with links', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_on_car_filter_links]"
								id="use_on_car_filter_links"
								/>
						</div>
						<!--Listings adds-->
						<h3><?php esc_html_e('Listing inventory additional settings', STM_VEHICLE_LISTING); ?></h3>
						<div class="form-field">
							<label><?php _e( 'Use this category in generated Listing Filter title', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								id="use_on_directory_filter_title"
								name="stm_listing_edit[use_on_directory_filter_title]"
								/>
							<p><?php _e('Enable this field, if you want to include category in Listing Filter title.',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Number field affix (used on listing archive)', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_listing_edit[number_field_affix]"
							    id="stm_number_field_affix"
								/>
							<p><?php _e('This affix will be shown after number. Example: mi, pcs, etc.',STM_VEHICLE_LISTING); ?></p>
						</div>

						<div class="form-field">
							<label><?php _e( 'Use in footer search', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_listing_edit[use_in_footer_search]"
								id="use_in_footer_search"
								/>
						</div>


						<!--Edit parent taxonomy-->
						<div class="form-field">
							<label><?php _e( 'Set parent taxonomy', STM_VEHICLE_LISTING ); ?></label>
							<select name="stm_listing_edit[listing_taxonomy_parent]" id="listing_taxonomy_parent">
								<option value=""><?php _e('No parent', STM_VEHICLE_LISTING); ?></option>
								<?php
								if ( ! empty( $options ) ):
									foreach ( $options as $option_key => $option ): ?>
										<option value="<?php echo $option['slug'] ?>"><?php echo $option['single_name'] ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>


						<div class="form-field">

							<label><?php _e( 'Use on listing archive as checkboxes ', STM_VEHICLE_LISTING ); ?></label>
							<select name="stm_listing_edit[listing_rows_numbers]" id="listing_cols_per_row">
								<option value=""><?php _e('Dont use', STM_VEHICLE_LISTING); ?></option>
								<option value="one_col"><?php _e('Use as 1 column per row', STM_VEHICLE_LISTING); ?></option>
								<option value="two_cols"><?php _e('Use as 2 columns per row', STM_VEHICLE_LISTING); ?></option>
							</select>
							<div class="form-field">
								<label><?php _e( 'Add submit button to this checkbox area (AJAX will be triggered after clicking on button)', STM_VEHICLE_LISTING ); ?></label>
								<input
									type="checkbox"
									name="stm_listing_edit[enable_checkbox_button]"
								    id="enable_checkbox_button"
									/>
							</div>
						</div>
						<div class="form-field">
							<?php
							$fa_icons = stm_get_cat_icons('fa');
							$custom_icons = stm_get_cat_icons('type_1');

							$new_icons_set = stm_get_cat_icons('service_icons');

							if(!empty($new_icons_set)) {
								$custom_icons = array_merge($custom_icons, $new_icons_set);
							}

							$counter = 0;
							?>
							<input type="hidden" name="stm_listing_edit[font_icon]" id="stm-edit-picked-font-icon"/>
							<div class="stm_theme_cat_chosen_icon_edit_preview"></div>
							<div class="stm_theme_font_pack_holder">
								<button type="button" class="stm_theme_pick_font button"><?php _e( 'Choose icons from Font Awesome Pack', STM_VEHICLE_LISTING ); ?></button>
								<table class="form-table stm_theme_icon_font_table">
									<tr>
										<?php foreach($fa_icons as $fa_icon): $counter++; ?>
										<td class="stm-pick-icon">
											<i class="fa fa-<?php echo esc_attr($fa_icon); ?>"></i>
										</td>
										<?php if($counter%15 == 0): ?>
									</tr>
									<tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								</table>
							</div>
							<div class="stm_theme_font_pack_holder">
								<button type="button" class="stm_theme_pick_font button"><?php _e( 'Choose icons from Motors Pack', STM_VEHICLE_LISTING ); ?></button>
								<table class="form-table stm_theme_icon_font_table">
									<tr>
										<?php $counter = 0; foreach($custom_icons as $custom_icon): $counter++; ?>
										<td class="stm-pick-icon">
											<i class="<?php echo esc_attr($custom_icon); ?>"></i>
										</td>
										<?php if($counter%15 == 0): ?>
									</tr>
									<tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								</table>
							</div>
						</div>
						<input id="stm_edit_item_id" type="hidden" name="stm_listing_edit[edit_id]" value="" readonly/>
						<input id="stm_old_slug" type="hidden" name="stm_listing_edit[old_slug]" value="" readonly/>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html_e('Save changes', STM_VEHICLE_LISTING); ?>">
							<a href="" class="button button-primary stm_close_edit_item"><?php _e('Close', STM_VEHICLE_LISTING); ?></a>
						</p>
					</form>
				</div>

				<!--New post-->
				<div class="form-wrap stm-new-filter-category">
					<form method="post" action="<?php echo get_home_url(); ?>/wp-admin/edit.php?post_type=listings&page=listing_categories">
						<div class="form-field">
							<label><?php _e( 'Singular name', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_vehicle_listing_options[single_name]"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Plural name', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_vehicle_listing_options[plural_name]"
								/>
						</div>
						<div class="form-field">
							<label><?php _e( 'Slug', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_vehicle_listing_options[slug]"
								/>
							<p><?php _e('Caution, you will not be able to change the link later'); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Number field', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[numeric]"
								/>
							<p><?php _e('Numeric value will be compared in another way (useful for mileage or fuel economy)',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field hidden">
							<label><?php _e( 'Use on single listing page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_single_listing_page]"
								/>
							<p><?php _e('Check if you want to see this category on single page',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car listing', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_listing_page]"
								/>
							<p><?php _e('Check if you want to see this category on car listing page (machine card)',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car archive listing', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_archive_listing_page]"
								/>
							<p><?php _e('Check if you want to see this category on car listing archive page with icon',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on single car page', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_single_car_page]"
								/>
							<p><?php _e('Check if you want to see this category on single car page',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car filter', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_filter]"
								/>
							<p><?php _e('Check if you want to see this category in filter',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car modern filter', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_modern_filter]"
								/>
							<p><?php _e('Check if you want to see this category in modern filter',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field stm-modern-filter-unit">
							<label><?php _e( 'Use images for this category', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_modern_filter_view_image]"
								/>
							<p><?php _e('Check if you want to see this category with images',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Use on car filter as block with links (be aware of using both as filter option)', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_car_filter_links]"
								/>
							<p><?php _e('Check if you want to see this category in filter as links',STM_VEHICLE_LISTING); ?></p>
						</div>
						<!--Listings adds-->
						<h3><?php esc_html_e('Listing inventory additional settings', STM_VEHICLE_LISTING); ?></h3>
						<div class="form-field">
							<label><?php _e( 'Use this category in generated Listing Filter title', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_on_directory_filter_title]"
								/>
							<p><?php _e('Enable this field, if you want to include category in Listing Filter title.',STM_VEHICLE_LISTING); ?></p>
						</div>
						<div class="form-field">
							<label><?php _e( 'Number field affix (used on listing archive)', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="text"
								name="stm_vehicle_listing_options[number_field_affix]"
								/>
							<p><?php _e('This affix will be shown after number. Example: mi, pcs, etc.',STM_VEHICLE_LISTING); ?></p>
						</div>

						<div class="form-field">
							<label><?php _e( 'Use in footer search', STM_VEHICLE_LISTING ); ?></label>
							<input
								type="checkbox"
								name="stm_vehicle_listing_options[use_in_footer_search]"
								/>
						</div>

						<!--Add new parent-->
						<div class="form-field">
							<label><?php _e( 'Set parent taxonomy', STM_VEHICLE_LISTING ); ?></label>
							<select name="stm_listing_edit[listing_taxonomy_parent]">
								<option value=""><?php _e('No parent', STM_VEHICLE_LISTING); ?></option>
								<?php
								if ( ! empty( $options ) ):
									foreach ( $options as $option_key => $option ): ?>
										<option value="<?php echo $option['slug'] ?>"><?php echo $option['single_name'] ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>

						<div class="form-field">
							<label><?php _e( 'Use on listing archive as checkboxes ', STM_VEHICLE_LISTING ); ?></label>
							<select name="stm_vehicle_listing_options[listing_rows_numbers]">
								<option value=""><?php _e('Dont use', STM_VEHICLE_LISTING); ?></option>
								<option value="one_col"><?php _e('Use as 1 column per row', STM_VEHICLE_LISTING); ?></option>
								<option value="two_cols"><?php _e('Use as 2 columns per row', STM_VEHICLE_LISTING); ?></option>
							</select>
							<p><?php _e('Use as checkboxes with images 1 or 2 columns',STM_VEHICLE_LISTING); ?></p>
							<div class="form-field">
								<label><?php _e( 'Add submit button to this checkbox area (AJAX will be triggered after clicking on button)', STM_VEHICLE_LISTING ); ?></label>
								<input
									type="checkbox"
									name="stm_vehicle_listing_options[enable_checkbox_button]"
									/>
							</div>
						</div>
						<div class="form-field">
							<?php
								$fa_icons = stm_get_cat_icons('fa');
								$custom_icons = stm_get_cat_icons('type_1');
								$new_icons_set = stm_get_cat_icons('service_icons');

								if(!empty($new_icons_set)) {
									$custom_icons = array_merge($custom_icons, $new_icons_set);
								}

								$counter = 0;
							?>
							<input type="hidden" name="stm_vehicle_listing_options[font_icon]" id="stm-picked-font-icon"/>
							<div class="stm_theme_cat_chosen_icon_preview"></div>
							<div class="stm_theme_font_pack_holder">
								<button type="button" class="stm_theme_pick_font button"><?php _e( 'Choose icons from Font Awesome Pack', STM_VEHICLE_LISTING ); ?></button>
								<table class="form-table stm_theme_icon_font_table">
									<tr>
										<?php foreach($fa_icons as $fa_icon): $counter++; ?>
										<td class="stm-pick-icon">
											<i class="fa fa-<?php echo esc_attr($fa_icon); ?>"></i>
										</td>
										<?php if($counter%15 == 0): ?>
									</tr>
									<tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								</table>
							</div>
							<div class="stm_theme_font_pack_holder">
								<button type="button" class="stm_theme_pick_font button"><?php _e( 'Choose icons from Motors Pack', STM_VEHICLE_LISTING ); ?></button>
								<table class="form-table stm_theme_icon_font_table">
									<tr>
										<?php $counter = 0; foreach($custom_icons as $custom_icon): $counter++; ?>
										<td class="stm-pick-icon">
											<i class="<?php echo esc_attr($custom_icon); ?>"></i>
										</td>
										<?php if($counter%15 == 0): ?>
									</tr>
									<tr>
										<?php endif; ?>
										<?php endforeach; ?>
									</tr>
								</table>
							</div>
						</div>
						<p class="submit">
							<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html_e('Add new', STM_VEHICLE_LISTING); ?>">
						</p>
					</form>
				</div>
			</div>
		</div>
		<div class="clear"></div>

	</div>
<?php }
add_action( 'init', 'create_stm_listing_category', 99);
function create_stm_listing_category() {
	$options = get_option( 'stm_vehicle_listing_options' );
	if ( ! empty( $options ) ) {
		foreach ( $options as $option ) {
			if(empty($option['numeric'])) {
				$numeric = true;
			} else {
				$numeric = false;
			}
			register_taxonomy(
				$option['slug'],
				'listings',
				array(
					'labels'             => array(
						'name'          => $option['plural_name'],
						'singular_name' => $option['single_name'],
						'search_items'               => __( 'Search '.$option['plural_name'] ),
						'popular_items'              => __( 'Popular '.$option['plural_name'] ),
						'all_items'                  => __( 'All '.$option['plural_name'] ),
						'parent_item'                => null,
						'parent_item_colon'          => null,
						'edit_item'                  => __( 'Edit '.$option['single_name'] ),
						'update_item'                => __( 'Update '.$option['single_name'] ),
						'add_new_item'               => __( 'Add New '.$option['single_name'] ),
						'new_item_name'              => __( 'New '.$option['single_name'].' Name' ),
						'separate_items_with_commas' => __( 'Separate '.$option['plural_name'].' with commas' ),
						'add_or_remove_items'        => __( 'Add or remove '.$option['plural_name'] ),
						'choose_from_most_used'      => __( 'Choose from the most used '.$option['plural_name'] ),
						'not_found'                  => __( 'No '.$option['plural_name'].' found.' ),
						'menu_name'                  => __( $option['plural_name'] ),
					),
					'public'             => true,
					'hierarchical'       => $numeric,
					'show_ui'            => true,
					'show_in_menu'       => false,
					'show_admin_column'  => false,
					'show_in_nav_menus'  => false,
					'show_in_quick_edit' => false,
					'query_var'          => false,
					'rewrite'            => false,
				)
			);
		};
	}
}

function stm_get_cat_icons($font_pack = 'fa') {
	$plugin_path = dirname(__FILE__);
	$fonts  = array();

	if($font_pack == 'fa') {
		$font_icons = file( $plugin_path . '/../assets/font-awesome.json' );
		$font_icons = json_decode( implode( '', $font_icons ), true );
		foreach ( $font_icons as $key => $val ) {
			$fonts[] = $key;
		};
	} elseif($font_pack == 'type_1') {
		$fonts_pack = get_option( 'stm_fonts' );
		foreach ( $fonts_pack as $font => $info ) {
			$icon_set   = array();
			$icons      = array();
			$upload_dir = wp_upload_dir();
			$path       = trailingslashit( $upload_dir['basedir'] );
			$file       = $path . $info['include'] . '/' . $info['config'];
			$output     = '';
			include( $file );
			if ( ! empty( $icons ) ) {
				$icon_set = array_merge( $icon_set, $icons );
			}
			if ( ! empty( $icon_set ) ) {
				foreach ( $icon_set as $icons ) {
					foreach ( $icons as $icon ) {
						$fonts[] = $font . '-' . $icon['class'];
					}
				}
			}
		}

	} elseif($font_pack == 'service_icons') {
		$fonts_custom_type_2 = json_decode( file_get_contents( get_template_directory() . '/assets/js/service_icons.json' ), true );

		foreach( $fonts_custom_type_2['icons'] as $icon ){
			$fonts[] = 'stm-service-icon-'.$icon['properties']['name'];
		}

	}
	return $fonts;
};

function stm_get_car_listings(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_listing_page'] ) and $options[ $key ]['use_on_car_listing_page'] == 1 and $option['slug'] != 'price' ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_archive_listings(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_archive_listing_page'] ) and $options[ $key ]['use_on_car_archive_listing_page'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_single_car_listings(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_single_car_page'] ) and $options[ $key ]['use_on_single_car_page'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_filter(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_filter'] ) and $options[ $key ]['use_on_car_filter'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_modern_filter(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_modern_filter'] ) and $options[ $key ]['use_on_car_modern_filter'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_modern_filter_view_images(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_modern_filter_view_images'] ) and $options[ $key ]['use_on_car_modern_filter_view_images'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_parent_exist(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['listing_taxonomy_parent'] ) ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_filter_links(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_car_filter_links'] ) and $options[ $key ]['use_on_car_filter_links'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_footer_taxonomies(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_in_footer_search'] ) and $options[ $key ]['use_in_footer_search'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_car_filter_checkboxes(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['listing_rows_numbers'] ) ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_filter_title(){

	$car_listing = array();
	$options = get_option( 'stm_vehicle_listing_options' );
	if(!empty($options)) {
		foreach ( $options as $key => $option ) {
			if ( ! empty( $options[ $key ]['use_on_directory_filter_title'] ) and $options[ $key ]['use_on_directory_filter_title'] == 1 ) {
				$car_listing[] = $option;
			}
		}
	}

	return $car_listing;
}

function stm_get_taxonomies() {
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option( 'stm_vehicle_listing_options' );

	$taxonomies = array();

	if(!empty($filter_options)) {
		foreach ( $filter_options as $filter_option ) {

			$taxonomies[ $filter_option['single_name'] ] = $filter_option['slug'];

		}

	}

	return $taxonomies;

}


function stm_get_taxonomies_with_type($taxonomy_slug) {
	if(!empty($taxonomy_slug)) {
		//Get all filter options from STM listing plugin - Listing - listing categories
		$filter_options = get_option( 'stm_vehicle_listing_options' );

		$taxonomies = array();

		if(!empty($filter_options)) {
			foreach ( $filter_options as $filter_option ) {
				if($filter_option['slug'] == $taxonomy_slug) {
					$taxonomies = $filter_option;
				}
			}

		}

		return $taxonomies;
	}

}

function stm_get_taxonomies_as_div() {
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option( 'stm_vehicle_listing_options' );

	$taxonomies = array();

	if(!empty($filter_options)) {
		foreach ( $filter_options as $filter_option ) {
			if(!$filter_option['numeric']) {
				$taxonomies[ $filter_option['single_name'] ] = $filter_option['slug'].'div';
			} else {
				$taxonomies[ $filter_option['single_name'] ] = 'tagsdiv-'.$filter_option['slug'];
			}

		}

	}

	return $taxonomies;

}

function stm_get_categories() {
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option( 'stm_vehicle_listing_options' );
	//Creating new array for tax query and meta query
	$categories = array();

	$terms_args = array(
		'orderby'    => 'name',
		'order'      => 'ASC',
		'hide_empty' => false,
		'fields'     => 'all',
		'pad_counts' => false,
	);

	if(!empty($filter_options)) {
		foreach ( $filter_options as $filter_option ) {
			if ( empty( $filter_option['numeric'] ) ) {

				$terms = get_terms( $filter_option['slug'], $terms_args );

				foreach ( $terms as $term ) {
					$categories[ $term->slug ] = $term->slug . ' | ' . $filter_option['slug'];
				}
			}
		}
	}

	return $categories;
}

function stm_get_name_by_slug($slug = '') {
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option( 'stm_vehicle_listing_options' );
	$name = '';
	if(!empty($filter_options)) {
		if ( ! empty( $slug ) ) {
			foreach ( $filter_options as $filter_option ) {
				if ( $filter_option['slug'] == $slug ) {
					$name = $filter_option['single_name'];
				}
			}
		}
	}
	return $name;
}

function stm_get_all_by_slug($slug = '') {
	//Get all filter options from STM listing plugin - Listing - listing categories
	$filter_options = get_option( 'stm_vehicle_listing_options' );
	$name = '';
	if(!empty($filter_options)) {
		if ( ! empty( $slug ) ) {
			foreach ( $filter_options as $filter_option ) {
				if ( $filter_option['slug'] == $slug ) {
					$name = $filter_option;
				}
			}
		}
	}
	return $name;
}

function stm_get_custom_taxonomy_count($tax_term, $taxonomy_name) {
	$args = array(
		'post_type' => 'listings',
		'posts_per_page' => '-1',
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy_name,
				'field' => 'slug',
				'terms' => $tax_term
			)
		)
	);

	$my_query = new WP_Query( $args );

	return $my_query->found_posts;

}

function stm_get_category_by_slug($slug) {
	if(!empty($slug)) {
		$terms_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
			'fields'     => 'all',
			'pad_counts' => true,
		);
		$terms = get_terms($slug, $terms_args);
		return $terms;
	}
}

function stm_get_category_by_slug_all($slug) {
	if(!empty($slug)) {
		$terms_args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
			'pad_counts' => true,
		);
		$terms = get_terms($slug, $terms_args);
		return $terms;
	}
}

function stm_remove_meta_boxes() {
	$taxonomies_style = stm_get_taxonomies_as_div();

	if(!empty($taxonomies_style)) {
		foreach($taxonomies_style as $taxonomy_style) {
			remove_meta_box($taxonomy_style, 'listings', 'side');
		}
	}

}

add_action( 'admin_init', 'stm_remove_meta_boxes' );
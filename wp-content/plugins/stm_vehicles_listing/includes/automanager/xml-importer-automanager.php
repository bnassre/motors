<?php
// Add options page to menu
function add_theme_menu_item_import_xml_automanager() {
	add_submenu_page(
		'edit.php?post_type=listings',
		__("WebManager XML Import", STM_VEHICLE_LISTING),
		__("WebManager XML Import", STM_VEHICLE_LISTING),
		'manage_options',
		'stm_xml_import_automanager',
		'stm_vehicle_import_xml_automanager'
	);
}

add_action("admin_menu", "add_theme_menu_item_import_xml_automanager");

function stm_vehicle_import_xml_automanager() {

	$filter_taxes = stm_get_taxonomies();
	$has_template = false;
	$url = '';
	$template_name = '';

	$templates = get_option('stm_xml_templates');
	$current_template = get_option('stm_current_template');

	update_option('current_queried_xml', 0);

	if(!empty($templates) and !empty($current_template)) {
		$has_template = true;

		$template = $templates[$current_template];

		$url = $template['url'];
		$template_name = $template['name'];

	}

	$stm_theme_fields = array(
		'title'	         			=> array(
			'name'  => __('Title', STM_VEHICLE_LISTING),
			'value' => '{Year} {Make} {Model}',
			'type'  => 'text',
		),
		'content'        			=> array(
			'name'  => __('Content', STM_VEHICLE_LISTING),
			'value' => '[vc_row][vc_column][vc_tabs][vc_tab title="Vehicle overview" tab_id="stm_tab_id"][vc_column_text]{Description}[/vc_column_text][/vc_tab][vc_tab title="Features" tab_id="stm_tab_id"][vc_column_text]{Features}[/vc_column_text][/vc_tab][/vc_tabs][/vc_column][/vc_row]',
			'type'  => 'textarea',
		),
		'status'                    => array(
			'name'  => __('Status', STM_VEHICLE_LISTING),
			'value' => 'publish',
			'values'=> array('draft'),
			'type'  => 'select'
		),
		'featured_image'	        => array(
			'name'  => __('Featured Image', STM_VEHICLE_LISTING),
			'value' => '{PhotoURLs[PhotoURL][0]}',
			'type'  => 'text',
		),
		'gallery'	                => array(
			'name'  => __('Gallery (All images except first)', STM_VEHICLE_LISTING),
			'value' => '{PhotoURLs}',
			'type'  => 'text',
		),
		'stock_number'	            => array(
			'name'  => __('Stock Number', STM_VEHICLE_LISTING),
			'value' => '{StockNum}',
			'type'  => 'text',
		),
		'vin'	         		    => array(
			'name'  => __('VIN', STM_VEHICLE_LISTING),
			'value' => '{VIN}',
			'type'  => 'text',
		),
		'city_mpg'	                => array(
			'name'  => __('City MPG', STM_VEHICLE_LISTING),
			'value' => '{MpgCity}',
			'type'  => 'text',
		),
		'highway_mpg'	            => array(
			'name'  => __('Highway MPG', STM_VEHICLE_LISTING),
			'value' => '{MpgHighway}',
			'type'  => 'text',
		),
		'regular_price_label'       => array(
			'name'  => __('Content', STM_VEHICLE_LISTING),
			'value' => __('Buy for', STM_VEHICLE_LISTING),
			'type'  => 'text',
		),
		'regular_price_description' => array(
			'name'  => __('Regular price description', STM_VEHICLE_LISTING),
			'value' => '',
			'type'  => 'text',
		),
		'special_price_label'       => array(
			'name'  => __('Special price label', STM_VEHICLE_LISTING),
			'value' => __('Retail Price', STM_VEHICLE_LISTING),
			'type'  => 'text',
		),
		'instant_savings_label'     => array(
			'name'  => __('Instant savings label', STM_VEHICLE_LISTING),
			'value' => __('Instant Savings:', STM_VEHICLE_LISTING),
			'type'  => 'text',
		),
	);

	if($has_template) {
		foreach($stm_theme_fields as $stm_theme_key => $stm_theme_field) {
			if($stm_theme_key == 'content') {
				$stm_theme_fields[$stm_theme_key]['value'] = stripslashes($template['associations'][$stm_theme_key]);
			} else {
				$stm_theme_fields[$stm_theme_key]['value'] = $template['associations'][$stm_theme_key];
			}
		}
	}
	?>



	<div class="stm-automanager-xml first-step">
		<div class="stm_automanager_notification updated">
			<h6><?php esc_html_e('How to Set Up WebManager XML Import:', STM_VEHICLE_LISTING); ?></h6>
			<ul>
				<li>
					1. <?php esc_html_e('Log into', STM_VEHICLE_LISTING); ?> <a href="http://wm.automanager.com/" target="_blank"><?php esc_html_e('WebManager', STM_VEHICLE_LISTING); ?></a> <?php esc_html_e('but keep this page in a separate tab.', STM_VEHICLE_LISTING); ?> <br/>
					<?php esc_html_e('(Note: If you are not a WebManager subscriber', STM_VEHICLE_LISTING); ?>, <a href="http://www.automanager.com/wordpress" target="_blank"><?php esc_html_e('click here', STM_VEHICLE_LISTING); ?></a> <?php esc_html_e('for a free trial.', STM_VEHICLE_LISTING); ?>)
				</li>
				<li>
					2. <?php esc_html_e('On the top navigation bar, select', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Settings', STM_VEHICLE_LISTING); ?></strong>, <?php esc_html_e('then', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Website', STM_VEHICLE_LISTING); ?></strong>, <?php esc_html_e('and click', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Inventory Integration', STM_VEHICLE_LISTING); ?></strong>.
				</li>
				<li>
					3. <?php esc_html_e('Under', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Inventory Feed Settings', STM_VEHICLE_LISTING); ?></strong>, <?php esc_html_e('add any', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Security ID', STM_VEHICLE_LISTING); ?></strong>, <?php esc_html_e('check the', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Enable Box', STM_VEHICLE_LISTING); ?></strong>, & <?php esc_html_e('click', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Save Changes', STM_VEHICLE_LISTING); ?></strong> <?php esc_html_e('at the bottom.', STM_VEHICLE_LISTING); ?>
				</li>
				<li>
					4. <?php esc_html_e('Under', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Inventory Feed Settings', STM_VEHICLE_LISTING); ?></strong> <?php esc_html_e('next to Example, select and copy the link.', STM_VEHICLE_LISTING); ?>
				</li>
				<li>
					5. <?php esc_html_e('Return to this page, and under', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('XML URL', STM_VEHICLE_LISTING); ?></strong>, <?php esc_html_e('paste the copied link in the field and click', STM_VEHICLE_LISTING); ?> <strong><?php esc_html_e('Upload File', STM_VEHICLE_LISTING); ?></strong>.
				</li>
			</ul>
		</div>
		<div class="container">
			<!-- Disable cron-->
			<?php
			if(!empty($current_template)):
				$active = get_option('stm_enable_cron_automanager');

				if(empty($active)) {
					$active = true;
					update_option('stm_enable_cron_automanager', true);
				}

				if(!empty($_GET['stm_stop_cron']) and $_GET['stm_stop_cron']) {
					$active = false;
					update_option('stm_enable_cron_automanager', false);
				}

				if(!empty($_GET['stm_start_cron']) and $_GET['stm_start_cron']) {
					$active = true;
					update_option('stm_enable_cron_automanager', true);
				}

				$delay = $templates[$current_template]['settings']['import_delay'];

				if($delay == 'hourly') {
					$delay = 60;
				}

				if($delay == 'twicedaily') {
					$delay = 720;
				}

				if($delay == 'daily') {
					$delay = 1440;
				}

				$time = $delay + (  ( time() - wp_next_scheduled( 'stm_cron_hook' ) ) / 60 );

				?>

				<div class="cron-settings">
					<?php if($active): ?>
						<p>
							<h6>
								<?php //echo 'last update - ' . date('H', mktime(0,$time)) . ' hours ' . date('i', mktime(0,$time)) . ' minutes ago'; ?>
							</h6>
							<h6><?php esc_html_e('Cronjob is active now, you can stop periodical import, by clicking Stop cron', STM_VEHICLE_LISTING); ?></h6>
						</p>
						<a 
							href="<?php echo add_query_arg(array('stm_stop_cron'=>'1', 'stm_start_cron'=>'0')); ?>"
							class="waves-effect waves-light btn-large">
								<?php esc_html_e('Stop Cron', STM_VEHICLE_LISTING); ?>
						</a>
					<?php else: ?>
						<p>
							<h6><?php esc_html_e('Cronjob is inactive now, you can resume periodical import, by clicking Start cron', STM_VEHICLE_LISTING); ?></h6>
						</p>
						<a 
							href="<?php echo add_query_arg(array('stm_stop_cron'=>'0', 'stm_start_cron'=>'1')); ?>"
							class="waves-effect waves-light btn-large">
								<?php esc_html_e('Start Cron', STM_VEHICLE_LISTING); ?>
						</a>
					<?php endif; ?>

				</div>

			<?php endif; ?>



			<h6><?php esc_html_e('Progress', STM_VEHICLE_LISTING); ?></h6>
			<div class="progress">
				<div class="determinate"></div>
			</div>
			<div class="row">
				<div class="col s12">

					<!-- First step, file upload -->
					<form id="stm_automanager_first_step" method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="stm_autmanager_upload_xml">

						<h5 class="center-align"><?php esc_html_e('Specify link', STM_VEHICLE_LISTING); ?></h5>

						<div class="row">
							<div class="col s12">
								<div class="row">
									<div class="input-field col s12">
										<input id="url" type="url" name="url" value="<?php echo esc_attr($url); ?>">
										<label for="url"><?php esc_html_e('XML Url', STM_VEHICLE_LISTING); ?></label>
									</div>
								</div>
							</div>
						</div>

						<div class="mg-bt-20"></div>

						<button type="submit" class="waves-effect waves-light btn-large"><?php esc_html_e('Upload file', STM_VEHICLE_LISTING); ?></button>

						<!-- Preloader -->
						<div class="stm-preloader-file">
							<div class="preloader-wrapper small">
								<div class="spinner-layer spinner-green-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>
					</form>

					<!-- Second step, dotting items -->
					<form id="stm_automanager_second_step" method="post" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="stm_automanager_place_items">
						<blockquote>
							<h5><?php esc_html_e('Note', STM_VEHICLE_LISTING); ?>:</h5>
							<p><?php esc_html_e('Title and Content are required fields', STM_VEHICLE_LISTING); ?></p>
						</blockquote>
						<div class="pd-bt-15"></div>
						<div class="clear"></div>
						<div class="row">
							<div class="col s4"><div class="xml_parts"></div></div> <!-- col-s4 -->
							<div class="col s8">

								<div class="stm_theme_fields">

									<?php foreach($stm_theme_fields as $key => $stm_theme_field): ?>
										<?php $active = 'active'; ?>
										<div class="input-field">
											<?php if($stm_theme_field['type'] == 'text'): ?>
												<input
													name="<?php echo esc_attr($key); ?>"
													value="<?php echo esc_attr($stm_theme_field['value']); ?>"
													/>
											<?php elseif($stm_theme_field['type'] == 'textarea'): ?>
												<textarea name="<?php echo esc_attr($key); ?>" class="materialize-textarea"><?php echo esc_attr($stm_theme_field['value']); ?></textarea>
											<?php else: ?>
												<?php $active = ''; ?>
												<select name="<?php echo esc_attr($key); ?>">
													<option value="<?php echo esc_attr($stm_theme_field['value']); ?>" selected><?php echo esc_attr($stm_theme_field['value']); ?></option>
													<?php foreach($stm_theme_field['values'] as $opt): ?>
														<option name="<?php echo esc_attr($opt); ?>"><?php echo esc_attr($opt); ?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
											<label
												for="<?php echo esc_attr($key) ?>"
												class="<?php echo esc_attr($active); ?>">
												<?php echo esc_attr($stm_theme_field['name']); ?>
											</label>
										</div>

									<?php endforeach; ?>

									<?php if(!empty($filter_taxes)): ?>

										<?php foreach($filter_taxes as $key => $filter_tax): ?>
											<?php if($filter_tax != 'price'): ?>
												<div class="input-field">
													<?php
													if(!$has_template) {

														if($key == 'Condition') {
															$value = '{Type}';
														} elseif($key == 'Interior Color') {
															$value = '{IntColor}';
														} elseif($key == 'Exterior Color') {
															$value = '{ExtColor}';
														} elseif($key == 'Fuel type') {
															$value = '{Fuel}';
														} elseif($key == 'Body') {
															$value = '{Style}';
														} elseif($key == 'Drive') {
															$value = '{Drivetrain}';
														} else {
															$value = '';
														}

													} else {
														$value = '';
														if(!empty($template['associations'][$filter_tax])){
															$value = $template['associations'][$filter_tax];
														}
													}

													?>
													<input name="<?php echo esc_attr($filter_tax) ?>" value="<?php echo esc_attr($value); ?>"/>
													<label for="<?php echo esc_attr($filter_tax) ?>" class="active"><?php echo esc_attr($key); ?></label>
												</div>
											<?php else: ?>
												<div class="input-field">
													<input name="price" value="{ShowroomPrice}"/>
													<label for="price" class="active">
														<?php esc_html_e('Price', STM_VEHICLE_LISTING); ?>
													</label>
												</div>
												<div class="input-field">
													<input name="sale_price"  value="{InternetPrice}"/>
													<label for="sale_price" class="active">
														<?php esc_html_e('Sale Price', STM_VEHICLE_LISTING); ?>
													</label>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>

									<?php endif; ?>

								</div>
								<button type="submit" class="hidden"><?php esc_html_e('Continue', STM_VEHICLE_LISTING); ?></button>
							</div> <!-- col-s-8 -->
						</div>
					</form>

					<!-- Third step -->
					<form id="stm_automanager_third_step" method="post" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="stm_automanager_save_template" target="stm_xml_import_automanager">
						<h4><?php esc_html_e('Set up Template', STM_VEHICLE_LISTING); ?></h4>
						<div class="pd-bt-15"></div>
						<div class="stm_theme_fields">
							<div class="input-field">
								<input type="text" name="template_name" value="<?php echo esc_attr($template_name); ?>"/>
								<label for="template_name" class="active"><?php esc_html_e('Template name:', STM_VEHICLE_LISTING); ?></label>
							</div>
							<div class="checkbox-field">
								<label><?php esc_html_e('Run import every (Cron Job):', STM_VEHICLE_LISTING); ?></label>
								<p>
									<input name="import_delay" type="radio" id="hourly" value="hourly" checked="1"/>
									<label for="hourly"><?php esc_html_e('Hourly', STM_VEHICLE_LISTING); ?></label>
								</p>
								<p>
									<input name="import_delay" type="radio" id="twicedaily" value="twicedaily"/>
									<label for="twicedaily"><?php esc_html_e('Twice Daily', STM_VEHICLE_LISTING); ?></label>
								</p>
								<p>
									<input name="import_delay" type="radio" value="daily" id="daily" />
									<label for="daily"><?php esc_html_e('Daily', STM_VEHICLE_LISTING); ?></label>
								</p>
							</div>
							<!--
<div class="checkbox-field">
								<label><?php esc_html_e('Run first import now?', STM_VEHICLE_LISTING); ?></label>
								<p>
									<input type="checkbox" id="run_import_now" name="run_import_now"/>
									<label for="run_import_now"><?php esc_html_e('Yes', STM_VEHICLE_LISTING); ?></label>
							    </p>
							</div>
-->

						</div>
						<button type="submit" class="hidden"><?php esc_html_e('Continue', STM_VEHICLE_LISTING); ?></button>
					</form>

					<div id="stm_automanager_fourth_step">
						<iframe src="about:blank" name="stm_xml_import_automanager" id="stm_xml_import_automanager"></iframe>
					</div>

					<footer class="clear">
						<a class="waves-effect waves-light btn stm-back"><?php esc_html_e('Back', STM_VEHICLE_LISTING); ?></a>

						<!-- Step two buttons -->
						<a href="#" class="waves-effect waves-light btn stm-proceed-step-three">
							<?php esc_html_e('Save associations and continue', STM_VEHICLE_LISTING); ?>
						</a>
						<div class="stm-step-two-preloader">
							<div class="preloader-wrapper small">
								<div class="spinner-layer spinner-green-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Step three buttons -->
						<a href="#" class="waves-effect waves-light btn stm-proceed-step-four">
							<?php esc_html_e('Start import', STM_VEHICLE_LISTING); ?>
						</a>
						<div class="stm-step-three-preloader">
							<div class="preloader-wrapper small">
								<div class="spinner-layer spinner-green-only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div>
									<div class="gap-patch">
										<div class="circle"></div>
									</div>
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>

					</footer>

				</div> <!-- col-12 -->
			</div> <!-- row -->
		</div> <!-- container -->
	</div> <!-- stm-automanager -->
<?php }
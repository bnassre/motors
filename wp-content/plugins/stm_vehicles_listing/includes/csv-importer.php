<?php	
// Add options page to menu
function add_theme_menu_item_import_csv() {
	add_submenu_page(
		'edit.php?post_type=listings',
		__("Import from CSV", STM_VEHICLE_LISTING),
		__("Import from CSV", STM_VEHICLE_LISTING),
		'manage_options',
		'stm_csv_import',
		'stm_vehicle_import_csv'
	);
}

function stm_helper($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

add_action("admin_menu", "add_theme_menu_item_import_csv");

function stm_vehicle_import_csv() {	
	
	$stm_get_taxonomies = stm_get_taxonomies();
	
	$error_type = '';
	
	$default_post_fields = array(
		'Post Title',
		'Post Content',
		'Post Excerpt',
		'Post Status',
	);
	
	if(!empty($_FILES['import_upload'])) {
		
		$csv_types = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
		
		if(!in_array($_FILES['import_upload']['type'], $csv_types)) {
			$error_type = esc_html__('Please, upload file with .csv format', STM_VEHICLE_LISTING);
		}
		
		if(empty($error_type)) {
			
			$csv_file = fopen($_FILES['import_upload']['tmp_name'], 'r+');
			
			$lines = array();
			
			while( ($row = fgetcsv($csv_file, 8192)) !== FALSE ) {
				$lines[] = $row;
			}
			
			update_option('parsed_csv', $lines);
			update_option('current_csv_line', 0);
		}
	}
	
	?>
	
	

	<div id="col-container">
		<h2 class="clear"><?php _e( 'Import from CSV', STM_VEHICLE_LISTING ); ?></h2>
		
		<div class="stm_csv_file_upload form-wrap">
			
			<!-- Uploading file -->
			<h4><?php esc_html_e('Load CSV from your local files', STM_VEHICLE_LISTING); ?></h4>
			<form id="upload_csv_form" method="post" enctype="multipart/form-data" class="wp-upload-form" action="<?php echo remove_query_arg( "error" ); ?>" name="stm_import_upload">
                <label class="screen-reader-text" for="import_upload"><?php esc_html_e("Listing file", STM_VEHICLE_LISTING); ?></label>
                <input type="file" id="import_upload" name="import_upload">
                <input type="submit" name="import_submit_auto" id="upload_csv_motors" class="button" value="<?php esc_html_e('Continue', STM_VEHICLE_LISTING); ?>">
            </form>
            
            
            <!-- If not csv uploaded -->
            <?php if(!empty($error_type)): ?>
            	<p class="danger" style="color:red;"><?php esc_html_e($error_type, STM_VEHICLE_LISTING); ?></p>
            <?php endif; ?>
            
            <!-- Show ui for the csv parsing way -->
            <?php if(!empty($lines) and count($lines) > 1): ?>
            	<div class="source">
	            	<?php $i=0; foreach($lines[0] as $key=>$line): ?>
	            		<div class="item" data-key="<?php echo esc_attr($i); ?>"><span class="closer">x</span><?php echo $line; ?></div>
	            		
	            	<?php $i++; endforeach; ?>
            	</div>
            	
            	<form method="post" action="<?php echo add_query_arg( "do_import", 1 ); ?>" name="stm_csv_import" id="stm_import_from_csv" target="stm_do_import_iframe">
	            	<div class="target">
		            	<?php foreach($default_post_fields as $key=>$default_post_field): ?>
		            		<div class="target-unit">
								<?php echo esc_attr($default_post_field); ?>
								<input type="hidden" name="stm_csv_field[<?php echo str_replace('-', '_', sanitize_title($default_post_field)); ?>]" value=""/>
								<div class="empty"></div>
							</div>
		            	<?php endforeach; ?>
		            	
		            	<?php if(!empty($stm_get_taxonomies)): ?>
							<?php foreach($stm_get_taxonomies as $key => $taxonomy): ?>
								<?php if($taxonomy == 'price'): ?>
									<div class="target-unit">
										<?php esc_html_e('Price', STM_VEHICLE_LISTING); ?>
										<input type="hidden" name="stm_csv_field[price]" value=""/>
										<div class="empty"></div>
									</div>
									<div class="target-unit">
										<?php esc_html_e('Sale Price', STM_VEHICLE_LISTING); ?>
										<input type="hidden" name="stm_csv_field[sale_price]" value=""/>
										<div class="empty"></div>
									</div>
								<?php else: ?>
									<div class="target-unit">
										<?php echo esc_attr($key); ?>
										<input type="hidden" name="stm_csv_field[<?php echo esc_attr($taxonomy); ?>]" value=""/>
										<div class="empty"></div>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
		            	<?php endif; ?>
		            	
		            	<!-- Featured Image -->
		            	<div class="target-unit">
							<?php esc_html_e('Featured Image', STM_VEHICLE_LISTING); ?>
							<input type="hidden" name="stm_csv_field[featured_image]" value=""/>
							<div class="empty"></div>
						</div>
						
						<!-- Gallery -->
						<div class="target-unit">
							<?php esc_html_e('Gallery', STM_VEHICLE_LISTING); ?>
							<input type="hidden" name="stm_csv_field[gallery]" value=""/>
							<div class="empty"></div>
						</div>
	            	</div>
	            	
	            	<div class="clear"></div>
	            	
	            	<button class="button" type="submit" class="start_import"><?php esc_html_e('Import', STM_VEHICLE_LISTING); ?></button>
	            	<div class="errors">
		            	
	            	</div>
            	</form>
            	
            <?php endif; ?>
            
            
            <div class="import_details">
	            <div class="danger" style="margin-top:5px;"><?php esc_html_e('Please wait for the notice "Import done."', 'motors'); ?></div>
	            <iframe src="<?php echo add_query_arg( "do_import", 1 ); ?>" name="stm_do_import_iframe"></iframe>
            </div>
                        
		</div>
	</div>
<?php }
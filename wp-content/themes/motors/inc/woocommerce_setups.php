<?php
// Declare Woo support
add_action( 'after_setup_theme', 'stm_woocommerce_support' );
function stm_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

//Remove Woo Breadcrumbs
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

function stm_remove_woo_widgets() {
	unregister_widget( 'WC_Widget_Recent_Products' );
	unregister_widget( 'WC_Widget_Featured_Products' );
	//unregister_widget( 'WC_Widget_Product_Categories' );
	unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
	//unregister_widget( 'WC_Widget_Cart' );
	unregister_widget( 'WC_Widget_Layered_Nav' );
	unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
	//unregister_widget( 'WC_Widget_Price_Filter' );
	unregister_widget( 'WC_Widget_Product_Search' );
	//unregister_widget( 'WC_Widget_Top_Rated_Products' );
	unregister_widget( 'WC_Widget_Recent_Reviews' );
	unregister_widget( 'WC_Widget_Recently_Viewed' );
	unregister_widget( 'WC_Widget_Best_Sellers' );
	unregister_widget( 'WC_Widget_Onsale' );
	unregister_widget( 'WC_Widget_Random_Products' );
}
add_action( 'widgets_init', 'stm_remove_woo_widgets' );


if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
	define( 'WOOCOMMERCE_USE_CSS', false );
}

add_filter( 'woocommerce_show_page_title', '__return_false' );

add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

add_filter( 'add_to_cart_fragments', 'stm_woocommerce_header_add_to_cart_fragment' );
function stm_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	$cart_count = $woocommerce->cart->cart_contents_count;
	if($cart_count == 0) {
		$cart_count = '';
	}
	ob_start();
	?>
	<span class="stm-current-items-in-cart"><?php echo esc_attr($cart_count); ?></span>
	<?php
	$fragments['.stm-current-items-in-cart'] = ob_get_clean();

	return $fragments;
}

add_filter( 'woocommerce_output_related_products_args', 'stm_related_products_args' );

function stm_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	return $args;
}

add_action( 'wp_enqueue_scripts', 'stm_woo_dequeue_styles_and_scripts', 100 );

function stm_woo_dequeue_styles_and_scripts() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'select2' );
        wp_deregister_style( 'select2' );

        wp_dequeue_script( 'select2');
        wp_deregister_script('select2');

    } 
} 
<?php


add_action( 'admin_init', 'stm_listing_list' );

function stm_listing_list() {
	$options = get_option( 'stm_vehicle_listing_options' );

	if(!empty($options)) {
		$taxonomies = array(
			'price'      => array(
				'label' => __( 'Regular Price', STM_VEHICLE_LISTING ),
				'type'  => 'text',
			),
			'sale_price' => array(
				'label' => __( 'Sale Price', STM_VEHICLE_LISTING ),
				'type'  => 'text',
			),
		);

		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
			'pad_counts' => false,
		);

		foreach ( $options as $key => $option ) {
			$terms = get_terms( $option['slug'], $args );

			$field_type = 'listing_select';
			if ( ! empty( $option['numeric'] ) ) {
				$field_type = 'text';
			}

			$single_term = array(
				'none' => 'None'
			);

			foreach ( $terms as $tax_key => $taxonomy ) {
				if ( ! empty( $taxonomy ) ) {
					$single_term[ $taxonomy->slug ] = $taxonomy->name;
				}
			}

			if ( $option['slug'] != 'price' ) {

				$taxonomies[ $option['slug'] ] = array(
					'label'   => $option['single_name'],
					'type'    => $field_type,
					'options' => $single_term,
				);
			}

		}

		
		if (class_exists('STM_PostType')) {
			STM_PostType::addMetaBox( 'listing_filter', __( 'Car Options', STM_POST_TYPE ), array( 'listings' ), '', '', '', array(
				'fields' => $taxonomies
			) );
		}
	}
}
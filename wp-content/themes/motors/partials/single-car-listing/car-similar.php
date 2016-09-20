<?php
	$filter_options = stm_get_car_filter();
	$similars = array();
	$similars_tax = array();
	$similar_args = array(
		'post_type' => 'listings',
		'post_status' => 'publish',
		'posts_per_page' => '3',
		'tax_query' => array(
			array(
				'taxonomy' => 'make',
				'field' => 'slug',
				'terms' => array('bmw')
			)
		)
	);

	foreach($filter_options as $filter_option) {
		$similars[$filter_option['slug']] = get_post_meta(get_the_id(), $filter_option['slug'], true);
	}

	$similar_args['post__not_in'] = array(get_the_ID());

	if(!empty($similars)) {
		$similars_tax[0] = array(
			'relation' => 'OR'
		);
		foreach($similars as $taxonomy => $terms) {
			if($taxonomy != 'transmission') {
				$similars_tax[0][] = array(
					'taxonomy' => esc_attr( $taxonomy ),
					'field'    => 'slug',
					'terms'    => explode( ',', $terms )
				);
			}
		}

		$similar_args['tax_query'] = $similars_tax;

		$query = new WP_Query($similar_args);
	}

?>

<?php if($query->have_posts()): ?>

	<div class="stm-similar-cars-units">
		<?php while($query->have_posts()): $query->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="stm-similar-car clearfix">
				<?php if(has_post_thumbnail()): ?>
					<div class="image">
						<?php the_post_thumbnail('stm-img-350-356', array('class'=>'img-responsive')); ?>
					</div>
				<?php endif; ?>
				<div class="right-unit">
					<div class="title"><?php echo stm_generate_title_from_slugs(get_the_ID()); ?></div>

					<?php
						$user_added_by = get_post_meta(get_the_ID(), 'stm_car_user', true);
						if(!empty($user_added_by)) {
							$user_exist = get_userdata( $user_added_by );
						}
					?>

					<div class="stm-dealer-name">
						<?php if(!empty($user_exist) and $user_exist): ?>
							<?php echo stm_display_user_name($user_added_by); ?>
						<?php endif; ?>
					</div>


					<?php
						$price = get_post_meta(get_the_ID(), 'price', true);
						$sale_price = get_post_meta(get_the_ID(), 'sale_price', true);

						if(empty($price) and !empty($sale_price)) {
							$price = $sale_price;
						}
					?>

					<div class="clearfix">

						<?php if(!empty($price)): ?>
							<div class="stm-price heading-font"><?php echo esc_attr(stm_listing_price_view($price)); ?></div>
						<?php endif; ?>

						<?php
							$labels = stm_get_car_listings();
							if(!empty($labels[0])) {
								$labels = $labels[0];
							}
						?>

						<?php if(!empty($labels)): ?>
							<div class="stm-car-similar-meta">
								<?php
									$value = '';
									if(!empty($labels['numeric']) and $labels['numeric']) {
										$value = get_post_meta(get_the_ID(), $labels['slug'], true);
										if(!empty($labels['number_field_affix'])) {
											$value .= ' '.$labels['number_field_affix'];
										}
									} else {
										$meta = get_post_meta(get_the_ID(), $labels['slug'], true);
										if(!empty($meta)) {
											$meta = explode(',', $meta);
											if(!empty($meta[0])) {
												$meta = get_term_by('slug', $meta[0], $labels['slug']);
												$value = $meta->name;
											}
										}
									}
								?>

								<?php if(!empty($labels['font'])): ?>
									<i class="<?php echo esc_attr($labels['font']) ?>"></i>
								<?php endif; ?>

								<?php if(!empty($value)): ?>
									<span><?php echo esc_attr($value); ?></span>
								<?php endif; ?>

							</div>
						<?php endif; ?>

					</div>

				</div>
			</a>
		<?php endwhile; ?>
	</div>

<?php endif; ?>
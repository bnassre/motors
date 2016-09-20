<?php
	//Get filter content from custom function from inc/custom.php
	$stm_listing_filter = stm_get_filter();

	$listing = $stm_listing_filter['listing_query'];

	$filter_badges = $stm_listing_filter['badges'];

	$filter_links = stm_get_car_filter_links();

	$listing_filter_position = get_theme_mod('listing_filter_position', 'left');
	if(!empty($_GET['filter_position']) and $_GET['filter_position'] == 'right') {
		$listing_filter_position = 'right';
	}

	$sidebar_pos_classes = '';
	$content_pos_classes = '';

	if($listing_filter_position == 'right') {
		$sidebar_pos_classes = 'col-md-push-9 col-sm-push-0';
		$content_pos_classes = 'col-md-pull-3 col-sm-pull-0';
	}
?>

	<div class="row">
		<div class="col-md-3 col-sm-12 classic-filter-row sidebar-sm-mg-bt <?php echo esc_attr($sidebar_pos_classes); ?>">
			<?php echo ($stm_listing_filter['filter_html']); ?>
		</div>
		<div class="col-md-9 col-sm-12 <?php echo esc_attr($content_pos_classes); ?>">
			<div class="stm-ajax-row">
				<div class="stm-car-listing-sort-units clearfix">
					<div class="stm-sort-by-options clearfix">
						<span><?php esc_html_e('Sort by:', 'motors'); ?></span>
						<div class="stm-select-sorting">
							<select>
								<option value="date_high" selected><?php esc_html_e( 'Date: newest first', 'motors' ); ?></option>
								<option value="date_low"><?php esc_html_e( 'Date: oldest first', 'motors' ); ?></option>
								<option value="price_low"><?php esc_html_e( 'Price: lower first', 'motors' ); ?></option>
								<option value="price_high"><?php esc_html_e( 'Price: highest first', 'motors' ); ?></option>
								<option value="mileage_low"><?php esc_html_e( 'Mileage: lowest first', 'motors' ); ?></option>
								<option value="mileage_high"><?php esc_html_e( 'Mileage: highest first', 'motors' ); ?></option>
							</select>
						</div>
					</div>
					<?php
						$view_list = '';
						$view_grid = '';
						$current_link_args = array();
						if(!empty($_GET)){
							$current_link_args = $_GET;
						}

						$view_list_link = $view_grid_link = $current_link_args;
						$view_list_link['view_type'] = 'list';
						$view_grid_link['view_type'] = 'grid';


						if(!empty($_GET['view_type'])) {
							if ( $_GET['view_type'] == 'list' ) {
								$view_list = 'active';
							} elseif ( $_GET['view_type'] == 'grid' ) {
								$view_grid = 'active';
								$current_link_args['view_type'] = 'grid';
							}
						} else {
							$view_list = 'active';
						}

					?>
					<div class="stm-view-by">
						<a href="<?php echo esc_url(add_query_arg($view_grid_link, stm_get_listing_archive_link())); ?>" class="view-grid view-type <?php echo esc_attr($view_grid); ?>">
							<i class="stm-icon-grid"></i>
						</a>
						<a href="<?php echo esc_url(add_query_arg($view_list_link, stm_get_listing_archive_link())); ?>" class="view-list view-type <?php echo esc_attr($view_list); ?>">
							<i class="stm-icon-list"></i>
						</a>
					</div>
				</div>

				<?php if(!empty($filter_badges)): ?>
					<div class="stm-filter-chosen-units">
						<?php echo $filter_badges; ?>
					</div>
				<?php endif; ?>

				<?php if($listing->have_posts()): ?>

					<?php if($view_grid == 'active'): ?>
						<div class="row row-3 car-listing-row <?php if($view_grid == 'active'){echo esc_attr('car-listing-modern-grid');} ?>">
					<?php endif; ?>


					<div class="stm-isotope-sorting">
						<?php while($listing->have_posts()): $listing->the_post();
							if($view_grid == 'active'){
								if(stm_is_listing()){
									get_template_part( 'partials/listing-cars/listing-grid', 'loop' );
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
						endwhile; ?>
					</div>

					<?php if($view_grid == 'active'): ?>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="row">
						<div class="col-md-12">
							<h3><?php esc_html_e('Sorry, No results', 'motors') ?></h3>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<!--Pagination-->
			<div class="row stm-ajax-pagination classic-filter-pagination">
				<!--Pagination-->
				<?php
				$show_pagination = true;

				if($listing->found_posts == 0) {
					$show_pagination = false;
				}

				if(!empty($listing->found_posts) and !empty($listing->query_vars['posts_per_page'])) {
					if($listing->found_posts < $listing->query_vars['posts_per_page']) {
						$show_pagination = false;
					}
				}
				if($show_pagination): ?>
					<div class="col-md-12">
						<div class="stm-blog-pagination">
							<?php if ( !get_previous_posts_link('<i class="fa fa-angle-left"></i>', $listing->max_num_pages ) ) {
								echo '<div class="stm-prev-next stm-prev-btn disabled"><i class="fa fa-angle-left"></i></div>';
							}

							echo paginate_links( array(
								'base'           => stm_get_listing_archive_link() . '%_%',
								'type'           => 'list',
								'total'          => $listing->max_num_pages,
								'posts_per_page' => $listing->query_vars['posts_per_page'],
								'prev_text'      => '<i class="fa fa-angle-left"></i>',
								'next_text'      => '<i class="fa fa-angle-right"></i>',
							) );

							if ( ! get_next_posts_link('<i class="fa fa-angle-right"></i>', $listing->max_num_pages ) ) {

								echo '<div class="stm-prev-next stm-next-btn disabled"><i class="fa fa-angle-right"></i></div>';
							} ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div> <!--col-md-9-->
	</div>

	<?php wp_reset_postdata(); ?>
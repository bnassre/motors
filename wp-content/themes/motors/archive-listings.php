<?php
	get_header();

		get_template_part('partials/title_box'); ?>

		<?php
			$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
			$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
			$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
			if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)) {
				wp_enqueue_script( 'stm_grecaptcha' );
			}
		?>

		<div class="archive-listing-page">
			<div class="container">
				<?php
					if(stm_is_listing()) {
						get_template_part( 'partials/listing-cars/listing-directory', 'archive' );
					} else {
						get_template_part( 'partials/listing-cars/listing', 'archive' );
					}
				?>
			</div>
		</div>


<?php get_footer();
get_template_part('partials/modals/test', 'drive');
get_template_part('partials/single-car/single-car-compare-modal'); ?>
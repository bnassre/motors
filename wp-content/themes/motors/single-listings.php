<?php get_header();?>
<?php get_template_part('partials/title_box'); ?>

	<div class="stm-single-car-page">

		<?php
			$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
			$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
			$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
			if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)) {
				wp_enqueue_script( 'stm_grecaptcha' );
			}
		?>

		<div class="container">
			<?php if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					$vc_status = get_post_meta( get_the_ID() , '_wpb_vc_js_status', true);

					if( $vc_status != 'false' && $vc_status == true ) {
						the_content();
					} else {
						if(stm_is_listing()) {
							get_template_part( 'partials/single-car-listing/car', 'main' );
						} else {
							get_template_part( 'partials/single-car/car', 'main' );
						}
					}

				endwhile;

			endif; ?>

			<div class="clearfix">
				<?php /*
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				} */
				?>
			</div>
		</div> <!--cont-->
	</div> <!--single car page-->
<?php get_template_part('partials/modals/test', 'drive'); ?>
<?php get_template_part('partials/modals/get-car', 'price'); ?>
<?php get_template_part('partials/single-car/single-car-compare-modal'); ?>
<?php get_footer();?>
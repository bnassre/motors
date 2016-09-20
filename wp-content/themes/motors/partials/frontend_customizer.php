<?php
	$service = false; 
	if(is_page_template('home-service-layout.php')) {
		$service = true;
	}
?>

<div id="frontend_customizer" style="left: -276px;">
	<div class="customizer_wrapper">

		<h3><?php esc_html_e('Nav Mode', 'motors'); ?></h3>

		<div class="customizer_element">
			<div class="stm_switcher active" id="navigation_type">
				<div class="switcher_label disable"><?php esc_html_e('Static', 'motors'); ?></div>
				<div class="switcher_nav"></div>
				<div class="switcher_label enable"><?php esc_html_e('Sticky', 'motors'); ?></div>
			</div>
		</div>

		<?php if(is_front_page() and !$service and !stm_is_listing()): ?>
			<h3><?php esc_html_e('Nav Transparency', 'motors'); ?></h3>

			<div class="customizer_element">
				<div class="stm_switcher active" id="navigation_transparency">
					<div class="switcher_label disable"><?php esc_html_e('Off', 'motors'); ?></div>
					<div class="switcher_nav"></div>
					<div class="switcher_label enable"><?php esc_html_e('On', 'motors'); ?></div>
				</div>
			</div>
		<?php endif; ?>

		<h3><?php esc_html_e('Layout', 'motors'); ?></h3>

		<div class="customizer_element">
			<div class="stm_switcher active" id="layout_mode">
				<div class="switcher_label disable"><?php esc_html_e('Boxed', 'motors'); ?></div>
				<div class="switcher_nav"></div>
				<div class="switcher_label enable"><?php esc_html_e('Wide', 'motors'); ?></div>
			</div>
		</div>
		
		<div class="customizer_boxed_background">
			<h3><?php esc_html_e( 'Background Image', 'motors' ); ?></h3>
	
			<div class="customizer_element">
				<div class="customizer_colors" id="background_image">
					<span id="boxed_fifth_bg" class="active" data-image="box_img_5"></span>
					<span id="boxed_first_bg" data-image="box_img_1"></span>
					<span id="boxed_second_bg" data-image="box_img_2"></span>
					<span id="boxed_third_bg" data-image="box_img_3"></span>
					<span id="boxed_fourth_bg" data-image="box_img_4"></span>
					
				</div>
			</div>
		</div>

		<h3><?php esc_html_e( 'Color Skin', 'motors' ); ?></h3>

		<div class="customizer_element">
			<div class="customizer_colors" id="skin_color">
				<span id="site_style_default" class="active" data-logo=""></span>
				<span id="site_style_red" data-logo="_2"></span>
				<span id="site_style_orange" data-logo="_3"></span>
				<span id="site_style_light_blue" data-logo="_4"></span>
				<span id="site_style_blue" data-logo="_5"></span>
			</div>
		</div>

	</div>
	<div id="frontend_customizer_button"><i class="fa fa-cog"></i></div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		"use strict";

		$(window).load(function () {
			$("#frontend_customizer").animate({left: -233}, 300);
		});

		$("#frontend_customizer_button").on('click', function () {
			if ($("#frontend_customizer").hasClass('open')) {
				$("#frontend_customizer").animate({left: -233}, 300);
				$("#frontend_customizer").removeClass('open');
			} else {
				$("#frontend_customizer").animate({left: 0}, 300);
				$("#frontend_customizer").addClass('open');
			}
		});

		$('body').on('click', function (kik) {
			if (!$(kik.target).is('#frontend_customizer, #frontend_customizer *') && $('#frontend_customizer').is(':visible')) {
				$("#frontend_customizer").animate({left: -233}, 300);
				$("#frontend_customizer").removeClass('open');
			}
		});

		$("#skin_color span").on('click', function () {
			var style_id = $(this).attr('id');
			var logo_num = $(this).data('logo');
			
			var logo_url = '<?php echo esc_url(get_template_directory_uri().'/assets/images/tmp/logo'); ?>' + logo_num + '.svg';
			
			$("#skin_color .active").removeClass("active");
			
			$(this).addClass("active");
			
			$("#custom_style").remove();
			$("#custom_style_listing").remove();
			
			if( style_id != 'site_style_default' ){
				$('#custom_style').remove();
				$("head").append('<link rel="stylesheet" id="custom_style" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/'+style_id+'.css?v=4" type="text/css" media="all">');
				$("head").append('<link rel="stylesheet" id="custom_style_listing" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/css/listing/'+style_id+'.css?v=4" type="text/css" media="all">');
				
				$('#header .logo-main img').attr('src', logo_url);
				$('#header .service-logo-main img').attr('src', logo_url);
			} else {
				
				$('#header .logo-main img').attr('src', logo_url);
				$('#header .service-logo-main img').attr('src', logo_url);
			}
		});


		$("#navigation_type").on("click", function () {
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');

				$('.header-nav').removeClass('header-nav-fixed');
				
				$('.header-service').removeClass('header-service-sticky header-service-fixed');

				$('.header-listing').removeClass('header-listing-fixed stm-fixed stm-fixed-invisible');
			} else {
				$(this).addClass('active');

				$('.header-nav').addClass('header-nav-fixed');
				
				$('.header-service').addClass('header-service-fixed');

				$('.header-listing').addClass('header-listing-fixed');
			}
		});

		$("#navigation_transparency").on("click", function () {
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');

				$('.header-nav').removeClass('header-nav-transparent');
				$('.header-nav').addClass('header-nav-default');
			} else {
				$(this).addClass('active');

				$('.header-nav').addClass('header-nav-transparent');
				$('.header-nav').removeClass('header-nav-default');

			}
		});

		$("#layout_mode").on("click", function () {
			if ($(this).hasClass('active')) {
				$(this).removeClass('active');

				$('body').addClass('stm-boxed');
				$('.customizer_boxed_background').slideDown();
				
				$('body').addClass('stm-background-customizer-box_img_5');
			} else {
				$(this).addClass('active');

				$('body').removeClass('stm-boxed');
				$('.customizer_boxed_background').slideUp();
				
				$('body').addClass('stm-background-customizer-box_img_5');
			}
		});
		
		$('#background_image span').on('click', function(){
			$('#background_image span').removeClass('active');
			$(this).addClass('active');
			
			var img_src = $(this).data('image');
			
			$('body').removeClass('stm-background-customizer-box_img_1 stm-background-customizer-box_img_2 stm-background-customizer-box_img_3 stm-background-customizer-box_img_4 stm-background-customizer-box_img_5');
			
			$('body').addClass('stm-background-customizer-' + img_src);
		});

	});

</script>
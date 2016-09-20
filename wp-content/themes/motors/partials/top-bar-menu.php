<ul>
<?php
	wp_nav_menu( array(
			'menu'              => 'top_bar',
			'theme_location'    => 'top_bar',
			'depth'             => 1,
			'container'         => false,
			'menu_class'        => 'top-bar-menu clearfix',
			'items_wrap'        => '%3$s',
			'fallback_cb' => false
		)
	);
?>
</ul>
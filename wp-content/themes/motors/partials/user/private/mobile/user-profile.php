<?php
$user        = wp_get_current_user();
$user_id     = $user->ID;
$user_fields = stm_get_user_custom_fields( $user_id );

$main          = 'active';
$fav           = '';
$settings      = '';
$become_dealer = '';

if ( ! empty( $_GET['my_favourites'] ) and $_GET['my_favourites'] ) {
	$main          = '';
	$fav           = 'active';
	$settings      = '';
	$become_dealer = '';
}

if ( ! empty( $_GET['my_settings'] ) and $_GET['my_settings'] ) {
	$main          = '';
	$fav           = '';
	$settings      = 'active';
	$become_dealer = '';
}

if ( ! empty( $_GET['become_dealer'] ) and $_GET['become_dealer'] ) {
	$main          = '';
	$fav           = '';
	$settings      = '';
	$become_dealer = 'active';
}

?>

<div class="stm-user-private">
	<div class="stm-user-private-sidebar">

		<div class="clearfix stm-user-top">

			<div class="stm-user-avatar">
				<?php if ( ! empty( $user_fields['image'] ) ): ?>
					<img class="img-responsive img-avatar" src="<?php echo esc_url( $user_fields['image'] ); ?>"/>
				<?php else: ?>
					<div class="stm-empty-avatar-icon"><i class="stm-service-icon-user"></i></div>
				<?php endif; ?>
			</div>

			<div class="stm-user-profile-information">
				<div class="title heading-font"><?php echo esc_attr( stm_display_user_name( $user->ID ) ); ?></div>
				<div class="title-sub"><?php esc_html_e( 'Private Seller', 'motors' ); ?></div>
				<?php if ( ! empty( $user_fields['socials'] ) ): ?>
					<div class="socials clearfix">
						<?php foreach ( $user_fields['socials'] as $social_key => $social ): ?>
							<a href="<?php echo esc_url( $social ); ?>">
								<?php
								if ( $social_key == 'youtube' ) {
									$social_key = 'youtube-play';
								}
								?>
								<i class="fa fa-<?php echo esc_attr( $social_key ); ?>"></i>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		</div>

		<div class="stm-became-dealer">
			<a href="<?php echo esc_url( add_query_arg( array( 'become_dealer' => 1 ), stm_get_author_link( '' ) ) ); ?>"
			   class="button stm-dp-in"><?php esc_html_e( 'Become a dealer', 'motors' ); ?></a>
		</div>

		<div class="stm-actions-list heading-font">

			<a
				href="<?php echo esc_url( stm_get_author_link( '' ) ); ?>" class="<?php echo esc_attr( $main ); ?>">
				<i class="stm-service-icon-inventory"></i><?php esc_html_e( 'My Inventory', 'motors' ) ?>
			</a>

			<a
				href="<?php echo esc_url( add_query_arg( array( 'my_favourites' => 1 ), stm_get_author_link( '' ) ) ); ?>""
			class="<?php echo esc_attr( $fav ); ?>">
			<i class="stm-service-icon-star-o"></i>
			<?php esc_html_e( 'My Favorites', 'motors' ) ?>
			</a>

			<a
				href="<?php echo esc_url( add_query_arg( array( 'my_settings' => 1 ), stm_get_author_link( '' ) ) ); ?>""
			class="<?php echo esc_attr( $settings ); ?>">
			<i class="fa fa-cog"></i>
			<?php esc_html_e( 'Profile Settings', 'motors' ) ?>
			</a>

		</div>

		<?php if ( ! empty( $user_fields['phone'] ) ): ?>
			<div class="stm-dealer-phone">
				<i class="stm-service-icon-phone"></i>

				<div class="phone-label heading-font"><?php esc_html_e( 'Seller Contact Phone', 'motors' ); ?></div>
				<div class="phone"><?php echo esc_attr( $user_fields['phone'] ); ?></div>
			</div>
		<?php endif; ?>

		<div class="stm-dealer-mail">
			<i class="fa fa-envelope-o"></i>

			<div class="mail-label heading-font"><?php esc_html_e( 'Seller Email', 'motors' ); ?></div>
			<div class="mail"><a href="mailto:<?php echo esc_attr( $user->data->user_email ); ?>">
					<?php echo esc_attr( $user->data->user_email ); ?>
				</a></div>
		</div>

		<div class="show-my-profile">
			<a href="<?php echo esc_url( stm_get_author_link( 'myself-view' ) ); ?>" target="_blank"><i
					class="fa fa-external-link"></i><?php esc_html_e( 'Show my Public Profile', 'motors' ); ?></a>
		</div>

		<div class="show-my-profile">
			<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><i
					class="fa fa-sign-out"></i><?php esc_html_e( 'Logout', 'motors' ); ?></a>
		</div>

	</div>
</div>
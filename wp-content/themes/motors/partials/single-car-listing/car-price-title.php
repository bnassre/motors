<?php
	$price = get_post_meta(get_the_ID(), 'price', true);
	$sale_price = get_post_meta(get_the_ID(), 'sale_price', true);

	if(empty($price) and !empty($sale_price)) {
		$price = $sale_price;
	}
?>

<div class="stm-listing-single-price-title heading-font clearfix">
	<?php if(!empty($price)): ?>
		<div class="price"><?php echo stm_listing_price_view($price); ?></div>
	<?php endif; ?>
	<div class="title">
		<?php echo stm_generate_title_from_slugs(get_the_ID(), true); ?>
	</div>
</div>
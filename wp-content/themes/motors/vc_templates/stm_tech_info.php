<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

?>

<?php if(!empty($name) and !empty($value)): ?>
	<tr>
		<td>
			<span class="text-transform"><?php echo esc_attr($name) ?></span>
		</td>
		<td class="text-right">
			<span class="h6"><?php echo esc_attr($value) ?></span>
		</td>
	</tr>
<?php endif; ?>
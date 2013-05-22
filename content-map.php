<?php
$conf = mappress_get_map_conf();
$data = mappress_get_map_data();
$data['conf'] = $conf;
?>

<div class="map-container">
	<div id="map_<?php echo mappress_get_map_id(); ?>" class="map"></div>
	<?php if(is_single()) : ?>
		<?php if(mappress_has_marker_location()) : ?>
			<div class="highlight-point transition has-end" data-end="1300"></div>
		<?php endif; ?>
	<?php endif; ?>
	<?php do_action('mappress_map'); ?>
</div>
<?php print_r($data); ?>
<script type="text/javascript">mappress(mappress.convertMapConf(<?php echo json_encode($data); ?>));</script>
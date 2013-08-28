<?php
$conf = jeo_get_map_conf();
$data = jeo_get_map_data();
$data['conf'] = $conf;
?>

<div class="map-container">
	<div id="map_<?php echo jeo_get_map_id(); ?>" class="map"></div>
	<?php if(is_single()) : ?>
		<?php if(jeo_has_marker_location()) : ?>
			<div class="highlight-point transition has-end" data-end="1300"></div>
		<?php endif; ?>
	<?php endif; ?>
	<?php do_action('jeo_map'); ?>
	<?php if(is_single()) : ?>
		<a class="button light home-link" href="<?php echo home_url('/'); ?>" title="<?php _e('Go back to the main map', 'arteforadomuseu'); ?>"><?php _e('Back to main map', 'arteforadomuseu'); ?></a>
	<?php endif; ?>
</div>
<script type="text/javascript">jeo(jeo.parseConf(<?php echo json_encode($data); ?>));</script>
<?php

/* 
 * Arte Fora do Museu
 * Mobile map/list
 */

class AFDM_Mobile_MapList {

	function __construct() {

		add_action('init', array($this, 'init'));

	}

	function init() {

		add_action('wp_footer', array($this, 'wp_footer'));

	}

	function wp_footer() {
		wp_enqueue_script('afdm-mobile-map-list', get_stylesheet_directory_uri() . '/inc/mobile-map-list/js/mobile-map-list.js', array('jquery'), '0.1.0');
		wp_enqueue_style('afdm-mobile-map-list', get_stylesheet_directory_uri() . '/inc/mobile-map-list/css/mobile-map-list.css');
		?>
		<div class="mobile-map-list-selector">
			<?php if(is_single()) : ?>
				<a href="#" class="map-selector"><?php _e('Map', 'arteforadomuseu'); ?></a>
				<a href="#" class="list-selector"><?php _e('About', 'arteforadomuseu'); ?></a>
			<?php else : ?>
				<a href="#" class="map-selector"><?php _e('Map', 'arteforadomuseu'); ?></a>
				<a href="#" class="list-selector"><?php _e('List', 'arteforadomuseu'); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}

}

new AFDM_Mobile_MapList();
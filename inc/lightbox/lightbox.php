<?php

/*
 * Arte Fora do Museu
 * Notification messages
 */

class ArteForaDoMuseu_Lightbox {

	var $directory_uri = '';

	function __construct() {
		$this->set_directories();
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
	}

	function set_directories() {
		$this->directory_uri = apply_filters('lightbox_directory_uri', get_stylesheet_directory_uri() . '/inc/lightbox');
	}

	function scripts() {
		wp_enqueue_script('afdm-lightbox', $this->directory_uri . '/js/lightbox.js', array('jquery'), '1.6.2');
	}

}

new ArteForaDoMuseu_Lightbox;

?>
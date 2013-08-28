<?php

/*
 * Arte Fora do Museu
 * Count views system
 */

class ArteForaDoMuseu_Views {

	var $post_types = array('post');

	function __construct() {
		add_action('jeo_init', array($this, 'setup'));
	}

	function setup() {
		$this->post_types();
		$this->setup_views();
	}

	function setup_views() {
		add_action('wp_head', array($this, 'hook_views'));
		add_action('save_post', array($this, 'first_view'));
	}

	function post_types() {
		$this->post_types =	apply_filters('afdm_views_post_types', $this->post_types);
		return $this->post_types;
	}

	function hook_views() {
		if(is_singular($this->post_types)) {
			global $post;
			$this->add_view($post->ID);
		}
	}

	function first_view($post_id) {
		if(in_array(get_post_type($post_id), $this->post_types)) {
			if(!$this->get_views($post_id) || $this->get_views($post_id) === 0)
				update_post_meta($post_id, '_views', 0);
		}
	}

	function add_view($post_id) {
		if(!$post_id)
			return false;

		$views = get_post_meta($post_id, '_views', true);
		$views = $views ? $views + 1 : 1;

		update_post_meta($post_id, '_views', $views);
	}

	function get_views($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$views = get_post_meta($post_id, '_views', true);
		return $views ? $views : 0;
	}

	function get_popular($amount = 5) {
		return get_posts(get_popular_query(array('posts_per_page' => $amount)));
	}

	function get_popular_query($query = array()) {
		$popular = array(
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'meta_key' => '_views'
		);
		return array_merge($query, $popular);
	}

}

$views = new ArteForaDoMuseu_Views();

function afdm_get_views($post_id = false) {
	global $views;
	return $views->get_views($post_id);
}

function afdm_get_popular($amount = 5) {
	global $views;
	return $views->get_popular($amount);
}

function afdm_get_popular_query($query = array()) {
	global $views;
	return $views->get_popular_query($query);
}
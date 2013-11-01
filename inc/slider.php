<?php

/*
 * Arte Fora do Museu
 * Slider
 */

class AFDM_Slider {


	function __construct() {
		add_action('init', array($this, 'init'));
	}

	function init() {

		$this->register_post_type();
		$this->acf_fields();
		add_filter('post_link', array($this, 'post_link'));
		add_filter('the_permalink', array($this, 'post_link'));

	}

	function register_post_type() {

		$labels = array( 
			'name' => __('Slider', 'arteforadomuseu'),
			'singular_name' => __('Slider item', 'arteforadomuseu'),
			'add_new' => __('Add slider item', 'arteforadomuseu'),
			'add_new_item' => __('Add new slider item', 'arteforadomuseu'),
			'edit_item' => __('Edit slider item', 'arteforadomuseu'),
			'new_item' => __('New slider item', 'arteforadomuseu'),
			'view_item' => __('View slider item', 'arteforadomuseu'),
			'search_items' => __('Search slider items', 'arteforadomuseu'),
			'not_found' => __('No slider item found', 'arteforadomuseu'),
			'not_found_in_trash' => __('No slider item found in the trash', 'arteforadomuseu'),
			'menu_name' => __('Featured slider', 'arteforadomuseu')
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __('Arte Fora do Museu slider', 'arteforadomuseu'),
			'supports' => array('title', 'thumbnail'),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'has_archive' => false,
			'menu_position' => 2
		);

		register_post_type('slider', $args);

	}

	function acf_fields() {

		/*
		 * ACF Fields
		 */
		if(function_exists("register_field_group")) {

			$translate_fields = array(
				'wysiwyg' => 'wysiwyg',
				'text' => 'text',
				'textarea' => 'textarea'
			);

			if(function_exists('qtrans_getLanguage')) {
				foreach($translate_fields as &$field) {
					$field = 'qtranslate_' . $field;
				}
			}

			register_field_group(array (
				'id' => 'acf_slider-settings',
				'title' => 'Slider settings',
				'fields' => array (
					array (
						'default_value' => '',
						'formatting' => 'html',
						'key' => 'field_51e32e3c411bd',
						'label' => 'Link',
						'name' => 'slider_url',
						'type' => $translate_fields['text'],
						'instructions' => 'Link to where the slider item will redirect',
						'required' => 1,
					)
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'slider',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array (
					),
				),
				'menu_order' => 0,
			));

		}

	}

	function post_link($permalink) {
		global $post;
		if(get_post_type() == 'slider')
			return get_field('slider_url');
		return $permalink;
	}

}

$afdm_slider = new AFDM_Slider();
<?php

/*
 * Arte Fora do Museu
 * Featured content
 */

class ArteForaDoMuseu_Featured {

	var $post_types = array('post');

	var $featured_meta = 'featured_content';

	function __construct() {
		add_action('jeo_init', array($this, 'setup'));
	}

	function setup() {
		$this->set_post_types();
		add_action('add_meta_boxes', array($this, 'add_metabox'));
		add_action('save_post', array($this, 'save'));
	}

	function set_post_types() {
		$this->post_types = apply_filters('afdm_featured_post_types', $this->post_types);
		return $this->post_types;
	}

	function add_metabox() {
		foreach($this->post_types as $post_type) {
			add_meta_box(
				'featured-metabox',
				__('Featured', 'arteforadomuseu'),
				array($this, 'box'),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	function box($post = false) {
		$featured = ($post) ? $this->is_featured($post->ID) : false;
		?>
		<div class="featured-box">
			<input type="checkbox" name="featured_content" id="featured_content" value="1" <?php if($featured) echo 'checked'; ?> />
			<label for="featured_content"><?php _e('Featured content', 'arteforadomuseu'); ?></label>
		</div>
		<?php
	}

	function save($post_id) {

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (defined('DOING_AJAX') && DOING_AJAX)
			return;

		if (wp_is_post_revision($post_id) !== false)
			return;

		if(isset($_REQUEST['featured_content']) && $_REQUEST['featured_content'])
			update_post_meta($post_id, $this->featured_meta, $_REQUEST['featured_content']);
		else
			delete_post_meta($post_id, $this->featured_meta);
	}

	function is_featured($post_id) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		return get_post_meta($post_id, $this->featured_meta, true);
	}

}

$featured = new ArteForaDoMuseu_Featured;

function afdm_is_featured($post_id = false) {
	global $featured;
	return $featured->is_featured($post_id);
}
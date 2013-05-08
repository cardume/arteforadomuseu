<?php

/*
 * Arte Fora do Museu
 * Artworks
 */

class ArteForaDoMuseu_Artworks {

	var $post_type = 'post';

	var $taxonomy_slugs = array(
		'style' => 'estilo'
	);

	var $directory_uri = '';

	var $directory = '';

	function __construct() {
		$this->set_directories();
		$this->setup_ajax();
		$this->setup_scripts();
		$this->register_taxonomies();
		$this->setup_meta_boxes();
		$this->hook_ui_elements();
	}

	function set_directories() {
		$this->directory_uri = apply_filters('artguides_directory_uri', get_stylesheet_directory_uri() . '/inc/artworks');
		$this->directory = apply_filters('artguides_directory', get_stylesheet_directory() . '/inc/artworks');
	}

	/*
	 * Ajax stuff
	 */
	function setup_ajax() {
		add_action('wp_ajax_nopriv_submit_artwork', array($this, 'ajax_add'));
		add_action('wp_ajax_submit_artwork', array($this, 'ajax_add'));
	}

	function ajax_response($data) {
		header('Content Type: application/json');
		echo json_encode($data);
		exit;
	}

	function ajax_add() {
		$this->ajax_response(array('error_msg' => 'Em desenvolvimento'));
	}

	/*
	 * Scripts
	 */

	function setup_scripts() {
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		add_action('mappress_geocode_scripts', array($this, 'geocode_scripts'));
	}

	function scripts() {
		wp_enqueue_script('afdm-artworks', $this->directory_uri . '/js/artworks.js', array('jquery', 'afdm-lightbox', 'jquery-autosize'), '0.0.5');
		wp_localize_script('afdm-artworks', 'artworks', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'sending_msg' => __('Sending data...', 'arteforadomuseu')
		));
	}

	function geocode_scripts() {
		$geocode_service = mappress_get_geocode_service();
		$gmaps_key = mappress_get_gmaps_api_key();
		if($geocode_service == 'gmaps' && $gmaps_key)
			wp_enqueue_script('google-maps-api');
		wp_enqueue_script('mappress.geocode.box');
	}

	/*
	 * Taxonomies
	 */

	function register_taxonomies() {
		add_action('init', array($this, 'taxonomy_style'));
	}

	function taxonomy_style() {

		$labels = array( 
			'name' => __('Styles', 'arteforadomuseu'),
			'singular_name' => __('Style', 'arteforadomuseu'),
			'search_items' => __('Search styles', 'arteforadomuseu'),
			'popular_items' => __('Popular styles', 'arteforadomuseu'),
			'all_items' => __('All styles', 'arteforadomuseu'),
			'parent_item' => __('Parent style', 'arteforadomuseu'),
			'parent_item_colon' => __('Parent style:', 'arteforadomuseu'),
			'edit_item' => __('Edit style', 'arteforadomuseu'),
			'update_item' => __('Update style', 'arteforadomuseu'),
			'add_new_item' => __('Add new style', 'arteforadomuseu'),
			'new_item_name' => __('New style name', 'arteforadomuseu'),
			'separate_items_with_commas' => __('Separate styles with commas', 'arteforadomuseu'),
			'add_or_remove_items' => __('Add or remove styles', 'arteforadomuseu'),
			'choose_from_most_used' => __('Choose from most used styles', 'arteforadomuseu'),
			'menu_name' => __('Styles', 'arteforadomuseu')
		);

		$args = array( 
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array('slug' => $this->taxonomy_slugs['style'], 'with_front' => false),
			'query_var' => true
		);

		register_taxonomy('style', array($this->post_type), $args);
	}

	/*
	 * Meta boxes
	 */

	function setup_meta_boxes() {
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
	}

	// Add meta boxes
	function add_meta_boxes() {
		// Dimensions
		add_meta_box(
			'artwork_dimensions',
			__('Artwork dimensions', 'arteforadomuseu'),
			array($this, 'box_artwork_dimensions'),
			$this->post_type,
			'advanced',
			'high'
		);

		// Dates
		add_meta_box(
			'artwork_dates',
			__('Artwork dates', 'arteforadomuseu'),
			array($this, 'box_artwork_dates'),
			$this->post_type,
			'advanced',
			'high'
		);
	}

	function box_artwork_dimensions($post = false) {
		if($post) {
			$width = $this->get_artwork_width();
			$height = $this->get_artwork_height();
		}
		?>
		<div id="artwork_dimensions_box">
			<h4><?php _e('Artwork dimensions', 'arteforadomuseu'); ?></h4>
			<div class="box-inputs">
				<p class="input-container dimensions-width">
					<input placeholder="<?php _e('Width', 'arteforadomuseu'); ?>" type="text" name="artwork_dimensions_width" id="artwork_dimensions_width" value="<?php echo $width; ?>" />
					<label for="artwork_dimensions_width"><?php _e('cm', 'arteforadomuseu'); ?></label>
				</p>
				<p class="input-container dimensions-height">
					<input placeholder="<?php _e('Height', 'arteforadomuseu'); ?>" type="text" name="artwork_dimensions_height" id="artwork_dimensions_height" value="<?php echo $height; ?>" />
					<label for="artwork_dimensions_height"><?php _e('cm', 'arteforadomuseu'); ?></label>
				</p>
			</div>
		</div>
		<?php
	}

	function box_artwork_dates($post = false) {

		wp_enqueue_style('jquery-ui-smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('artworks-box-dates', $this->directory_uri . '/js/artworks.box.dates.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-datepicker-pt-BR'), '0.0.5');
		wp_localize_script('artworks-box-dates', 'box_dates_settings', array(
			'dateFormat' => 'dd/mm/yy',
			'language' => get_bloginfo('language')
		));

		if($post) {
			$creation_date = $this->get_artwork_creation_date();
			$termination_date = $this->get_artwork_termination_date();
			$currently_active = $this->is_artwork_currently_active();
		}
		?>
		<div id="artwork_dates_box">
			<h4><?php _e('Creation and termination dates', 'arteforadomuseu'); ?></h4>
			<div class="box-inputs">
				<p class="input-container creation-date">
					<input placeholder="<?php _e('Creation date', 'arteforadomuseu'); ?>" class="datepicker" type="text" name="artwork_date_creation" id="artwork_date_creation" value="<?php echo $creation_date; ?>" />
				</p>
				<p class="input-container termination-date">
					<input placeholder="<?php _e('Termination date', 'arteforadomuseu'); ?>" class="datepicker" type="text" name="artwork_date_termination" id="artwork_date_termination" value="<?php echo $termination_date; ?>" />
				</p>
				<p class="input-container currently-active">
					<input type="checkbox" name="artwork_currently_active" id="artwork_currently_active" <?php if($currently_active) echo 'checked'; ?> /> <label for="artwork_currently_active"><?php _e('Currently active', 'arteforadomuseu'); ?></label>
				</p>
			</div>
		</div>
		<?php
	}

	/*
	 * Taxonomy boxes, for non-admin usage only
	 */

	function box_artwork_styles($post = false) {

		wp_enqueue_script('jquery-tag-it');
		wp_enqueue_style('jquery-tag-it');

		if($post) {
			$post_style_names = $this->get_artwork_style_names();
		}

		$styles = get_terms('style', array('hide_empty' => 0));
		$style_names = array();
		if($styles) {
			foreach($styles as $style) {
				$style_names[] = $style->name;
			}
		}
		?>
		<div id="artwork_styles_box">
			<h4><?php _e('Tag styles for this artwork', 'arteforadomuseu'); ?></h4>
			<div class="box-inputs">
				<ul id="style-tags">
					<?php
					if($post_style_names) {
						foreach($post_style_names as $style_name) {
							echo '<li>' . $style_name . '</li>';
						}
					}
					?>
				</ul>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#style-tags').tagit({
							fieldName: 'styles',
							tagLimit: 5,
							availableTags: <?php echo json_encode($style_names); ?>,
							autocomplete: { delay: 0, minLength: 2 }
						});
					});
				</script>
			</div>
		</div>
		<?php
	}

	function box_artwork_categories($post = false) {

		if($post) {
			$category = array_shift(get_the_category($post->ID));
			$category_id = $category->term_id;
		}

		$categories = get_categories(array('hide_empty' => 0));

		if(!$categories)
			return false;

		?>
		<div id="artwork_categories_box">
			<h4><?php _e('Select a category', 'arteforadomuseu'); ?></h4>
			<div class="box-inputs">
				<select id="artwork_categories_select" name="categories">
					<option></option>
					<?php foreach($categories as $category) : ?>
						<option value="<?php echo $category->term_id; ?>" <?php if($category->term_id == $category_id) echo 'selected'; ?>><?php echo $category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php
	}

	/*
	 * UI
	 */

	function hook_ui_elements() {
		if(current_user_can('edit_posts')) { 
			add_action('afdm_logged_in_user_menu_items', array($this, 'user_menu_items'));
			add_action('wp_footer', array($this, 'add_box'));
		}
	}

	function user_menu_items() {
		?>
		<li><a href="#" class="add_artwork"><?php _e('Submit an artwork', 'arteforadomuseu'); ?></a></li>
		<?php
	}

	function add_box() {
		?>
		<div id="add_artwork">
			<h2 class="lightbox_title"><span class="lsf">addnew</span> <?php _e('Submit new artwork', 'arteforadomuseu'); ?></h2>
			<div class="lightbox_content">
				<form id="new_artwork">
					<div class="form-inputs">
						<input type="text" name="title" class="title" placeholder="<?php _e('Title', 'arteforadomuseu'); ?>" />
						<textarea name="content" placeholder="<?php _e('Description', 'arteforadomuseu'); ?>"></textarea>
						<div class="clearfix">
							<div class="half-1">
								<?php $this->box_artwork_styles(); ?>
								<?php $this->box_artwork_categories(); ?>
							</div>
							<div class="half-2">
								<?php $this->box_artwork_dimensions(); ?>
							</div>
						</div>
						<div class="clearfix">
							<?php $this->box_artwork_dates(); ?>
						</div>
						<div class="clearfix">
							<?php mappress_geocode_box(); ?>
						</div>
					</div>
					<div class="form-actions">
						<input type="submit" value="<?php _e('Submit', 'arteforadomuseu'); ?>" />
						<a class="close" href="#"><?php _e('Cancel', 'arteforadomuseu'); ?></a>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/*
	 * Functions
	 */

	function get_artwork_width() {
		return false;
	}

	function get_artwork_height() {
		return false;
	}

	function get_artwork_creation_date() {
		return false;
	}

	function get_artwork_termination_date() {
		return false;
	}

	function is_artwork_currently_active() {
		return false;
	}

	function get_artwork_styles() {
		return false;
	}

	function get_artwork_style_names() {
		return false;
	}

}

new ArteForaDoMuseu_Artworks;
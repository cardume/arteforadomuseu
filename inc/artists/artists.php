<?php

/*
 * Arte Fora do Museu
 * Artists
 */

class ArteForaDoMuseu_Artists {

	var $post_type = 'artist';

	var $slug = '';

	var $directory_uri = '';

	var $directory = '';

	var $is_singular = false;

	function __construct() {
		add_action('jeo_init', array($this, 'setup'));
	}

	function setup() {
		$this->set_directories();
		$this->set_slug();
		$this->setup_post_type();
		$this->setup_metaboxes();
		$this->setup_query();
		$this->setup_views();
		$this->setup_marker_query();
		$this->setup_post();
		$this->setup_ajax();
		$this->setup_templates();
		$this->hook_ui_elements();
		$this->setup_jeditable();
	}

	function set_directories() {
		$this->directory_uri = apply_filters('artists_directory_uri', get_stylesheet_directory_uri() . '/inc/artists');
		$this->directory = apply_filters('artists_directory', get_stylesheet_directory() . '/inc/artists');
	}

	function set_slug() {
		$this->slug = apply_filters('artists_slug', 'artistas');
	}

	function setup_post_type() {
		$this->register_post_type();
		add_filter('jeo_mapped_post_types', array($this, 'unset_from_jeo_mapped'));
		add_filter('jeo_featured_post_types', array($this, 'setup_featured'));
	}

	function register_post_type() {

		$labels = array( 
			'name' => __('Artists', 'arteforadomuseu'),
			'singular_name' => __('Artist', 'arteforadomuseu'),
			'add_new' => __('Add artist', 'arteforadomuseu'),
			'add_new_item' => __('Add new artist', 'arteforadomuseu'),
			'edit_item' => __('Edit artist', 'arteforadomuseu'),
			'new_item' => __('New artist', 'arteforadomuseu'),
			'view_item' => __('View artist', 'arteforadomuseu'),
			'search_items' => __('Search artists', 'arteforadomuseu'),
			'not_found' => __('No artist found', 'arteforadomuseu'),
			'not_found_in_trash' => __('No artist found in the trash', 'arteforadomuseu'),
			'menu_name' => __('Artists', 'arteforadomuseu')
		);

		$args = array( 
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __('Artists', 'arteforadomuseu'),
			'supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail', 'comments'),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => $this->slug, 'with_front' => false)
		);

		register_post_type($this->post_type, $args);

		add_action('afdm_geo_post_types', array($this, 'geocode_post_type'));
	}

	function geocode_post_type($post_types) {
		$post_types[] = $this->post_type;
		return $post_types;
	}

	function unset_from_jeo_mapped($post_types) {
		unset($post_types[$this->post_type]);
		return $post_types;
	}

	function setup_featured($post_types) {
		$post_types[] = $this->post_type;
		return $post_types;
	}

	/*
	 * Metabox
	 */

	function setup_metaboxes() {
		add_action('add_meta_boxes', array($this, 'register_boxes'));	
		add_action('save_post', array($this, 'save_artist_age'));
		add_action('admin_footer', array($this, 'admin_css'));
	}

	function admin_css() {
		wp_enqueue_style('artwork-admin', $this->directory_uri . '/css/admin.css');
	}

	function register_boxes() {
		add_meta_box(
			'artist_age',
			__('Artist age', 'arteforadomuseu'),
			array($this, 'box_artist_age'),
			'artist',
			'advanced',
			'high'
		);
	}

	function box_artist_age($post = false) {

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui-smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');

		if($post) {
			$birth = get_post_meta($post->ID, 'birth_date', true);
			$death = get_post_meta($post->ID, 'death_date', true);
		}
		?>
		<div id="artist_age_box">
			<h4><?php _e('Artist\'s age', 'arteforadomuseu'); ?></h4>
			<p class="tip"><?php _e('Fill birth and death dates', 'arteforadomuseu'); ?></p>
			<div class="box-inputs">
				<p class="input-container">
					<input type="text" class="datepicker" name="birth_date" <?php if($birth) echo 'value="' . $birth . '"'; ?> placeholder="<?php _e('Birth date', 'arteforadomuseu'); ?>" />
				</p>
				<p class="input-container">
					<input type="text" class="datepicker" name="death_date" <?php if($death) echo 'value="' . $death . '"'; ?> placeholder="<?php _e('Death date', 'arteforadomuseu'); ?>" />
				</p>
			</div>
			<script type="text/javascript">
				(function($) {

					$(document).ready(function() {

						$('#artist_age_box .datepicker').datepicker({
							yearRange: '-500:+0',
							changeMonth: true,
							changeYear: true,
							dateFormat: 'dd/mm/yy'
						});

					});

				})(jQuery);
			</script>
		</div>
		<?php
	}

	function save_artist_age($post_id) {

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		if (defined('DOING_AJAX') && DOING_AJAX && !(defined('AFDM_ALLOWED_AJAX') && AFDM_ALLOWED_AJAX))
			return;

		if (false !== wp_is_post_revision($post_id))
			return;

		if(isset($_POST['birth_date'])) {
			update_post_meta($post_id, 'birth_date', $_POST['birth_date']);
		}

		if(isset($_POST['death_date'])) {
			update_post_meta($post_id, 'death_date', $_POST['death_date']);
		}
	}

	/*
	 * Custom query stuff
	 */

	function setup_query() {
		add_action('pre_get_posts', array($this, 'artists_query'), 5);
	}

	// remove singular from geo query
	function artists_query($query) {
		if($this->is_singular || is_singular($this->post_type)) {
			$query->set('not_geo_query', true);
		}
	}

	/*
	 * Add to view system
	 */
	function setup_views() {
		add_action('afdm_views_post_types', array($this, 'register_views'));
	}

	function register_views($post_types) {
		if(!in_array($this->post_type, $post_types))
			$post_types[] = $this->post_type;

		return $post_types;
	}

	/*
	 * Store singular post_id post
	 */

	function setup_post() {
		add_action('the_post', array($this, 'post'));
	}

	function post() {
		if(get_post_type(get_the_ID()) == $this->post_type) {
			global $post;
			$this->artist = $post;
			if(is_singular($this->post_type))
				$this->is_singular = true;
		}
	}

	/*
	 * Marker query
	 */

	function setup_marker_query() {
		add_filter('jeo_marker_query', array($this, 'query'));
	}

	function query($query) {
		if(is_singular($this->post_type)) {
			global $post;
			$query = $this->get_query($post->ID);
		}
		return $query;
	}

	/*
	 * Ajax actions
	 */

	function setup_ajax() {
		add_action('wp_ajax_nopriv_delete_artist', array($this, 'ajax_delete'));
		add_action('wp_ajax_delete_artist', array($this, 'ajax_delete'));
		add_action('wp_ajax_nopriv_remove_artwork_from_artist', array($this, 'ajax_remove_artwork'));
		add_action('wp_ajax_remove_artwork_from_artist', array($this, 'ajax_remove_artwork'));
		add_action('wp_ajax_nopriv_add_artwork_to_artist', array($this, 'ajax_add_artwork'));
		add_action('wp_ajax_add_artwork_to_artist', array($this, 'ajax_add_artwork'));
	}

	function ajax_response($data) {
		header('Content Type: application/json');
		echo json_encode($data);
		exit;
	}

	function ajax_add_artwork() {

		$post_id = false;

		if(!$this->can_edit($_REQUEST['artwork_id']))
			$this->ajax_response(array('error_msg' => __('You are not allowed to do that', 'arteforadomuseu')));

		if(!$post_id) {
			if(isset($_REQUEST['post_id']) && !$_REQUEST['post_id'])
				$this->ajax_response(array('error_msg' => __('You need to select an artist', 'arteforadomuseu')));
			else
				$post_id = $_REQUEST['post_id'];
		}

		$artwork_id = $_REQUEST['artwork_id'];

		if($this->has_artwork($post_id, $artwork_id))
			$this->ajax_response(array('error_msg' => __('This artwork is already part of the selected artist', 'arteforadomuseu')));

		$this->add_artwork($post_id, $artwork_id);
		$this->ajax_response(array('success_msg' => __('The selected artwork has been added to this artist!', 'arteforadomuseu') . ' <a href="#" class="close">' . __('Click here to close', 'arteforadomuseu') . '</a>'));
	}

	function ajax_delete() {
		if(!$this->can_edit($_REQUEST['post_id']))
			$this->ajax_response(array('error_msg' => __('You are not allowed to do that', 'arteforadomuseu')));

		wp_delete_post($_REQUEST['post_id']);
		$this->ajax_response(array('success_msg' => __('Artist has been deleted', 'arteforadomuseu')));
	}

	function ajax_remove_artwork() {
		if(!$this->can_edit($_REQUEST['post_id']))
			$this->ajax_response(array('error_msg' => __('You are not allowed to do that', 'arteforadomuseu')));

		$this->remove_artwork($_REQUEST['post_id'], $_REQUEST['artwork_id']);
		$this->ajax_response(array('success_msg' => __('Artwork has been removed from this artist', 'arteforadomuseu')));
	}

	/*
	 * Functions
	 */

	function get_archive_link() {
		return get_post_type_archive_link($this->post_type);
	}

	function add_artwork($post_id, $artwork_id = false) {

		global $post;
		$artwork_id = $artwork_id ? $artwork_id : $post->ID;

		if(!$artwork_id || !$post_id)
			return false;

		if(!$this->can_edit($post_id))
			return false;

		if($this->has_artwork($post_id, $artwork_id))
			return false;

		add_post_meta($post_id, '_artworks', $artwork_id);

		$this->update_artist($post_id);
	}

	function remove_artwork($post_id, $artwork_id) {

		if(!$artwork_id || !$post_id)
			return false;

		if(!$this->can_edit($post_id))
			return false;

		delete_post_meta($post_id, '_artworks', $artwork_id);

		$this->update_artist($post_id);
	}

	function update_artist($post_id) {

		$artworks = get_post_meta($post_id, '_artworks');

		$city_names = null;

		if($artworks) {

			$city_names = array();

			foreach($artworks as $artwork_id) {
				if(!get_post($artwork_id)) {
					// artwork not found (deleted)
					delete_post_meta($post_id, '_artworks', $artwork_id);
				} else {
					// update city
					$city = array_shift(get_the_terms($artwork_id, 'city'));
					$city_names[] = $city->name;
				}
			}

		}

		wp_set_object_terms($post_id, $city_names, 'city');

	}

	function has_artist($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		if($this->get_the_artist($post_id)) return true;

		return false;
	}

	function get_the_artist($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$artists = get_posts(array(
			'post_type' => $this->post_type,
			'meta_query' => array(
				array(
					'key' => '_artworks',
					'value' => $post_id
				)
			),
			'posts_per_page' => -1
		));

		return $artists;
	}

	function the_artist($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$artists = $this->get_the_artist($post_id);

		if(!$artists)
			return false;

		$list = array();
		foreach($artists as $artist) {
			$list[] = '<a href="' . get_permalink($artist->ID) . '">' . get_the_title($artist->ID) . '</a>';
		}

		echo implode(', ', $list);
	}

	function get_query($post_id = false, $query = false) {
		if(!$query)
			$query = array();

		if(!$post_id) {
			global $post;
			$post_id = $post->ID;
		}

		$query['post_type'] = 'post';
		$query['post__in'] = $this->get_artworks_id($post_id);

		$query['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;

		return $query;
	}

	function get_popular($amount = 5) {
		$query = array(
			'post_type' => $this->post_type,
			'posts_per_page' => $amount,
		);
		return get_posts(afdm_get_popular_query($query));
	}

	function get_image_mosaic($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$artworks = get_post_meta($post_id, '_artworks');
		$image_ids = array();
		if($artworks) {
			$i = 0;
			foreach($artworks as $artwork) {
				if($i >= 3)
					continue;
				if(has_post_thumbnail($artwork)) {
					$image_ids[$i] = get_post_thumbnail_id($artwork);
				} else {
					$images = get_posts(array('post_type' => 'attachment', 'post_status' => 'any', 'post_parent' => $artwork));
					if($images) {
						$image_ids[$i] = array_shift($images)->ID;
					}
				}
				$i++;
			}
			$mosaic = '<div class="artwork-mosaic images-' . count($image_ids) . ' clearfix">';
			$i = 1;
			foreach($image_ids as $image_id) {
				$image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
				$mosaic .= '<div class="image-' . $i . '-container image-container">';
				$mosaic .= '<a href="' . get_permalink($post_id) . '"><img src="' . $image_src[0] . '" class="image-' . $i . '" /></a>';
				$mosaic .= '</div>';
				$i++;
			}
			$mosaic .= '</div>';
			return $mosaic;
		}
		return false;
	}

	function get_user_link($user_id = false) {
		$user_id = $user_id ? $user_id : wp_get_current_user()->ID;
		return $this->get_archive_link() . '?author=' . $user_id;
	}

	function get_from_user($user_id = false) {
		$user_id = $user_id ? $user_id : wp_get_current_user()->ID;
		return get_posts(array('post_type' => $this->post_type, 'author' => $user_id, 'posts_per_page' => -1, 'not_geo_query' => true));
	}

	function get_artworks_id($post_id) {
		$ids = get_post_meta($post_id, '_artworks');

		if(!$ids)
			$ids = array(0);

		return $ids;
	}

	function get_artwork_count($post_id) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		return count(get_post_meta($post_id, '_artworks'));
	}

	function has_artwork($post_id, $artwork_id) {
		$ids = $this->get_artworks_id($post_id);
		if(in_array($artwork_id, $ids)) {
			return true;
		} else {
			return false;
		}
	}

	function get_age($post_id) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		$birth = get_post_meta($post_id, 'birth_date', true);
		$death = get_post_meta($post_id, 'death_date', true);

		if($birth) {

			if(!$death) {
				$death = date('d/m/Y');
			}

			$birth_date = DateTime::createFromFormat('d/m/Y', $birth);

			$death_date = DateTime::createFromFormat('d/m/Y', $death);

			$diff = $death_date->diff($birth_date);

			return $diff->y;

		}

		return false;
	}

	function is_dead($post_id) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		if(get_post_meta($post_id, 'death_date'))
			return true;

		return false;
	}

	/*
	 * Capability checks
	 */

	function can_edit_any($user_id = false) {
		$user_id = $user_id ? $user_id : wp_get_current_user()->ID;
		if(user_can($user_id, 'edit_others_posts'))
			return true;
		return false;
	}

	function can_edit($post_id = false, $user_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$user_id = $user_id ? $user_id : wp_get_current_user()->ID;
		if(user_can($user_id, 'edit_post', $post_id))
			return true;
		else if(user_can($user_id, 'edit_posts') && !$this->has_artist($post_id))
			return true;
		return false;
	}

	function can_delete($post_id = false, $user_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		$user_id = $user_id ? $user_id : wp_get_current_user()->ID;
		if(user_can($user_id, 'delete_post', $post_id))
			return true;
		return false;
	}

	/*
	 * Templates
	 */

	function setup_templates() {
		add_action('template_redirect', array($this, 'templates'));
	}

	function templates() {
		// archive
		if(is_singular($this->post_type)) {
			include($this->directory . '/templates/single.php');
			exit();
		}
		if(is_post_type_archive($this->post_type)) {
			include($this->directory . '/templates/archive.php');
			exit();
		}
	}


	/*
	 * UI
	 */

	function hook_ui_elements() {
		if(is_user_logged_in()) {
			add_action('wp_footer', array($this, 'add_artwork_box'));
		}
		add_action('afdm_loop_artwork_actions', array($this, 'add_artwork_button'));
		add_action('afdm_loop_artwork_actions', array($this, 'hook_remove_artwork_button'));
		wp_enqueue_script('artists', $this->directory_uri . '/js/artists.js', array('jquery', 'afdm-lightbox', 'jquery-autosize', 'jquery-jeditable'), '0.2');
		wp_localize_script('artists', 'artists', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'confirm_delete' => __('Are you sure?', 'arteforadomuseu'),
			'sending_msg' => __('Sending data...', 'arteforadomuseu')
		));
	}

	function add_artwork_box() {
		$artists = get_posts(array(
			'post_type' => $this->post_type,
			'post_status' => array('publish', 'private', 'pending', 'draft', 'future'),
			'posts_per_page' => -1,
			'not_geo_query' => 1,
			'orderby' => 'title',
			'order' => 'ASC'
		));
		?>
		<div id="add_artwork_to_artist">
			<h2 class="lightbox_title"><span class="lsf">&#xE041;</span> <?php printf(__('Add &ldquo;%s&rdquo; to:', 'arteforadomuseu'), '<span class="title"></span>'); ?></h2>
			<div class="lightbox_content">
				<?php if($artists) : ?>
					<form id="add_to_existing_artist" class="clearfix">
						<input type="hidden" class="artwork_id" name="artwork_id" />
						<div class="form-inputs row">
							<h3><?php _e('Artists', 'arteforadomuseu'); ?></h3>
							<select class="artists" name="post_id">
								<?php foreach($artists as $artist) : ?>
									<option value="<?php echo $artist->ID; ?>"><?php echo $artist->post_title; ?></option>
								<?php endforeach; ?>
							</select>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('select.artists').chosen();
								});
							</script>
						</div>
						<div class="form-actions">
							<input type="submit" value="<?php _e('Add artwork', 'arteforadomuseu'); ?>" />
							<a class="close button secondary" href="#"><?php _e('Cancel', 'arteforadomuseu'); ?></a>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	function add_artwork_button($artwork_id = false) {
		global $post;
		$artwork_id = $artwork_id ? $artwork_id : $post->ID;
		if(!$this->can_edit($artwork_id))
			return;
		?>
		<a class="add_artwork_to_artist" data-artwork="<?php echo $artwork_id; ?>" data-artwork-title="<?php echo get_the_title($artwork_id); ?>" href="#"><span class="lsf">&#xE041;</span> <?php _e('Register artist', 'arteforadomuseu'); ?></a>
		<?php
	}

	function hook_remove_artwork_button() {
		if($this->is_singular) {
			global $post;
			$this->remove_artwork_button($this->artist->ID, $post->ID);
		}
	}

	function remove_artwork_button($post_id, $artwork_id = false) {
		global $post;
		$artwork_id = $artwork_id ? $artwork_id : $post->ID;
		if(!$this->can_edit($post_id))
			return false;
		?>
		<form id="remove_artwork_from_artist">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
			<input type="hidden" name="artwork_id" value="<?php echo $artwork_id; ?>" />
			<button title="<?php _e('Remove this artwork from the artist', 'arteforadomuseu'); ?>"><span class="lsf">&#xE10f;</span> <?php _e('Remove', 'arteforadomuseu'); ?></button>
		</form>
		<?php
	}

	function visit_edit_button($post_id = false) {
		$text = __('Visit', 'arteforadomuseu');
		if($this->can_edit($post_id))
			$text = __('Visit/edit', 'arteforadomuseu');
		?>
		<a class="button" href="<?php the_permalink(); ?>" title="<?php echo $post->post_title; ?>"><?php echo $text; ?></a>
		<?php
	}

	function delete_button($post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;
		if(!$this->can_delete($post_id))
			return false;
		?>
		<form id="delete_artist">
			<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
			<button class="remove" title="<?php _e('Permanently delete this artist', 'arteforadomuseu'); ?>"><span class="lsf">&#xE10f;</span> <?php _e('Delete', 'arteforadomuseu'); ?></button>
		</form>
		<?php
	}

	/*
	 * Jeditable
	 */

	function setup_jeditable() {

		if(!is_admin() && !is_feed() && is_user_logged_in()) {
			add_action('wp_enqueue_scripts', array($this, 'jeditable_scripts'));
			add_filter('the_title', array($this, 'jeditable_post_title'), 10, 2);
			add_filter('the_content', array($this, 'jeditable_post_content'), 100);
			//add_filter('the_excerpt', array($this, 'jeditable_post_excerpt'), 100); nope!
		}

		add_action('wp_ajax_nopriv_jeditable_artirts', array($this, 'jeditable_ajax'));
		add_action('wp_ajax_jeditable_artists', array($this, 'jeditable_ajax'));
	}

	function jeditable_scripts() {

		wp_enqueue_style('jquery-wysiwyg');
		wp_enqueue_script('jquery-jeditable');
		wp_enqueue_script('jquery-jeditable-wysiwyg');
		wp_enqueue_script('jquery-autosize');

		wp_localize_script('artists', 'jeditable', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'submit' => 'ok',
			'cancel' => 'close',
			'tooltip' => __('Double-click to edit...', 'arteforadomuseu'),
			'saving' => __('Saving...', 'arteforadomuseu'),
			'css' => get_stylesheet_directory_uri() . '/css/main.css' /* todo */
		));
	}

	function jeditable($content, $type = 'text', $post_id = false) {
		global $post;
		$post_id = $post_id ? $post_id : $post->ID;

		if($this->can_edit($post_id))
			return 'data-editable="1" data-content="' . $content . '" data-postid="' . $post_id . '" data-type="' . $type . '"';
	}

	function jeditable_post_title($title, $post_id) {
		if(get_post_type($post_id) == $this->post_type && $this->can_edit($post_id) && $this->is_singular) {
			return '<span class="jeditable-container"><span class="jeditable" ' . $this->jeditable('post_title', 'textarea', $post_id) . '>' . $title . '</span><span class="tip"><span class="lsf">&#xE041;</span> ' . __('Double-click to edit', 'arteforadomuseu') . '</span>';
		}
		return $title;
	}

	function jeditable_post_content($content) {
		global $post;
		$post_id = $post->ID;
		if(get_post_type($post_id) == $this->post_type && $this->can_edit($post_id) && $this->is_singular) {

			if(!trim(str_replace(array('\n', '\r'), '', strip_tags($content))))
				$content = '';

			return '<div class="jeditable-container"><div class="jeditable" ' . $this->jeditable('post_content', 'wysiwyg', $post_id) . '>' . $content . '</div><span class="tip"><span class="lsf">&#xE041;</span> ' . __('Double-click to edit', 'arteforadomuseu') . '</div>';
		}

		return $content;
	}

	function jeditable_post_excerpt($excerpt) {
		global $post;
		$post_id = $post->ID;
		if(get_post_type($post_id) == $this->post_type && $this->can_edit($post_id) && $this->is_singular) {

			if(!trim(str_replace(array('\n', '\r'), '', strip_tags($excerpt))))
				$excerpt = '';

			return '<div class="jeditable-container"><div class="jeditable" ' . $this->jeditable('post_excerpt', 'textarea', $post_id) . '>' . $excerpt . '</div><span class="tip"><span class="lsf">&#xE041;</span> ' . __('Double-click to edit', 'arteforadomuseu') . '</div>';
		}

		return $excerpt;
	}

	function jeditable_ajax() {
		if(!isset($_REQUEST['post_id'])) {
			print __('Missing post id', 'arteforadomuseu');
			exit();
		}

		if(!$this->can_edit($_REQUEST['post_id'])) {
			print __('You are not allowed to do that', 'arteforadomuseu');
			exit();
		}

		if(isset($_REQUEST['post_title'])) {
			wp_update_post(array('ID' => $_REQUEST['post_id'], 'post_title' => $_REQUEST['post_title']));
			print $_REQUEST['post_title'];
			exit();
		}

		if(isset($_REQUEST['post_content'])) {
			wp_update_post(array('ID' => $_REQUEST['post_id'], 'post_content' => $_REQUEST['post_content']));
			print $_REQUEST['post_content'];
			exit();
		}

		print __('Something went wrong', 'arteforadomuseu');
		exit();
	}
}

$artists = new ArteForaDoMuseu_Artists;

function afdm_artists_get_archive_link() {
	global $artists;
	return $artists->get_archive_link();
}

function afdm_artists_add_artwork($post_id, $artwork_id = false) {
	global $artists;
	return $artists->add_artwork($artwork_id, $post_id);
}

function afdm_artists_artwork_button($artwork_id = false) {
	global $artists;
	return $artists->add_artwork_button($artwork_id);
}

function afdm_get_user_artists_link($user_id = false) {
	global $artists;
	return $artists->get_user_link($user_id);
}

function afdm_get_user_artists($user_id = false) {
	global $artists;
	return $artists->get_from_user($user_id);
}

function afdm_get_artist_query($post_id = false) {
	global $artists;
	return $artists->get_query($post_id);
}

function afdm_get_artist_artwork_count($post_id = false) {
	global $artists;
	return $artists->get_artwork_count($post_id);
}

function afdm_get_artist_mosaic($post_id = false) {
	global $artists;
	return $artists->get_image_mosaic($post_id);
}

function afdm_get_artist_delete_button($post_id = false) {
	global $artists;
	return $artists->delete_button($post_id);
}

function afdm_get_artist_visit_edit_button($post_id = false) {
	global $artists;
	return $artists->visit_edit_button($post_id);
}

function afdm_artists_can_edit($post_id = false, $user_id = false) {
	global $artists;
	return $artists->can_edit($post_id, $user_id);
}

function afdm_artists_get_popular($amount = 5) {
	global $artists;
	return $artists->get_popular($amount);
}

function afdm_get_artist_views($post_id = false) {
	global $artists;
	return $artists->get_views($post_id);
}

function afdm_has_artist($post_id = false) {
	global $artists;
	return $artists->has_artist($post_id);
}

function afdm_the_artist($post_id = false) {
	global $artists;
	return $artists->the_artist($post_id);
}

function afdm_get_artist_age($post_id = false) {
	global $artists;
	return $artists->get_age($post_id);
}

function afdm_is_artist_dead($post_id = false) {
	global $artists;
	return $artists->is_dead($post_id);
}
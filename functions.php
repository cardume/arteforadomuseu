<?php

require_once(STYLESHEETPATH . '/inc/lightbox/lightbox.php');
require_once(STYLESHEETPATH . '/inc/artguides/artguides.php');
require_once(STYLESHEETPATH . '/inc/artists/artists.php');
require_once(STYLESHEETPATH . '/inc/artworks/artworks.php');
require_once(STYLESHEETPATH . '/inc/geolocator/geolocator.php');
require_once(STYLESHEETPATH . '/inc/views.php');

include_once(STYLESHEETPATH . '/inc/instagram/instagram.php');

include_once(STYLESHEETPATH . '/inc/mobile-map-list/mobile-map-list.php');

include_once(STYLESHEETPATH . '/inc/slider.php'); // Featured slider

/*
 * Advanced Custom Fields
 */

function afdm_acf_path() {
	return get_stylesheet_directory_uri() . '/inc/acf/';
}
add_filter('acf/helpers/get_dir', 'afdm_acf_path');

define('ACF_LITE', false);
require_once(STYLESHEETPATH . '/inc/acf/acf.php');
include_once(STYLESHEETPATH . '/inc/acf/add-ons/acf-qtranslate/acf-qtranslate.php');

function afdm_setup() {
	load_child_theme_textdomain('arteforadomuseu', get_stylesheet_directory() . '/languages');
	add_theme_support('post-thumbnails');
	add_image_size('page-featured', 680, 270, true);
	add_image_size('featured-squared', 400, 400, true);
}
add_action('after_setup_theme', 'afdm_setup');

function afdm_scripts() {

	wp_deregister_script('jeo-site');

	wp_enqueue_style('afdm-main', get_stylesheet_directory_uri() . '/css/main.css', array(), '1.7');
	wp_enqueue_script('responsive-nav', get_stylesheet_directory_uri(). '/lib/responsive-nav.min.js', '', '1.0');
	wp_enqueue_script('afdm', get_stylesheet_directory_uri(). '/js/arteforadomuseu.js', array('responsive-nav', 'shadowbox'), '0.1.10');
}

function afdm_after_markers_scripts() {

	if(!is_single()) {
		wp_enqueue_script('afdm-filter', get_stylesheet_directory_uri() . '/js/arteforadomuseu.filterCategories.js', array('jquery', 'jeo.markers'), '0.0.5');
		wp_localize_script('afdm-filter', 'afdmFilter', array(
			'categories' => array(
				array(
					'slug' => 'colaborativo',
					'title' => 'Apenas obras colaborativas'
				)
			)
		));
	}
}

function afdm_register_lib() {
	wp_register_style('jquery-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.wysiwyg.css');
	wp_register_script('jquery-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.wysiwyg.js', array('jquery'));
	wp_register_script('jquery-jeditable', get_stylesheet_directory_uri() . '/lib/jquery.jeditable.mini.js', array('jquery'));
	wp_register_script('jquery-jeditable-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.jeditable.wysiwyg.js', array('jquery', 'jquery-jeditable', 'jquery-wysiwyg'));
	wp_register_script('jquery-autosize', get_stylesheet_directory_uri() . '/lib/jquery.autosize-min.js', array('jquery'), '1.16.7');
	wp_register_script('jquery-ui-datepicker-pt-BR', get_stylesheet_directory_uri() . '/lib/jquery.ui.datepicker.pt-BR.js', array('jquery-ui-datepicker'));
	wp_register_script('jquery-chosen', get_stylesheet_directory_uri() . '/lib/jquery.chosen.min.js', array('jquery'), '0.9.14');
	wp_register_style('jquery-chosen', get_stylesheet_directory_uri() . '/lib/chosen.css');
	wp_deregister_script('jquery-form');
	wp_register_script('jquery-form', get_stylesheet_directory_uri() . '/lib/jquery.form.js', array('jquery'), '3.34.0-test1');

	wp_register_script('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.js', array('jquery'), '3.0.3');
	wp_enqueue_style('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.css');

	wp_register_style('jquery-tag-it', get_stylesheet_directory_uri() . '/lib/jquery.tagit.css');
	wp_register_script('jquery-tag-it', get_stylesheet_directory_uri() . '/lib/tag-it.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position', 'jquery-ui-autocomplete'), '2.0');
}

add_action('wp_enqueue_scripts', 'afdm_register_lib', 15);
add_action('jeo_enqueue_scripts', 'afdm_scripts', 15);
add_action('jeo_markers_enqueue_scripts', 'afdm_scripts', 10);
add_action('admin_footer', 'afdm_register_lib', 10);

function afdm_marker_extent() {
	return true;
}
add_action('jeo_use_marker_extent', 'afdm_marker_extent');

add_filter('show_admin_bar', '__return_false');

function afdm_prevent_admin_access() {
	if (!current_user_can('edit_others_posts') && !defined('DOING_AJAX')) {
		wp_redirect(home_url());
		exit();
	}
}
add_action('admin_init', 'afdm_prevent_admin_access', 0);

function afdm_get_user_menu() {
	?>
	<div class="user-meta hide-if-mobile">
		<?php
		if(!is_user_logged_in()) :
			?>
			<span class="dropdown-title login clearfix"><span class="lsf icon">&#xE087;</span> <span class="icon-title"><?php _e('Login', 'arteforadomuseu'); ?></span></span>
			<div class="dropdown-content">
				<div class="login-content">
					<p><?php _e('Login with: ', 'arteforadomuseu'); ?></p>
					<?php do_action('oa_social_login'); ?>
				</div>
			</div>
			<?php
		else :
			?>
			<span class="dropdown-title login clearfix"><span class="lsf icon">&#xE137;</span> <span class="user-name"><?php echo wp_get_current_user()->display_name; ?></span></span>
			<div class="dropdown-content">
				<div class="logged-in">
					<p><?php _e('Hello', 'arteforadomuseu'); ?>, <?php echo wp_get_current_user()->display_name; ?>. <a class="logout" href="<?php echo wp_logout_url(home_url()); ?>" title="<?php _e('Logout', 'arteforadomuseu'); ?>"><?php _e('Logout', 'arteforadomuseu'); ?> <span class="lsf">&#xE088;</span></a></p>
					<ul class="user-actions">
						<?php do_action('afdm_logged_in_user_menu_items'); ?>
						<?php if(current_user_can('edit_others_posts')) : ?>
							<li><a href="<?php echo get_admin_url(); ?>"><?php _e('Dashboard', 'arteforadomuseu'); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<?php
		endif;
	?>
	</div>
	<?php
}

function afdm_city_not_found_message() {
	global $wp_query;
	if(get_query_var('city_not_found')) {
		?>
		<div class="content-message">
			<p>
				<?php _e('We couldn\'t find anything for your city.', 'arteforadomuseu'); ?><br />
				<?php _e('Showing all cities results', 'arteforadomuseu'); ?>
			</p>
			<?php if(is_user_logged_in()) { ?>
				<p><a href="#" class="button add_artwork"><?php _e('Click here to add an artwork', 'arteforadomuseu'); ?></a></p>
			<?php } else { ?>
				<p><a href="#"><?php _e('Login to submit an artwork!', 'arteforadomuseu'); ?></a></p>
			<?php } ?>
		</div>
		<?php
	}
}

add_action('afdm_before_content', 'afdm_city_not_found_message');

function afdm_flush_rewrite() {
	global $pagenow;
	if(is_admin() && $_REQUEST['activated'] && $pagenow == 'themes.php') {
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}
}
add_action('init', 'afdm_flush_rewrite');
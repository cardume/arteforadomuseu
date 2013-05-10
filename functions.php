<?php


require_once(STYLESHEETPATH . '/inc/lightbox/lightbox.php');
require_once(STYLESHEETPATH . '/inc/artguides/artguides.php');
require_once(STYLESHEETPATH . '/inc/artworks/artworks.php');
require_once(STYLESHEETPATH . '/inc/featured-content/featured.php');
require_once(STYLESHEETPATH . '/inc/geolocator/geolocator.php');

function afdm_setup() {
	add_theme_support('post-thumbnails');
	add_image_size('page-featured', 680, 270, true);
}
add_action('after_setup_theme', 'afdm_setup');

function afdm_scripts() {
	wp_enqueue_style('afdm-main', get_stylesheet_directory_uri() . '/css/main.css', array(), '1.0');
	wp_enqueue_script('responsive-nav', get_stylesheet_directory_uri(). '/lib/responsive-nav.min.js', '', '1.0');
	wp_enqueue_script('afdm', get_stylesheet_directory_uri(). '/js/arteforadomuseu.js', array('responsive-nav'), '0.1.1');
}

function afdm_register_lib() {
	wp_register_style('jquery-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.wysiwyg.css');
	wp_register_script('jquery-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.wysiwyg.js', array('jquery'));
	wp_register_script('jquery-jeditable', get_stylesheet_directory_uri() . '/lib/jquery.jeditable.mini.js', array('jquery'));
	wp_register_script('jquery-jeditable-wysiwyg', get_stylesheet_directory_uri() . '/lib/jquery.jeditable.wysiwyg.js', array('jquery', 'jquery-jeditable', 'jquery-wysiwyg'));
	wp_register_script('jquery-autosize', get_stylesheet_directory_uri() . '/lib/jquery.autosize-min.js', array('jquery'), '1.16.7');
	wp_register_script('jquery-ui-datepicker-pt-BR', get_stylesheet_directory_uri() . '/lib/jquery.ui.datepicker.pt-BR.js', array('jquery-ui-datepicker'));

	wp_register_style('jquery-tag-it', get_stylesheet_directory_uri() . '/lib/jquery.tagit.css');
	wp_register_script('jquery-tag-it', get_stylesheet_directory_uri() . '/lib/tag-it.min.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position', 'jquery-ui-autocomplete'), '2.0');
}

add_action('wp_enqueue_scripts', 'afdm_scripts', 5);
add_action('wp_enqueue_scripts', 'afdm_register_lib', 5);
add_action('admin_footer', 'afdm_register_lib', 5);

// Set google geocode service
function afdm_geocode_service() {
	return 'gmaps';
}
add_action('mappress_geocode_service', 'afdm_geocode_service');

function afdm_gmaps_api_key() {
	return 'AIzaSyABrs0DJWrYC_Imx7VbGw1Hsfr6KEZBdpg';
}
add_action('mappress_gmaps_api_key', 'afdm_gmaps_api_key');

function afdm_marker_extent() {
	return true;
}
add_action('mappress_use_marker_extent', 'afdm_marker_extent');

add_filter('show_admin_bar', '__return_false');

function afdm_prevent_admin_access() {
	if (!current_user_can('edit_others_posts') && !defined('DOING_AJAX')) {
		wp_redirect(home_url());
		exit();
	}
}
add_action('admin_init', 'afdm_prevent_admin_access', 0);

function afdm_use_map_query() {
	return false;
}
add_action('mappress_use_map_query', 'afdm_use_map_query');

function afdm_use_hash() {
	return false;
}
add_action('mappress_use_hash', 'afdm_use_hash');

function afdm_get_user_menu() {
	?>
	<div class="user-meta hide-if-mobile">
		<?php
		if(!is_user_logged_in()) :
			?>
			<span class="dropdown-title login"><span class="lsf icon">login</span> <?php _e('Login', 'arteforadomuseu'); ?> <span class="lsf arrow">down</span></span>
			<div class="dropdown-content">
				<div class="login-content">
					<p><?php _e('Login with: ', 'arteforadomuseu'); ?></p>
					<?php do_action('oa_social_login'); ?>
				</div>
			</div>
			<?php
		else :
			?>
			<span class="dropdown-title login"><span class="lsf icon">user</span> <span class="user-name"><?php echo wp_get_current_user()->display_name; ?></span> <span class="lsf arrow">down</span></span>
			<div class="dropdown-content">
				<div class="logged-in">
					<p><?php _e('Hello', 'arteforadomuseu'); ?>, <?php echo wp_get_current_user()->display_name; ?>. <a class="logout" href="<?php echo wp_logout_url(home_url()); ?>" title="<?php _e('Logout', 'arteforadomuseu'); ?>"><?php _e('Logout', 'arteforadomuseu'); ?> <span class="lsf">logout</span></a></p>
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
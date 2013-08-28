<?php

/*
 * Arte Fora do Museu
 * Geolocator
 */

class ArteForaDoMuseu_Geolocator {

	var $user_city = null;

	var $city_slug = 'cidades';

	var $cookie_name = 'afdm_user_city';

	function __construct() {

		add_action('jeo_init', array($this, 'setup'));

	}

	function setup() {

		// find on map

		// add_action('wp_enqueue_scripts', array($this, 'scripts'));

		// find on markers

		$this->register_taxonomy();

		$this->queries();

		$this->setup_cookies();

	}

	/*
	 * Script to find on map
	 * NOT USED

	function scripts() {
		if(jeo_get_geocode_service() == 'gmaps' && jeo_get_gmaps_api_key()) {
			wp_enqueue_script('afdm-geolocator', get_stylesheet_directory_uri() . '/inc/geolocator/js/geolocator.js', array('jquery', 'google-maps-api', 'jeo'), '0.0.4');
			wp_localize_script('afdm-geolocator', 'geolocator_confs', array(
				'enable' => is_front_page(),
				'latlng' => $this->get_user_latlng()
			));
		}
	}
	 */


	/*
	 * City taxonomy connected to jeo geocode box
	 */

	function register_taxonomy() {
		$this->taxonomy_city();
		add_action('jeo_geocode_box_save', array($this, 'populate_city'));
	}

	function geo_post_types() {
		return apply_filters('afdm_geo_post_types', jeo_get_mapped_post_types());
	}

	function taxonomy_city() {

		$labels = array(
			'name' => __('Cities', 'arteforadomuseu'),
			'singular_name' => __('City', 'arteforadomuseu'),
			'search_items' => __('Search cities', 'arteforadomuseu'),
			'popular_items' => __('Popular cities', 'arteforadomuseu'),
			'all_items' => __('All cities', 'arteforadomuseu'),
			'parent_item' => __('Parent city', 'arteforadomuseu'),
			'parent_item_colon' => __('Parent city:', 'arteforadomuseu'),
			'edit_item' => __('Edit city', 'arteforadomuseu'),
			'update_item' => __('Update city', 'arteforadomuseu'),
			'add_new_item' => __('Add new city', 'arteforadomuseu'),
			'new_item_name' => __('New city name', 'arteforadomuseu'),
			'separate_items_with_commas' => __('Separate cities with commas', 'arteforadomuseu'),
			'add_or_remove_items' => __('Add or remove cities', 'arteforadomuseu'),
			'choose_from_most_used' => __('Choose from most used cities', 'arteforadomuseu'),
			'menu_name' => __('Cities', 'arteforadomuseu')
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => false,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array('slug' => $this->city_slug, 'with_front' => false),
			'query_var' => true,
			'show_admin_column' => true
		);

		register_taxonomy('city', $this->geo_post_types(), $args);

		do_action('afdm_city_taxonomy_registered');

	}

	// save jeo city data to taxonomy

	function populate_city($post_id) {
		if(isset($_POST['geocode_city'])) {
			wp_set_object_terms($post_id, $_POST['geocode_city'], 'city');
		}
	}

	/*
	 * Setup queries
	 * If no city was found, set a query var and return all cities results
	 */

	function queries() {

		global $wp;
		$wp->add_query_var('city_not_found');
		$wp->add_query_var('not_geo_query');

		add_action('pre_get_posts', array($this, 'geo_wp_query'));

	}

	function geo_wp_query($query) {

		if($this->is_geo_query($query)) {

			$city = $this->get_user_city();

			if($city) {

				$city_term = get_term_by('name', $city, 'city');

				if(!$city_term) {
					$query->set('city_not_found', 1);
					return $query;
				}

				$query->set('city', $city_term->slug);

				remove_action('pre_get_posts', array($this, 'geo_wp_query'));

				$have_posts = get_posts($query->query_vars);

				add_action('pre_get_posts', array($this, 'geo_wp_query'));

				if(!$have_posts) {
					$query->set('city', null);
					$query->set('city_not_found', 1);
				}

			}

		}

		return $query;

	}

	/*
	 * Verify if the query is returning city results
	 */
	function is_from_user_city() {
		global $wp_query;
		if(get_query_var('city_not_found'))
			return false;
		return true;
	}

	/*
	 * Verify which query to inject city term
	 */
	function is_geo_query($query) {
		return apply_filters('afdm_is_geo_query', (!is_admin() && !$query->get('not_geo_query')), $query);
	}

	/*
	 * Cookie by city term
	 */

	function city_selector() {
		$user_city = $this->get_user_city();
		$cities = get_terms('city');
		if($cities) {
			?>
			<div class="city-selector">
				<?php if($user_city) : ?>
					<h2 class="city-title"><span class="lsf">&#xE03e;</span> <?php echo $user_city; ?></h2>
				<?php else : ?>
					<span class="city-title"><span class="lsf">&#xE03e;</span> <?php _e('Select a city', 'arteforadomuseu'); ?></span>
				<?php endif; ?>
				<ul class="city-list">
					<?php if($user_city) : ?>
						<li class="tip"><?php _e('Choose another city:', 'arteforadomuseu'); ?></li>
						<li>
							<a href="?select_city=all"><?php _e('All cities', 'arteforadomuseu'); ?></a>
						</li>
					<?php endif; ?>
					<?php foreach($cities as $city) : ?>
						<?php if($user_city == $city->name) continue; ?>
						<li>
							<a href="<?php echo home_url('?select_city=' . $city->term_id); ?>"><?php echo $city->name; ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php
		}
	}

	function setup_cookies() {
		$this->verify_cookie();
	}

	function verify_cookie() {
		if(isset($_GET['select_city'])) {
			if($_GET['select_city'] == 'all') {
				$this->store_cookie('all');
				$this->user_city = false;
			} else {
				$city_term = get_term($_GET['select_city'], 'city');
				if($city_term) {
					$this->store_cookie($city_term->term_id);
					$this->user_city = $city_term->name;
				}
			}
		}
	}

	function store_cookie($city_id) {
		setcookie(
			$this->cookie_name,
			$city_id,
			time() + 3600,
			parse_url(get_option('siteurl'), PHP_URL_PATH),
			parse_url(get_option('siteurl'), PHP_URL_HOST)
		);
	}

	function get_cookie() {
		$city_id = $_COOKIE[$this->cookie_name];
		if($city_id == 'all') {
			return false;
		} else {
			$city_term = get_term($city_id, 'city');
			return $city_term->name;
		}
	}

	/*
	 * GEOIP
	 */

	function get_user_city() {

		// defined by class
		$user_city = $this->user_city;

		// defined by cookie
		$cookie = $this->get_cookie();

		// defined by geoip, finally
		$geoip = $this->geoip();

		if($user_city !== null)
			$city = $user_city;
		elseif($cookie !== null)
			$city = $cookie;
		elseif($geoip && $geoip['country_code'] == 'BR')
			$city = $geoip['city'];
		else
			$city = false;

		return $city;
	}

	function get_user_latlng() {

		$geoip = $this->geoip();

		if($geoip['latitude'] && $geoip['longitude'])
			return array($geoip['latitude'], $geoip['longitude']);

		return false;
	}

	function geoip() {

		$ip = $this->get_user_ip();

		$geoip = get_transient('geoip_' . $ip);

		if(!$geoip) {
			$ch = curl_init('http://freegeoip.net/json/' . $this->get_user_ip());
			curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json')
			));
			$result = curl_exec($ch);

			$geoip = json_decode($result, true);

			set_transient('geoip_'. $ip, $geoip, 60*60*48);
		}

		return $geoip;

	}

	function get_user_ip() { 
		$ip = false;

		if (getenv("HTTP_CLIENT_IP")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
		else if(getenv("HTTP_X_FORWARDED_FOR")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if(getenv("REMOTE_ADDR")) 
			$ip = getenv("REMOTE_ADDR");

		if($ip == '127.0.0.1')
			$ip = '186.207.146.97';

		return $ip; 
	}

}

$geolocator = new ArteForaDoMuseu_Geolocator();

function afdm_city_selector() {
	global $geolocator;
	return $geolocator->city_selector();
}

function afdm_is_from_user_city() {
	global $geolocator;
	return $geolocator->is_from_user_city();
}

function afdm_get_user_city() {
	global $geolocator;
	return $geolocator->get_user_city();
}
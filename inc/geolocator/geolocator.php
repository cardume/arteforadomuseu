<?php

/*
 * Arte Fora do Museu
 * Geolocator
 * Find user's city using HTML5 and Google API
 */

class ArteForaDoMuseu_Geolocator {

	var $cookie_name = 'afdm_user_city';

	var $user_city = null;

	function __construct() {

		$this->setup_cookies();

		add_action('init', array($this, 'setup'));

	}

	function setup() {

		// find on map

		// add_action('wp_enqueue_scripts', array($this, 'scripts'));

		// find on markers

		$this->queries();

	}

	/*
	 * Script to find on map
	 */

	function scripts() {
		if(mappress_get_geocode_service() == 'gmaps' && mappress_get_gmaps_api_key()) {
			wp_enqueue_script('afdm-geolocator', get_stylesheet_directory_uri() . '/inc/geolocator/js/geolocator.js', array('jquery', 'google-maps-api', 'mappress'), '0.0.4');
			wp_localize_script('afdm-geolocator', 'geolocator_confs', array(
				'enable' => is_front_page(),
				'latlng' => $this->get_user_latlng()
			));
		}
	}

	/*
	 * Change markers to location using GeoIP
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

				remove_action('pre_get_posts', array($this, 'geo_wp_query'));

				$city_term = get_term_by('name', $city, 'city');

				if($city_term)
					$query->set('city', $city_term->slug);

				if(!get_posts($query->query_vars)) {
					$query->set('city', null);
					$query->set('city_not_found', 1);
				}

				add_action('pre_get_posts', array($this, 'geo_wp_query'));

			}

		}

		return $query;

	}

	// check the query if the city was not found
	function is_from_user_city() {
		global $wp_query;
		if(get_query_var('city_not_found'))
			return false;
		return true;
	}

	function is_geo_query($query) {
		return apply_filters('afdm_is_geo_query', (!is_admin() && !$query->get('not_geo_query')), $query);
	}

	/*
	 * Cookie by city term (storing with select_city query)
	 */

	function setup_cookies() {
		add_action('init', array($this, 'verify_cookie'));
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
			$ip = '186.204.200.47';

		return $ip; 
	}

}

$geolocator = new ArteForaDoMuseu_Geolocator();

function afdm_is_from_user_city() {
	global $geolocator;
	return $geolocator->is_from_user_city();
}

function afdm_get_user_city() {
	global $geolocator;
	return $geolocator->get_user_city();
}
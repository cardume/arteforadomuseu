<?php

/*
 * Arte Fora do Museu
 * Instagram map
 */

class AFDM_Instagram {

	function __construct() {

		add_action('init', array($this, 'init'));

	}

	function init() {

		add_action('query_vars', array($this, 'query_vars'));
		add_action('generate_rewrite_rules', array($this, 'generate_rewrite_rules'));
		add_action('template_redirect', array($this, 'template_redirect'));

		$this->setup_data_ajax();

	}

	function query_vars($vars) {

		$vars[] = 'afdm_instagram';

		return $vars; 

	}

	function generate_rewrite_rules($wp_rewrite) {
		$widgets_rule = array(
			'instagram$' => 'index.php?afdm_instagram=1'
		);
		$wp_rewrite->rules = $widgets_rule + $wp_rewrite->rules;
	}

	function template_redirect() {
		if(get_query_var('afdm_instagram')) {
			$this->template();
			exit;
		}
	}

	function template() {

		wp_enqueue_script('instagram-map', get_stylesheet_directory_uri() . '/inc/instagram/js/instagram.js', array('jquery', 'jeo'));
		wp_localize_script('instagram-map', 'instagram_settings', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'iconUrl' => get_stylesheet_directory_uri() . '/inc/instagram/marker.png'
		));

		wp_enqueue_style('instagram-map', get_stylesheet_directory_uri() . '/inc/instagram/css/instagram.css');

		get_header();

		?>
		<div class="instagram-page">
			<div class="instagram-header">
				<div class="container">
					<div class="twelve columns">
						<h2><?php _e('Collaborative Instagram map', 'arteforadomuseu'); ?></h2>
						<p class="description"><?php _e('Use the hashtag #arteforadomuseu to appear on our collaborative map', 'arteforadomuseu'); ?></p>
					</div>
				</div>
			</div>
			<div id="instagram_map"></div>
		</div>
		<?php

		get_footer();

	}

	function setup_data_ajax() {

		add_action('wp_ajax_nopriv_instagram_data', array($this, 'get_data'));
		add_action('wp_ajax_instagram_data', array($this, 'get_data'));

	}

	function get_data() {

		$cache = get_transient('afdm_instagram_data');
		$media = $cache ? $cache : array();

		if(!$media || empty($media)) {

			$url = "https://api.instagram.com/v1/tags/arteforadomuseu/media/recent?client_id=822ac764dbf547a4b3c66ca66bafdb5f&count=50";

			$result = $this->get_instagram($url);
			$media = array_merge($media, $this->get_instagram_images($result));

			while(isset($result['pagination']) && $result['pagination']['next_url']) {

				$result = $this->get_instagram($result['pagination']['next_url']);
				$media = array_merge($media, $this->get_instagram_images($result));

			}

			$media = json_encode($media);

			set_transient('afdm_instagram_data', $media, 60*10);

		}

		header('Content-Type: application/json;charset=UTF-8');
		/*
		$expires = 60 * 10; // 10 minutes of browser cache
		header('Pragma: public');
		header('Cache-Control: maxage=' . $expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
		*/

		echo $media;
		exit;

	}

	function get_instagram($url) {

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = json_decode(curl_exec($curl), true);

		return $result;

	}

	function get_instagram_images($instagram_object) {

		return $instagram_object['data'];

	}


}

$GLOBALS['afdm_instagram'] = new AFDM_Instagram();
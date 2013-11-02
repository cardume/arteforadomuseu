<?php

$featured_query = array(
	'not_geo_query' => 1,
	'post_type' => 'slider',
	'posts_per_page' => -1
);

query_posts($featured_query);

if(have_posts()) :

	?>

	<div class="child-section popular-section">
		<div class="disable-autorun">
			<?php get_template_part('loop', 'carousel'); ?>
		</div>
	</div>

	<?php

else :

	wp_reset_query();

	global $wp_query;

	$city = afdm_get_user_city();

	$popular_query = afdm_get_popular_query();

	$query = array_merge($wp_query->query, $popular_query);

	query_posts($query);

	if(have_posts()) :
		?>

		<div class="child-section popular-section">
			<div class="section-title featured">
				<?php if($city && !get_query_var('city_not_found')) : ?>
					<h2><?php _e('Popular in', 'arteforadomuseu'); ?> <?php echo $city; ?></h2>
				<?php else : ?>
					<h2><?php _e('Popular', 'arteforadomuseu'); ?></h2>
				<?php endif; ?>
			</div>
			<div class="disable-autorun">
				<?php get_template_part('loop', 'carousel'); ?>
			</div>
		</div>

		<?php

	endif;
	wp_reset_query();

endif;
wp_reset_query();
?>
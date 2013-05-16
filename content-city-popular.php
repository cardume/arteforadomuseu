<div class="child-section">

	<?php

	global $wp_query;

	$city = afdm_get_user_city();

	$popular_query = afdm_get_popular_query();

	$query = array_merge($wp_query->query, $popular_query);

	query_posts($query);

	if(have_posts()) :
		?>
		<div class="section-title">
			<?php if($city) : ?>
				<h2><?php _e('Popular in', 'arteforadomuseu'); ?> <?php echo $city; ?></h2>
			<?php else : ?>
				<h2><?php _e('Popular', 'arteforadomuseu'); ?></h2>
			<?php endif; ?>
		</div>

		<?php get_template_part('loop', 'popular'); ?>

	<?php else : ?>

		<div class="section-message">
			<p><?php echo $city; ?> <?php _e('doesn\'t have artworks, yet!', 'arteforadomuseu'); ?></p>
		</div>

	<?php
	endif;
	wp_reset_query();
	?>
</div>
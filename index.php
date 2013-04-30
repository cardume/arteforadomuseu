<?php get_header(); ?>

<?php mappress_featured(); ?>

<section id="content">
	<div class="section-title">
		<h2><?php _e('Featured artists', 'arteforadomuseu'); ?></h2>
	</div>
	<div class="section-title">
		<h2><?php _e('Latest artworks', 'arteforadomuseu'); ?></h2>
	</div>
	<?php get_template_part('loop'); ?>
</section>

<?php get_footer(); ?>
<?php get_header(); ?>

<?php mappress_featured(); ?>

<section id="content">
	<div class="section-title">
		<h2><?php _e('Featured artworks', 'mappress'); ?></h2>
	</div>
	<?php get_template_part('loop'); ?>
</section>

<?php get_footer(); ?>
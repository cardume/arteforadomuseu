<?php get_header(); ?>

<?php jeo_featured(); ?>

<section id="content">

	<?php do_action('afdm_before_content'); ?>

	<?php if(!is_paged()) get_template_part('content', 'popular'); ?>

	<div class="child-section">
		<div class="section-title">
			<h2><?php _e('Latest artworks', 'arteforadomuseu'); ?></h2>
		</div>
		<?php get_template_part('loop'); ?>
	</div>
</section>

<?php get_footer(); ?>
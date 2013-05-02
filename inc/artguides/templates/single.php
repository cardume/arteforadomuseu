<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php mappress_featured(true, true); ?>

	<section id="content">
		<header class="single-post-header clearfix">
			<?php the_post_thumbnail('page-featured'); ?>
			<h1><?php the_title(); ?></h1>
		</header>
		<section class="post-content">
			<?php the_content(); ?>
		</section>
		<?php query_posts(afdm_get_artguide_query()); ?>
			<section id="artworks" class="child-section">
				<div class="section-title">
					<h2><?php _e('Artworks', 'arteforadomuseu'); ?></h2>
				</div>
				<?php get_template_part('loop'); ?>
			</section>
		<?php wp_reset_query(); ?>
	</section>

<?php endif; ?>

<?php get_footer(); ?>
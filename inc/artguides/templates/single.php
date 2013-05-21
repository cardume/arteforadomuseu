<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php mappress_featured(true, true); ?>

	<section id="content">
		<header class="single-post-header clearfix">
			<?php the_post_thumbnail('page-featured'); ?>
			<h1><?php the_title(); ?></h1>
			<div class="header-meta">
				<div class="buttons">
					<?php afdm_get_artguide_delete_button(); ?>
				</div>
				<p><span class="lsf">user</span> <?php _e('by', 'arteforadomuseu'); ?> <a href="<?php echo afdm_get_user_artguides_link(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a></p>
				<p><span class="lsf">time</span> <?php _e('added', 'arteforadomuseu'); ?> <?php echo get_the_date(); ?></p>
				<p><span class="lsf">checkboxempty</span> <?php echo sprintf(_n('1 artwork', '%s artworks', afdm_get_artguide_artwork_count(), 'arteforadomuseu'), afdm_get_artguide_artwork_count()); ?></p>
			</div>
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
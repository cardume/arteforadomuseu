<?php get_header(); ?>
<?php if(have_posts()) : ?>
	<section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="twelve columns">
					<h1><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/artguide-logo.png" /><?php _e('Art guides', 'arteforadomuseu'); ?></h1>
					<?php if(isset($_GET['author']) && get_userdata($_GET['author'])) : ?>
						<h2><?php _e('by', 'arteforadomuseu'); ?> <?php echo get_userdata($_GET['author'])->display_name; ?></h2>
					<?php endif; ?>
					<a class="new_artguide button" href="#"><?php _e('Create an art guide', 'arteforadomuseu'); ?></a>
				</div>
			</div>
		</header>
		<div class="container">
			<?php if(isset($_GET['author']) && get_userdata($_GET['author'])) : ?>
				<section class="art-guide regular-list">
					<?php while(have_posts()) : the_post(); ?>
						<article id="artguide-<?php the_ID(); ?>" class="clearfix">
							<div class="twelve columns">
								<div class="three columns alpha">
									<header class="post-header">
										<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
										<p><span class="lsf">user</span> <?php _e('by', 'arteforadomuseu'); ?> <?php the_author(); ?></p>
										<p><span class="lsf">time</span> <?php _e('added', 'arteforadomuseu'); ?> <?php echo get_the_date(); ?></p>
										<p><span class="lsf">checkboxempty</span> <?php echo sprintf(_n('1 artwork', '%s artworks', afdm_get_artguide_artwork_count(), 'arteforadomuseu'), afdm_get_artguide_artwork_count()); ?></p>
										<div class="buttons">
											<a class="button" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Visit art guide', 'arteforadomuseu'); ?></a>
											<?php afdm_get_artguide_delete_button(); ?>
										</div>
									</header>
								</div>
								<div class="five columns">
									<?php echo afdm_get_artguide_mosaic(); ?>
								</div>
								<div class="four columns omega">
									<section class="post-content">
										<?php the_content(); ?>
									</section>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				</section>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
<?php get_header(); ?>
<?php if(have_posts()) : ?>
	<section id="content" class="single-post">
		<header class="single-post-header">
			<div class="container">
				<div class="twelve columns">
					<h1><a href="<?php echo afdm_artists_get_archive_link(); ?>" title="<?php _e('Artists', 'arteforadomuseu'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/artist-logo.png" /><?php _e('Artists', 'arteforadomuseu'); ?></a></h1>
					<?php if(isset($_GET['s'])) : ?>
						<h2><?php echo __('Search results for:', 'arteforadomuseu') . ' <i>' . $_GET['s'] . '</i>'; ?></h2>
					<?php else : ?>
						<h2><?php // _e('Collaborative guides through the public art', 'arteforadomuseu'); ?></h2>
					<?php endif; ?>
				</div>
			</div>
		</header>
		<div class="container">
			<section class="art-guide">
				<?php if((isset($_GET['author']) && get_userdata($_GET['author'])) || isset($_GET['s'])) : ?>
					<div class="regular-list">
						<?php while(have_posts()) : the_post(); ?>
							<article id="artist-<?php the_ID(); ?>" class="clearfix">
								<div class="twelve columns">
									<div class="three columns alpha">
										<header class="post-header">
											<h3><a href="<?php the_permalink(); ?>" title="<?php echo $post->post_title; ?>"><?php the_title(); ?></a></h3>
											<p><span class="lsf">&#xE02b;</span> <?php echo sprintf(_n('1 artwork', '%s artworks', afdm_get_artist_artwork_count(), 'arteforadomuseu'), afdm_get_artist_artwork_count()); ?></p>
											<div class="buttons clearfix">
												<?php afdm_get_artist_visit_edit_button(); ?>
												<?php afdm_get_artist_delete_button(); ?>
											</div>
										</header>
									</div>
									<div class="five columns">
										<?php echo afdm_get_artist_mosaic(); ?>
									</div>
									<div class="four columns omega">
										<section class="post-content">
											<?php the_content(); ?>
										</section>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
						<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
					</div>
				<?php else : ?>
					<?php if(!is_paged()) : ?>
						<div class="row">
							<div class="seven columns">
								<section class="featured">
									<?php
									$featured = get_posts(array('posts_per_page' => 1, 'post_type' => 'artist', 'jeo_featured' => 1));
									if($featured) : ?>
										<h2><?php _e('Featured', 'arteforadomuseu'); ?></h2>
										<?php
										global $post;
										foreach($featured as $post) :
											setup_postdata($post);
											?>
											<div class="row">
												<article id="artist-<?php echo the_ID(); ?>">
													<header class="post-header">
														<?php if(has_post_thumbnail()) : ?>
															<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
																<?php the_post_thumbnail('page-featured', array('class' => 'scale-with-grid')); ?>
															</a>
														<?php endif; ?>
														<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
													</header>
													<section class="post-content">
														<?php the_excerpt(); ?>
													</section>
												</article>
											</div>
											<?php
											wp_reset_postdata();
										endforeach;
										?>
									<?php endif; ?>
								</section>
							</div>
							<div class="four columns offset-by-one">
								<section class="popular">
									<?php
									$popular = afdm_artists_get_popular(4);
									if($popular) : ?>
										<h2><?php _e('Popular', 'arteforadomuseu'); ?></h2>
										<?php
										foreach($popular as $post) :
											global $post;
											setup_postdata($post);
											?>
											<article id="artist-<?php echo the_ID(); ?>">
												<header class="post-header">
													<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
													<p><span class="lsf">&#xE13a;</span> <?php echo sprintf(_n('1 view', '%s views', afdm_get_views(), 'arteforadomuseu'), afdm_get_views()); ?></p>
												</header>
											</article>
											<?php
											wp_reset_postdata();
										endforeach;
										?>
									<?php endif; ?>
								</section>
							</div>
						</div>
					<?php endif; ?>
					<div class="row">
						<section class="recent">
							<div class="twelve columns">
								<h2><?php _e('Recently published', 'arteforadomuseu'); ?></h2>
							</div>
							<div class="regular-list">
								<?php while(have_posts()) : the_post(); ?>
									<article id="artist-<?php the_ID(); ?>" class="clearfix">
										<div class="twelve columns">
											<div class="three columns alpha">
												<header class="post-header">
													<h3><a href="<?php the_permalink(); ?>" title="<?php echo $post->post_title; ?>"><?php the_title(); ?></a></h3>
													<p><span class="lsf">&#xE02b;</span> <?php echo sprintf(_n('1 artwork', '%s artworks', afdm_get_artist_artwork_count(), 'arteforadomuseu'), afdm_get_artist_artwork_count()); ?></p>
													<div class="buttons clearfix">
														<?php afdm_get_artist_visit_edit_button(); ?>
														<?php afdm_get_artist_delete_button(); ?>
													</div>
												</header>
											</div>
											<div class="five columns">
												<?php echo afdm_get_artist_mosaic(); ?>
											</div>
											<div class="four columns omega">
												<section class="post-content">
													<?php the_content(); ?>
												</section>
											</div>
										</div>
									</article>
								<?php endwhile; ?>
								<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
							</div>
						</section>
					</div>
				<?php endif; ?>
			</section>
		</div>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
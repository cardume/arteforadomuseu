<?php if(have_posts()) : ?>
	<section class="posts-section">
		<ul class="posts-list">
			<?php while(have_posts()) : the_post(); ?>
				<li id="post-<?php the_ID(); ?>">
					<article id="post-<?php the_ID(); ?>">
						<div class="clearfix">
							<?php if(has_post_thumbnail()) : ?>
								<div class="thumbnail-container">
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
								</div>
							<?php endif; ?>
							<header class="post-header">
								<?php do_action('afdm_loop_before_artwork_header'); ?>
								<p class="category"><?php echo get_the_category_list(', '); ?></p>
								<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
								<p class="meta">
									<span class="date"><span class="lsf">&#xE12b;</span> <?php echo get_the_date(); ?></span>
									<span class="views"><span class="lsf">&#xE13a;</span> <?php echo sprintf(_n('1 view', '%s views', afdm_get_views(), 'arteforadomuseu'), afdm_get_views()); ?></span>
								</p>
								<?php do_action('afdm_after_artwork_header'); ?>
							</header>
						</div>
						<?php /*
						<section class="post-content">
							<div class="post-excerpt">
								<?php the_excerpt(); ?>
							</div>
						</section>
						*/
						?>
						<aside class="actions clearfix">
							<?php echo jeo_find_post_on_map_button(); ?>
							<?php do_action('afdm_loop_artwork_actions'); ?>
						</aside>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>
		<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
	</section>
<?php endif; ?>
<?php if(have_posts()) : ?>
	<section class="posts-section">
		<ul class="posts-list">
			<?php while(have_posts()) : the_post(); ?>
				<li id="post-<?php the_ID(); ?>">
					<article id="post-<?php the_ID(); ?>">
						<header class="post-header">
							<?php do_action('afdm_before_artwork_header'); ?>
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
							<p class="meta">
								<span class="date"><?php echo get_the_date(); ?></span>
								<span class="author"><?php _e('by', 'mappress'); ?> <?php the_author(); ?></span>
							</p>
							<?php do_action('afdm_after_artwork_header'); ?>
						</header>
						<section class="post-content">
							<div class="post-excerpt">
								<?php the_excerpt(); ?>
							</div>
						</section>
						<aside class="actions">
							<?php echo mappress_find_post_on_map_button(); ?>
							<?php afdm_artguides_artwork_button(); ?>
						</aside>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>
	</section>
<?php endif; ?>
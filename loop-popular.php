<?php if(have_posts()) : ?>
	<section class="posts-section carousel">
		<ul class="posts-list popular">
			<?php while(have_posts()) : the_post(); ?>
				<li id="post-<?php the_ID(); ?>" class="clearfix">
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
						<?php if(has_post_thumbnail()) : ?>
							<div class="thumbnail-container">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
							</div>
						<?php endif; ?>
						<header class="post-header">
							<?php do_action('afdm_loop_before_artwork_header'); ?>
							<p class="category"><?php echo get_the_category_list(', '); ?></p>
							<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
							<?php /*
							<p class="meta">
								<span class="date"><span class="lsf">time</span> <?php echo get_the_date(); ?></span>
								<span class="views"><span class="lsf">view</span> <?php echo afdm_get_artwork_views(); ?></span>
							</p>
							*/ ?>
						</header>
					</article>
				</li>
			<?php endwhile; ?>
		</ul>
	</section>
<?php endif; ?>
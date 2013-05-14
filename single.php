<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php mappress_map(); ?>

	<article id="content" class="single-post">
		<header class="single-post-header clearfix">
			<?php the_post_thumbnail('page-featured'); ?>
			<h1><?php the_title(); ?></h1>
		</header>
		<section class="post-content">
			<?php the_content(); ?>
		</section>
		<?php
		$videos = afdm_get_videos();
		$featured_video_id = afdm_get_featured_video_id();
		if($videos) :
			?>
			<section id="videos" class="sub-content">
				<ul class="video-list">
					<?php foreach($videos as $video) : ?>
						<li><?php echo apply_filters('the_content', $video['url']); ?></li>
					<?php endforeach; ?>
				</ul>
			</section>
			<?php
		endif;
		?>
	</article>

<?php endif; ?>

<?php get_footer(); ?>
<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php
	$videos = afdm_get_videos();
	$featured_video_id = afdm_get_featured_video_id();
	?>

	<?php mappress_map(); ?>

	<article>
		<section id="content" class="single-post">
			<header class="single-post-header clearfix">
				<?php the_post_thumbnail('page-featured'); ?>
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="menu">
				<?php if($videos) : ?>
					<a href="#" data-subsection="videos"><span class="lsf">video</span> <?php _e('Videos', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
				<a href="#" data-subsection="images"><span class="lsf">images</span> <?php _e('Image gallery', 'arteforadomuseu'); ?></a>
				<?php if(mappress_is_streetview()) : ?>
					<a href="#" class="toggle-map"><span class="lsf">map</span> <?php _e('Toggle map', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
			</div>
			<section class="post-content">
				<?php the_content(); ?>
			</section>
		</section>
		<?php if($videos) : ?>
			<section id="videos" class="sub-content middle-content">
				<div class="content">
					<div class="sub-content-header">
						<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">close</span></a>
						<h3><?php _e('Videos', 'arteforadomuseu'); ?></h3>
					</div>
					<ul class="video-list">
						<?php foreach($videos as $video) : ?>
							<li><?php echo apply_filters('the_content', $video['url']); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
	</article>

<?php endif; ?>

<?php get_footer(); ?>
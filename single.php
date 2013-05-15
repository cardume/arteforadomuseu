<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php
	$videos = afdm_get_videos();
	$images = afdm_get_artwork_images();
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
				<?php if($images) : ?>
					<a href="#" data-subsection="images"><span class="lsf">images</span> <?php _e('Gallery', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
				<?php if(mappress_is_streetview()) : ?>
					<a href="#" class="toggle-map"><span class="lsf">map</span> <?php _e('Map', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
				<a href="#" data-subsection="comments"><span class="lsf">comments</span> <?php _e('Comments', 'arteforadomuseu'); ?></a>
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
		<?php if($images) : ?>
			<section id="images" class="sub-content middle-content">
				<div class="content">
					<div class="sub-content-header">
						<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">close</span></a>
						<h3><?php _e('Images', 'arteforadomuseu'); ?></h3>
					</div>
					<ul class="image-list">
						<?php foreach($images as $image) : ?>
							<li>
								<a href="<?php echo $image['full'][0]; ?>"><img src="<?php echo $image['thumb'][0]; ?>" /></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
		<section id="comments" class="sub-content middle-content">
			<div class="content">
				<div class="sub-content-header">
					<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">close</span></a>
					<h3><?php _e('Comments', 'arteforadomuseu'); ?></h3>
				</div>
				<div class="clearfix">
					<?php comments_template(); ?>
				</div>
			</div>
		</section>
	</article>

<?php endif; ?>

<?php get_footer(); ?>
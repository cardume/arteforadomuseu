<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php
	$videos = afdm_get_videos();
	$images = afdm_get_artwork_images();
	$featured_video_id = afdm_get_featured_video_id();
	$dimensions = afdm_get_artwork_dimensions();
	$creation_date = afdm_get_creation_date();
	$termination_date = afdm_get_termination_date();
	?>

	<?php mappress_map(); ?>

	<article>
		<section id="content" class="single-post">
			<header class="single-post-header clearfix">
				<?php the_post_thumbnail('page-featured'); ?>
				<?php the_category(); ?>
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
					<a href="#" class="toggle-map" data-toggled-text="<?php _e('StreetView', 'arteforadomuseu'); ?>" data-default-text="<?php _e('Map', 'arteforadomuseu'); ?>"><span class="lsf">map</span> <span class="label"><?php _e('Map', 'arteforadomuseu'); ?></span></a>
				<?php endif; ?>
				<a href="#" data-subsection="comments"><span class="lsf">comments</span> <?php _e('Comments', 'arteforadomuseu'); ?></a>
			</div>
			<aside class="actions clearfix">
				<?php do_action('afdm_loop_artwork_actions'); ?>
			</aside>
			<?php if($dimensions || $creation_date) : ?>
				<section class="post-data clearfix">
					<?php if($dimensions) : ?>
						<div class="dimensions">
							<h4><?php _e('Dimensions', 'arteforadomuseu'); ?></h4>
							<p>
								<?php echo $dimensions; ?>
							</p>
						</div>
					<?php endif; ?>
					<?php if($creation_date) : ?>
						<div class="dates">
							<h4><?php _e('Dates', 'arteforadomuseu'); ?></h4>
							<p class="creation">
								<strong><?php _e('Creation', 'arteforadomuseu'); ?></strong>
								<?php echo $creation_date; ?>
							</p>
							<?php if($termination_date) : ?>
								<p class="termination">
									<strong><?php _e('Termination', 'arteforadomuseu'); ?></strong>
									<?php echo $termination_date; ?>
								</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</section>
			<?php endif; ?>
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
					<ul class="video-list clearfix">
						<?php foreach($videos as $video) : ?>
							<li><?php echo apply_filters('the_content', $video['url']); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
		<?php if($images) : ?>
			<section id="images" class="sub-content middle-content">
				<div class="content image-gallery">
					<div class="sub-content-header">
						<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">close</span></a>
						<h3><?php _e('Image gallery', 'arteforadomuseu'); ?></h3>
					</div>
					<div class="image-stage-container">
						<div class="image-stage">
							<?php $image = $images[0]; ?>
							<a href="<?php echo $image['full'][0]; ?>" rel="shadowbox"><img src="<?php echo $image['large'][0]; ?>" /></a>
						</div>
					</div>
					<div class="image-list-container clearfix">
						<ul class="image-list">
							<?php foreach($images as $image) : ?>
								<li>
									<a href="<?php echo $image['large'][0]; ?>" data-full="<?php echo $image['full'][0]; ?>"><img src="<?php echo $image['thumb'][0]; ?>" /></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
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
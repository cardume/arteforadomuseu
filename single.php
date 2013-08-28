<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php
	$links = afdm_get_links();
	$videos = afdm_get_videos();
	$images = afdm_get_artwork_images();
	$featured_video_id = afdm_get_featured_video_id();
	$dimensions = afdm_get_artwork_dimensions();
	$creation_date = afdm_get_creation_date();
	$termination_date = afdm_get_termination_date();
	?>

	<?php jeo_map(); ?>

	<article>
		<section id="content" class="single-post">
			<header class="single-post-header clearfix">
				<?php the_post_thumbnail('page-featured'); ?>
				<?php the_category(); ?>
				<h1><?php the_title(); ?></h1>
				<?php if(afdm_has_artist()) : ?>
					<p class="artists"><span class="lsf">&#xE137;</span> <?php _e('Artists', 'arteforadomuseu'); ?>: <?php afdm_the_artist(); ?></p>
				<?php endif; ?>
			</header>
			<div class="menu">
				<?php if($videos) : ?>
					<a href="#" data-subsection="videos"><span class="lsf">&#xE139;</span> <?php _e('Videos', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
				<?php if($images) : ?>
					<a href="#" data-subsection="images"><span class="lsf">&#xE101;</span> <?php _e('Gallery', 'arteforadomuseu'); ?></a>
				<?php endif; ?>
				<?php if(jeo_is_streetview()) : ?>
					<a href="#" class="toggle-map" data-toggled-text="<?php _e('StreetView', 'arteforadomuseu'); ?>" data-default-text="<?php _e('Map', 'arteforadomuseu'); ?>"><span class="lsf">&#xE08b;</span> <span class="label"><?php _e('Map', 'arteforadomuseu'); ?></span></a>
				<?php endif; ?>
				<a href="#" data-subsection="comments"><span class="lsf">&#xE035;</span> <?php _e('Comments', 'arteforadomuseu'); ?></a>
			</div>
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
				<?php if($links) : ?>
					<h3>Links</h3>
					<ul class="post-links">
						<?php foreach ($links as $link) : ?>
							<li><a href="<?php echo $link['url'] ; ?>" rel="external" target="_blank" title="<?php echo $link['title']; ?>"><?php echo $link['title']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php the_terms($post->ID, 'style', '<p class="styles"><span class="lsf">&#xE128;</span> ' . __('Styles', 'arteforadomuseu') . ': ', ' ', '</p>'); ?>
			</section>
			<aside class="actions clearfix">
				<?php do_action('afdm_loop_artwork_actions'); ?>
			</aside>
		</section>
		<?php if($videos) : ?>
			<section id="videos" class="sub-content middle-content">
				<div class="content">
					<div class="sub-content-header">
						<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">&#xE10f;</span></a>
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
						<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">&#xE10f;</span></a>
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
					<a class="close" href="#"><?php _e('Close', 'arteforadomuseu'); ?> <span class="lsf">&#xE10f;</span></a>
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
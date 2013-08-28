<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php jeo_featured(true, true); ?>

	<?php
	$videos = afdm_get_videos();
	$links = afdm_get_links();
	?>

	<section id="content">
		<header class="single-post-header clearfix">
			<?php the_post_thumbnail('page-featured'); ?>
			<h2><a href="<?php echo afdm_artists_get_archive_link(); ?>" title="<?php _e('Artists', 'arteforadomuseu'); ?>"><?php _e('Artists', 'arteforadomuseu'); ?></a></h2>
			<h1><?php the_title(); ?></h1>
			<div class="header-meta">
				<?php
				$age = afdm_get_artist_age();
				if($age) : ?>
					<p class="lsf-icon" title="calendar"><?php echo $age; ?> <?php _e('years old', 'arteforadomuseu'); ?></p>
					<p><strong><?php _e('Birth', 'arteforadomuseu'); ?></strong>: <?php echo get_post_meta(get_the_ID(), 'birth_date', true); ?></p>
					<?php
					$death = get_post_meta(get_the_ID(), 'death_date', true);
					if($death) : ?>
						<p><strong><?php _e('Death', 'arteforadomuseu'); ?></strong>: <?php echo $death; ?></p> 
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</header>
		<div class="menu">
			<?php if($videos) : ?>
				<a href="#" data-subsection="videos"><span class="lsf">&#xE139;</span> <?php _e('Videos', 'arteforadomuseu'); ?></a>
			<?php endif; ?>
			<a href="#" data-subsection="comments"><span class="lsf">&#xE035;</span> <?php _e('Comments', 'arteforadomuseu'); ?></a>
		</div>
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
		</section>
		<?php query_posts(afdm_get_artist_query()); ?>
			<?php if(have_posts()) : ?>
				<section id="artworks" class="child-section">
					<div class="section-title">
						<h2><?php _e('Artworks', 'arteforadomuseu'); ?></h2>
					</div>
					<?php get_template_part('loop'); ?>
				</section>
			<?php endif; ?>
		<?php wp_reset_query(); ?>
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
						<li><?php echo $GLOBALS['wp_embed']->autoembed($video['url']); ?></li>
					<?php endforeach; ?>
				</ul>
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

<?php endif; ?>

<?php get_footer(); ?>
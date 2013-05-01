<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

	<?php mappress_map(); ?>

	<article id="content" class="single-post">
		<header class="single-post-header clearfix">
			<h1><?php the_title(); ?></h1>
		</header>
		<section class="post-content">
			<?php the_content(); ?>
		</section>
	</article>

<?php endif; ?>

<?php get_footer(); ?>
<?php get_header(); ?>

<?php jeo_featured(); ?>

<section id="content">

	<?php do_action('afdm_before_content'); ?>

	<div class="child-section">
		<div class="section-title">
			<h2><?php _e('Results for: ', 'arteforadomuseu'); ?> "<?php echo $_GET['s']; ?>"</h2>
		</div>
		<?php get_template_part('loop'); ?>
	</div>
</section>

<?php get_footer(); ?>
<?php
/*
 * Mousehover bubble content
 */
?>
<span class="arrow">&nbsp;</span>
<?php the_post_thumbnail('thumbnail'); ?>
<?php the_category(); ?>
<h4><?php the_title(); ?></h4>
<?php if(afdm_has_artist()) : ?>
	<div class="meta">
		<p class="artists"><span class="lsf">user</span> <?php _e('Artists', 'arteforadomuseu'); ?>: <?php afdm_the_artist(); ?></p>
	</div>
<?php endif; ?>
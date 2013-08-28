<?php get_header(); ?>

<?php jeo_featured(); ?>

<section id="content">

	<?php do_action('afdm_before_content'); ?>

	<div class="child-section">
		<div class="section-title">
			<h2 class="archive-title"><?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'arteforadomuseu' ), get_the_date() );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'arteforadomuseu' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'arteforadomuseu' ) ) );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'arteforadomuseu' ), get_the_date( _x( 'Y', 'yearly archives date format', 'arteforadomuseu' ) ) );
					elseif(is_tax()) :
						single_term_title(__('Currently browsing', 'arteforadomuseu') . ': ');
					else :
						_e( 'Archives', 'arteforadomuseu' );
					endif;
				?></h1>
		</div>
		<?php get_template_part('loop'); ?>
	</div>
</section>

<?php get_footer(); ?>
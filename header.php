<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | Página ' . max($paged, $page);

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(get_bloginfo('language')); ?>>
	<header id="masthead">
		<div class="container">
			<div class="two columns">
				<div class="site-meta">
					<h1>
						<a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
							<?php bloginfo('name'); ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png" alt="<?php bloginfo('name'); ?>" class="scale-with-grid" />
						</a>
					</h1>
				</div>
			</div>
			<div class="three columns">
				<?php
				$user_city = afdm_get_user_city();
				$cities = get_terms('city');
				if($cities) :
					?>
					<div class="city-selector">
						<?php if($user_city) : ?>
							<h2 class="city-title"><span class="lsf">down</span> <?php echo $user_city; ?></h2>
						<?php else : ?>
							<span class="city-title"><span class="lsf">down</span> <?php _e('Select a city', 'arteforadomuseu'); ?></span>
						<?php endif; ?>
						<ul class="city-list">
							<?php if($user_city) : ?>
								<li class="tip"><?php _e('Choose another city:', 'arteforadomuseu'); ?></li>
								<li>
									<a href="?select_city=all"><?php _e('All cities', 'arteforadomuseu'); ?></a>
								</li>
							<?php endif; ?>
							<?php foreach($cities as $city) : ?>
								<?php if($user_city == $city->name) continue; ?>
								<li>
									<a href="?select_city=<?php echo $city->term_id; ?>"><?php echo $city->name; ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
			<div class="seven columns">
				<div id="masthead-nav">
					<div class="clearfix">
						<nav id="main-nav">
							<ul>
								<li><a href="<?php echo afdm_artguides_get_archive_link(); ?>"><?php _e('Art guides', 'arteforadomuseu'); ?></a></li>
								<li><a href="#"><?php _e('Artists', 'arteforadomuseu'); ?></a></li>
								<?php
								$categories = get_categories();
								if($categories) :
									?>
									<li class="categories">
										<?php
										if(is_category()) :
											$current_category = array_shift(get_the_category());
											$category_id = $current_category->term_id;
											?>
											<a href="<?php echo get_category_link($category_id); ?>" class="current-menu-item <?php echo $current_category->slug; ?>"><?php echo $current_category->name; ?> <span class="lsf">down</span></a>
										<?php else : ?>
											<a href="#"><?php _e('Categories', 'arteforadomuseu'); ?> <span class="lsf">down</span></a>
										<?php endif; ?>
										<ul class="category-list">
											<?php if($current_category) : ?>
												<li class="tip"><?php _e('Choose another category:', 'arteforadomuseu'); ?></li>
											<?php endif; ?>
											<?php foreach($categories as $category) : ?>
												<?php if($current_category && $category->term_id == $category_id) continue; ?>
												<li>
													<a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
												</li>
											<?php endforeach; ?>
										</ul>
									</li>
								<?php endif; ?>
							</ul>
						</nav>
						<?php get_search_form(); ?>
						<?php afdm_get_user_menu(); ?>
					</div>
				</div>
			</div>
		</div>
	</header>
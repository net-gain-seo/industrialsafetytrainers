<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<?php wp_head(); ?>


</head>
<body <?php body_class(); ?>>

<header>
	<section class="main-header container">
		<a id="logo" href="<?php bloginfo('url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/industrial-safety-trainers-logo.png" /></a>

		<div>
			<div class="flex-column">
				<div class="heading-qualifications align-items-center hide-sticky-header">
					<div class="heading-qualification hide-screen-md-max">
						<span>Ontario Ministry of Labour</span>
						<span>Authorized Training Provider</span>
					</div>
					<div class="heading-qualification hide-screen-md-max">
						<span>TSSA</span>
						<span>Accredited Training Provider</span>
					</div>
					<div>
						<form role="search" method="get" action="<?php echo home_url( '/' ); ?>" class="hide-screen-sm-max">
							<div class="input-group">
								<input type="search" class="form-control" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
								<div class="btn-group">
									<div class="btn-group">
										<input type="submit" class="btn btn-primary" value="GO!" />
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="header-call-to-action">
					<div>
						<span class="header-call-to-action-content hide-screen-md-max">Ontario's Leading Health & Safety Training Partner for Over 15 Years</span>
					</div>
					<div class="header-call-to-action-phone text-right">
						<span class="hide-sticky-header hide-screen-lg-max">Book Your Safety Training</span>
						<span>1-800-219-8660</span>
					</div>
				</div>

			</div>
		</div>
	</section>

	<section class="menu-section">
		<div class="container d-flex justify-content-between align-items-center">
			<nav class="navbar navbar-inverse navbar-toggleable-lg navbar-light" role="navigation">

				<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div id="mainMenu" class="collapse navbar-collapse">
					<div class="closen-responsive-nav hide-screen-lg-min" data-toggle="collapse" data-target="#mainMenu">Close</div>
					<?php 
					wp_nav_menu( 
						array(
							'theme_location'    => 'main',
							'container'         => 'false',
							'menu_class'        => 'navbar-nav mr-auto',
							'walker'			=> new bootstrap_Walker(false)
						)
					);
					?>

				</div>

		    </nav>
		    <a href="#" class="btn btn-danger">SAFETY SUPPLIES</a>
	    </div>
    </section>
</header>


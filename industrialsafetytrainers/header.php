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

		<?php


		if(get_current_blog_id() == 3){
			echo '<div id="logo">';
				echo '<a href="'.get_site_url(1).'" style="margin-right:30px"><img id="ist-logo" src="'.get_stylesheet_directory_uri().'/assets/images/ist-logo.jpg"></a>';
				echo '<a href="'.get_site_url(2).'"><img id="cst-logo" src="'.get_stylesheet_directory_uri().'/assets/images/cst-logo.jpg"></a>';
			echo '</div>';
		}else{
			if(get_current_blog_id() == 2){
				$logoSrc = get_stylesheet_directory_uri().'/assets/images/construction-safety-trainers-logo.png';
			}else{
				$logoSrc = get_template_directory_uri().'/assets/images/industrial-safety-trainers-logo.png';
			}
			echo '<a id="logo" href="'.get_bloginfo('url').'"><img src="'.$logoSrc.'" /></a>';
		}

		?>


		<?php if(get_current_blog_id() != 3){ ?>
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
					<span class="header-call-to-action-content hide-screen-md-max">Ontario's Leading Health & Safety Training Partner for Over 15 Years</span>
					<div class="header-call-to-action-phone text-right">
						<span class="hide-sticky-header hide-screen-lg-max">Book Your Safety Training</span>
						<span><a href="tel:18002198660">1-800-219-8660</a></span>
					</div>
				</div>

			</div>
		</div>
		<?php }else{ ?>
		<div>
			<div class="flex-column">
				<div class="button-list hide-sticky-header hide-screen-md-max">
					<a class="btn btn-primary" href="<?php echo bloginfo('url'); ?>/my-account/">My Account</a>
					<!--
					<a class="btn btn-primary" href="#">My Wishlist</a>
					-->
					<a class="btn btn-primary" href="<?php echo bloginfo('url'); ?>/my-account/orders/">Order Status</a>
					<a class="btn btn-primary" href="<?php echo get_site_url(1); ?>/contact-us/">Contact Us</a>
				</div>
				<div class="safety-suppliers-search align-items-center">
					<form role="search" method="get" action="<?php echo home_url( '/' ); ?>" class="hide-screen-md-max">
						<div class="input-group" style="margin-bottom: 0px;">
							<input type="search" class="form-control" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
							<div class="btn-group">
								<div class="btn-group">
									<input type="submit" class="btn btn-primary" value="GO!" />
								</div>
							</div>
						</div>
					</form>

					<div class="header-call-to-action">
						<div class="header-call-to-action-phone text-right">
							<span>Call Toll Free: <a href="tel:18002198660">1-800-219-8660</a></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</section>

	<section class="menu-section">
		<div class="container d-flex justify-content-end flex-end align-items-center">
			<nav class="navbar navbar-inverse navbar-toggleable-lg navbar-light" role="navigation">

				<button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
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

				<div class="mobileMenuOverlay"></div>

		    </nav>
				<div id="othersites-header">
					<a href="https://onlinesafetysupplies.ca/"><div class="site-button shopcart-button"></div></a>
					<a href="https://constructionsafetytrainers.ca/"><div class="site-button construction-button"></div></a>
					<a href="https://thesafetybus.com/"><div class="site-button industrial-button"></div></a>
				</div>
		    <?php /* if(get_current_blog_id() != 3){
		    	if(get_current_blog_id() == 2){
		    		?>
		    		<a href="<?php echo get_site_url(3); ?>" class="btn btn-danger">SHOP NOW</a>
		    		<?php
		    	}else{
		    		?>
		    		<a href="<?php echo get_site_url(3); ?>" class="btn btn-danger">SAFETY SUPPLIES</a>
		    	<?php
		    	}

		    }else{ ?>

		    <?php } */?>
	    </div>
    </section>
</header>

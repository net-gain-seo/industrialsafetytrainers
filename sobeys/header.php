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
	<section class="main-header container" style="justify-content:flex-start" id="logo">

		<?php

		echo '<a id="" href="'.get_bloginfo('url').'" style="margin-right:10px;"><img src="'.get_template_directory_uri().'/assets/images/industrial-safety-trainers-logo.png" style="border-right: 1px solid #dddddd;padding-right: 10px;" /></a>';

        echo '<a href="'.get_bloginfo('url').'" style="align-self:center"><img src="'.get_stylesheet_directory_uri().'/assets/images/sobeys-logo.jpg" style="height:50px;" /></a>';
        echo '<a href="'.get_bloginfo('url').'" style="align-self:center;margin-right:10px;"><img src="'.get_stylesheet_directory_uri().'/assets/images/freshco-logo.jpg" style="height:50px;" /></a>';
        echo '<a href="'.get_bloginfo('url').'" style="align-self:center"><img src="'.get_stylesheet_directory_uri().'/assets/images/Foodland-logo.jpg" style="height:50px;" /></a>';

		?>

        

		
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
				
		    <?php/* if(get_current_blog_id() != 3){
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

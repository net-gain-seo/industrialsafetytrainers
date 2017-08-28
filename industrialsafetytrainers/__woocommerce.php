<?php get_header(); ?>

	<main role="main">

		

		<?php 
		echo '<section class="banner-section">';
			/*
			echo '<div class="banner-content d-flex justify-content-center align-items-stretch">';
				echo '<div>';
					echo '<div class="d-flex align-items-center fifty-percent-section right">';
						echo '<h1>'.get_the_title().'</h1>';
					echo '</div>';
				echo '</div>';
				echo '<div>';

				echo '</div>';
			echo '</div>';
			*/
			//the_post_thumbnail();
			echo '<img src="'.get_stylesheet_directory_uri().'/assets/images/store-banner.png" />';
		echo '</section>';
		?>
		
		<section class="container">
			<?php woocommerce_content(); ?>
		</section>
		

	</main>

<?php get_footer(); ?>
<?php /* Template Name: Full Width Page */ ?>
<?php get_header(); ?>

	<main role="main">
		<!-- section -->
			<?php 
			if(has_post_thumbnail()){
				echo '<section class="banner-section">';
					echo '<div class="banner-content d-flex justify-content-center align-items-stretch">';
						echo '<div>';
							echo '<div class="d-flex align-items-center fifty-percent-section right">';
								echo '<h1>'.get_the_title().'</h1>';
							echo '</div>';
						echo '</div>';
						echo '<div>';

						echo '</div>';
					echo '</div>';
					the_post_thumbnail();
				echo '</section>';
			}else{
				echo '<section class="page-title-section">';
					echo '<div class="container">';
						echo '<h1>'.get_the_title().'</h1>';
					echo '</div>';
				echo '</section>';
			}
			?>

		<section>	
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_content(); ?>
			</article>
			<!-- /article -->

			<?php endwhile; ?>
			<?php endif; ?>

		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
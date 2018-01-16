<?php get_header(); ?>

	<main role="main">
		<section class="banner-section">
			<div class="banner-content d-flex justify-content-center align-items-stretch">
				<div class="container">
					<div class="d-flex align-items-center" style="height: 100%">
						<div>
							<h1>Welcome to<br/> On-Line Safety Supplies.</h1>
						</div>
					</div>
				</div>
			</div>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/store-banner.png" />
		</section>
		<!-- section -->
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
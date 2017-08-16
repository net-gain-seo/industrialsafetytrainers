<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section class="container">
			<h1><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentysixteen' ); ?></h1>
			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentysixteen' ); ?></p>
			</article>
			<!-- /article -->
		</section>
		<!-- /section -->
	</main>
<?php get_footer(); ?>
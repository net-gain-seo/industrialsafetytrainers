<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<?php if(!is_page('safety-training-course-public-dates')){ ?>
			<section class="page-title-section">
				<div class="container">
					<h1><?php the_title(); ?></h1>
				</div>
			</section>
		<?php }else{
			$args = array(
	            'name'        => $_GET['course'],
	            'post_type'   => 'product',
	            'post_status' => 'publish',
	            'numberposts' => 1
	        );
	        $current_product = get_posts($args);

	        //print_r($current_product);
			?>
				<section class="page-title-section">
					<div class="container">
						<h1><?php echo $current_product[0]->post_title; ?></h1>
					</div>
				</section>
			<?php
		} ?>
		
		<section class="container">

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
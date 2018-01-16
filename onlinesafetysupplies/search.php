<?php get_header(); ?>

<main role="main">
	<!-- section -->
		<section class="banner-section">
			<div class="banner-content d-flex justify-content-center align-items-stretch">
				<div class="container">
					<div class="d-flex align-items-center" style="height: 100%">
						<div>
							<h1>Search Results</h1>
						</div>
					</div>
				</div>
			</div>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/store-banner.png" />
		</section>

		<div class="container blog-container woocommerce">
			<section>
				<?php 
						echo '<br/><br/>';

						echo '<h2>Supplies</h2>';
						$current = get_current_blog_id();
						switch_to_blog(3);
						//echo do_shortcode('[course_search search="'.$_GET['s'].'"]');
						$posts = get_posts( 
							array( 
								'post_type' => 'product', 
								'numberposts' => - 1, 
								's' => get_search_query(),
								'tax_query' => array(
									[
										'taxonomy' => 'product_cat',
										'terms'		=> 17,18,
										'operator'  => 'NOT IN'
									]
								)
							) 
						);

						echo '<ul class="products">';
							foreach($posts as $post){
								echo '<li class="product">';
									echo get_the_post_thumbnail($post->ID);
									echo '<div>';
										echo '<h3>'.$post->post_title.'</h3>';
										echo '<a class="btn btn-primary" href="'.$post->guid.'">View</a>';
									echo '</div>';
								echo '</li>';
							}
						echo '</ul>';
						switch_to_blog($current);

					?>
		</div>




	</main>

<?php echo do_shortcode('[common_element id="55" name="CTA"]'); ?>
<?php echo do_shortcode('[common_element id="52" name="About Industrial Safety Trainers"]'); ?>
	
<?php get_footer(); ?>
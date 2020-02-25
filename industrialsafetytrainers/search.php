<?php get_header(); ?>

<main role="main">
	<!-- section -->
		<section class="banner-section">
			<div class="banner-content d-flex justify-content-center align-items-stretch">
				<div>
					<div class="d-flex align-items-center fifty-percent-section right">
						<h1>SEARCH</h1>
					</div>
				</div>
				<div></div>
			</div>

			<img width="2000" height="375" src="https://thesafetybus.com/wp-content/uploads/2017/08/whats-new.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="https://thesafetybus.com/wp-content/uploads/2017/08/whats-new.png 2000w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-300x56.png 300w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-768x144.png 768w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-1024x192.png 1024w" sizes="(max-width: 2000px) 100vw, 2000px">
		</section>

		<div class="container blog-container">
			<aside class="sidebar">
				<?php
					echo '<h2>Blog Posts</h2>';
					if (have_posts()): while (have_posts()) : the_post(); ?>
						<article class="post d-flex" style="margin-bottom:20px;">
							<div>
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

								<?php the_excerpt(); ?>
								<div class="post-meta d-flex">
									<span style="margin-right: 10px;"><?php the_date('M d, Y'); ?></span>
									<span><?php echo the_category(', '); ?></span>
								</div>
							</div>
						</article>
						<?php
						endwhile; ?>
					<?php endif; ?>


			</aside>
			
			<div>
				<h2>Courses</h2>
				<section class="posts">
					<?php
					$current = get_current_blog_id();
					//switch_to_blog(3);
					
					//echo do_shortcode('[course_search search="'.$_GET['s'].'"]');
					$posts = get_posts(
						array(
							'post_type' => 'product',
							'numberposts' => - 1,
							's' => get_search_query(),
							'tax_query' => array(
								[
									'taxonomy' => 'product_cat',
									'terms'		=> 30
								]
							)
						)
					);
					//print_r($posts);
					foreach($posts as $post){
						//print_r($post);
						echo '<article class="post d-flex">';
							echo '<div>';
								echo '<h2>'.$post->post_title.'</h2>';
								//echo '<p>'.$post->post_excerpt.'</p>';
								echo '<a class="btn btn-primary" href="https://thesafetybus.com/safety-training-course/?course='.$post->post_name.'">More Information</a>';
							echo '</div>';
						echo '</article>';
					}
					//switch_to_blog($current);
					?>
				</section>
			</div>
		</div>




	</main>

<?php echo do_shortcode('[common_element id="55" name="CTA"]'); ?>
<?php echo do_shortcode('[common_element id="52" name="About Industrial Safety Trainers"]'); ?>

<?php get_footer(); ?>

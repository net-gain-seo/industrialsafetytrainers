<?php get_header(); ?>

<main role="main">
	<!-- section -->
		<section class="banner-section">
			<div class="banner-content d-flex justify-content-center align-items-stretch">
				<div>
					<div class="d-flex align-items-center fifty-percent-section right">
						<h1>WHAT’S NEW?</h1>
					</div>
				</div>
				<div></div>
			</div>

			<img width="2000" height="375" src="https://thesafetybus.com/wp-content/uploads/2017/08/whats-new.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="https://thesafetybus.com/wp-content/uploads/2017/08/whats-new.png 2000w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-300x56.png 300w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-768x144.png 768w, https://thesafetybus.com/wp-content/uploads/2017/08/whats-new-1024x192.png 1024w" sizes="(max-width: 2000px) 100vw, 2000px">
		</section>

		<div class="container blog-container">
			<aside class="sidebar">
				<?php
					dynamic_sidebar('blog-sidebar');
				?>
			</aside>
			<section class="posts">
				<?php
				if (have_posts()): while (have_posts()) : the_post(); ?>
					<article class="post d-flex">
						<div class="<?php if(has_post_thumbnail()){ echo 'has-thumbnail'; } ?>">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

							<?php
								if(has_post_thumbnail()){
									echo '<a href="'.get_the_permalink().'">';
										the_post_thumbnail();
									echo '</a>';
								}
							?>

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
			</section>
		</div>


		<div class="container d-flex justify-content-center">
			<div class="nav-next"><?php previous_posts_link( '<span class="btn btn-primary left-arrow"></span> Previous Page' ); ?></div>
			<div class="nav-previous"><?php next_posts_link( 'Next Page <span class="btn btn-primary right-arrow"></span>' ); ?></div>
		</div>



	</main>

<?php
if(get_current_blog_id() == 1){
	echo do_shortcode('[common_element id="55" name="CTA"]');
	echo do_shortcode('[common_element id="52" name="About Industrial Safety Trainers"]');
}
if(get_current_blog_id() == 2){
	echo do_shortcode('[common_element id="128" name="ARE YOU LOOKING FOR SOMETHING SPECIFIC?"]');
	echo do_shortcode('[common_element id="44" name="About Construction Safety Trainers"]');
}
?>

<?php get_footer(); ?>

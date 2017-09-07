<?php get_header(); ?>

<main role="main">
	<!-- section -->
		<section class="banner-section">
			<div class="banner-content d-flex justify-content-center align-items-stretch">
				<div>
					<div class="d-flex align-items-center fifty-percent-section right">
						<h1>Business We Support</h1>
					</div>
				</div>
				<div>

				</div>
			</div>
			
			<img width="2000" height="375" src="http://209.126.119.193/~industrialsafety/wp-content/uploads/2017/08/about-us.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="http://209.126.119.193/~industrialsafety/wp-content/uploads/2017/08/about-us.png 2000w, http://209.126.119.193/~industrialsafety/wp-content/uploads/2017/08/about-us-300x56.png 300w, http://209.126.119.193/~industrialsafety/wp-content/uploads/2017/08/about-us-768x144.png 768w, http://209.126.119.193/~industrialsafety/wp-content/uploads/2017/08/about-us-1024x192.png 1024w" sizes="(max-width: 2000px) 100vw, 2000px">

		</section>

		<?php 
			$postcount=1;
		if (have_posts()): while (have_posts()) : the_post(); ?>
			<section class="<?php if( ($postcount % 2) == 0 ) echo ' grey-background'; ?>">
				<article class="container post-article in-the-communuity-article">
					<div class="<?php if(has_post_thumbnail()){ echo 'has-thumbnail'; } ?>">
						<h2 class="h3"><?php the_title(); ?></h2>
						<?php the_content(); ?>
					</div>
					<?php 
					if(has_post_thumbnail()){
						the_post_thumbnail();
					}
					?>
				</article>
			</section>
			<?php 
			 $postcount++;
			endwhile; ?>
		<?php endif; ?>


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
	echo do_shortcode('[common_element id="63" name="REQUEST A TRAINING QUOTE"]');
	echo do_shortcode('[common_element id="44" name="About Construction Safety Trainers"]'); 
}
?>
	
<?php get_footer(); ?>
<?php get_header(); ?>

<main role="main">
	<!-- section -->


		<div class="container d-flex">
			<!--<aside class="sidebar">
				<?php
					dynamic_sidebar('blog-sidebar');
				?>
			</aside>-->
			<section class="posts singlePosts">
				<?php
				if (have_posts()): while (have_posts()) : the_post(); 
					if(has_post_thumbnail()){
						//the_post_thumbnail('blog-image');
					}
					?>
					<article class="">
						<div class="row">
							<div class="col col-12 post-header">
								<h1><?php the_title(); ?></h1>
							</div>
						</div>
						<div class="row">
							<div class="col col-12">
								<?php the_content(); ?>
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

<?php get_footer(); ?>

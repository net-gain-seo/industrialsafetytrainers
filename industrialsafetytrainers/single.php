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
					<article class="post">
						<div class="row">
							<div class="col col-12 post-header">
								<h1><?php the_title(); ?></h1>
								<span style="margin-right: 10px;"><?php the_date('M d, Y'); ?></span>
								<span><?php echo the_category(', '); ?></span>
							</div>
						</div>
						<div class="row">
							<div class="col col-9">
								<?php the_content(); ?>
							</div>
							<div class="col col-3 relatedPosts">
								<h2>Related Posts</h2>
								<?php 
									$related = new WP_Query(
									    array(
									        'category__in'   => wp_get_post_categories( $post->ID ),
									        'posts_per_page' => 5,
									        'post__not_in'   => array( $post->ID )
									    )
									);

									if( $related->have_posts() ) { 
									    while( $related->have_posts() ) { 
									        $related->the_post(); 
									        /*whatever you want to output*/
									        echo '<div class="relatedPost">';
										        echo '<a href="'.get_permalink().'">';
										        	echo '<h3>'.get_the_title().'</h3>';
										        	if(has_post_thumbnail()){
														the_post_thumbnail();
													}
												echo '</a>';
									        echo '</div>';
									    }
									    wp_reset_postdata();
									}
								?>
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

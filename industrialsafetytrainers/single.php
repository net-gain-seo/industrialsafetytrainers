<?php get_header(); ?>

<main role="main">
	<!-- section -->


		<div class="container d-flex">
			<!--<aside class="sidebar">
				<?php
					dynamic_sidebar('blog-sidebar');
				?>
			</aside>-->
			<section class="singlePost">
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
							<div class="col col-12">
								<?php the_content(); ?>
							</div>
						</div>
						<div class="row">
							

							

						</div>
					</article>
					<?php
					endwhile; ?>
				<?php endif; ?>
			</section>
		</div>


		<div class="container" style="padding-bottom: 0">
			<div class="row">
				<div class="col-12">
					<h2>Related Posts</h2>
				</div>
			</div>
		</div>
		<div class="container blog-container" style="padding-top: 0px;">

			<section class="posts postsList">
				
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
				     ?>
					<article class="post d-flex" style="padding-top: 0px;">
						<a class="post-main-link" href="<?php the_permalink(); ?>">
							<div class="<?php if(has_post_thumbnail()){ echo 'has-thumbnail'; } ?>">
								<div class="post-meta">
									<span style="font-weight: 600; text-transform: uppercase;" class="postCategories"><?php 
										foreach((get_the_category()) as $key => $category){
											if($key == 0){
												echo $category->name.'';
											}else{
												echo ', '.$category->name;
											}
									        
									    }
									?></span>
								</div>
								<h2><?php the_title(); ?></h2>
								<?php
									if(has_post_thumbnail()){
										the_post_thumbnail();
									}
								?>

								<span style="margin-right: 10px; float: left; font-weight: bold; line-height: 1.8"><?php the_date('M d, Y'); ?>  - </span><?php the_excerpt(); ?>
								
							</div>
						</a>
					</article>
					<?php
					}
				    wp_reset_postdata();
				}
				?>
		</section>
	</div>


	<div class="container d-flex justify-content-center">
		<div class="nav-next"><?php previous_posts_link( '<span class="btn btn-primary left-arrow"></span> Previous Page' ); ?></div>
		<div class="nav-previous"><?php next_posts_link( 'Next Page <span class="btn btn-primary right-arrow"></span>' ); ?></div>
	</div>



	</main>

<?php get_footer(); ?>

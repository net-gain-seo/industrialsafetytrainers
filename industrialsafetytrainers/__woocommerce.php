<?php get_header(); ?>

	<main role="main">



		<?php
		echo '<section class="banner-section">';
			/*
			echo '<div class="banner-content d-flex justify-content-center align-items-stretch">';
				echo '<div>';
					echo '<div class="d-flex align-items-center fifty-percent-section right">';
						echo '<h1>'.get_the_title().'</h1>';
					echo '</div>';
				echo '</div>';
				echo '<div>';

				echo '</div>';
			echo '</div>';
			*/
			//the_post_thumbnail();
			echo '<img src="'.get_stylesheet_directory_uri().'/assets/images/store-banner.png" />';
		echo '</section>';
		global $category_help_url, $wp_query;

 	 $terms_post = get_the_terms( $post->cat_ID , 'product_cat' );
     foreach ($terms_post as $term_cat) {
 		    $term_cat_id = $term_cat->term_id;
 		}
 	 $category_help_url = get_term_meta($term_cat_id, 'category_help_url', true);

 	 if($category_help_url != ''){ ?>
 		 <section class="container">
 		 <p>Can’t find what you are looking for, <a href="<?php echo $category_help_url; ?>" target="_blank">click here</a> to see more of a selection. Contact us with the item number and we’ll send you costing.</p>
 		 </section>
 	 <?php }else{ ?> <section class="container"><?php echo $category_help_url; ?></section> <?php } ?>


		<section class="container">
			<?php woocommerce_content(); ?>
		</section>


	</main>

<?php get_footer(); ?>

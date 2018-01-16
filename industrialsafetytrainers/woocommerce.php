<?php get_header(); ?>

	<main role="main">



		<?php
		global $category_help_url, $wp_query;

 	 $terms_post = get_the_terms( $post->cat_ID , 'product_cat' );
     foreach ($terms_post as $term_cat) {
 		    $term_cat_id = $term_cat->term_id;
 		}
 	 $category_help_url = get_term_meta($term_cat_id, 'category_help_url', true);

 	 if($category_help_url != ''){ ?>
 		 <section class="container helptext">
 		 <p>Can’t find what you are looking for, <a href="<?php echo $category_help_url; ?>" target="_blank">click here</a> to see more of a selection. Contact us with the item number and we’ll send you costing.</p>
 		 </section>
 	 <?php }else{ ?> <?php } ?>

		<section class="container">
			<?php woocommerce_content(); ?>
		</section>


	</main>

<?php get_footer(); ?>

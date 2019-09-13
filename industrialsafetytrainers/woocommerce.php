<?php get_header(); ?>

	<main role="main">

		<div class="container" style="padding-bottom: 0px;">
			<div class="row">
				<div class="col-12">
					<?php woocommerce_breadcrumb(array('home'=>false)); ?>
				</div>
			</div>
		</div>

		<?php
		global $category_help_url, $wp_query;

		 	 $category_help_url = get_term_meta($wp_query->get_queried_object()->term_id, 'category_help_url', true);

		 	 if($category_help_url != ''){ ?>
		 		 <section class="container helptext">
		 		 <p>Can’t find what you are looking for, <a href="<?php echo $category_help_url; ?>" target="_blank">click here</a> to see more of a selection. Contact us with the item number and we’ll send you costing.</p>
		 		 </section>
		 	 <?php }else{  } ?>
	 	 <?php 
	 	 ?>

		<section class="container">
			<?php woocommerce_content(); ?>
		</section>


	</main>

<?php get_footer(); ?>
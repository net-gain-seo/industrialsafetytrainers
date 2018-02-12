<?php
/**
 * Show messages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/notices/success.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ) {
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message"><?php echo wp_kses_post( $message ); ?></div>
<?php endforeach; ?>

<section class="up-sells upsells products">
<?php $upsell_ids = get_post_meta( get_the_ID(), '_upsell_ids' );
$upsell_ids=$upsell_ids[0]; ?>
<?php
if(count($upsell_ids)>0){ ?>
<div class="woocommerce-title-section"><h3 class="h3">Related Products</h3></div>

<?php $args = array( 'post_type' => 'product', 'posts_per_page' => 4, 'post__in' => $upsell_ids );
$loop = new WP_Query( $args );
?><ul class="products"><?php
while ( $loop->have_posts() ) : $loop->the_post();
?><li class="product "><a href='<?php the_permalink(); ?>'><?php
the_post_thumbnail( 'thumbnail' ); ?>
<h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2></a>
<?php do_action( 'woocommerce_after_shop_loop_item_title' );
do_action( 'woocommerce_after_shop_loop_item' ); ?>
</li>
<?php
endwhile;
}
?></ul>
</section>

<?php wp_reset_postdata(); ?>

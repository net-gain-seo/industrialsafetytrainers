
<footer>
	<section class="upper-footer">
		<div class="container">
			<div class="footer-logo">
				<?php
				if(get_current_blog_id() == 2){
					$logoSrc = get_stylesheet_directory_uri().'/assets/images/construction-safety-trainers-logo-white.png';
				}elseif(get_current_blog_id() == 3){
					$logoSrc = get_stylesheet_directory_uri().'/assets/images/online-safety-supplies-logo-footer.png';
				}else{
					$logoSrc = get_template_directory_uri().'/assets/images/industrial-safety-trainers-logo-white.png';
				}
				?>
				<img src="<?php echo $logoSrc; ?>" />
				<br/>
				<span>Ontario's Leading Health & Safety Training Partner for Over 15 Years </span>
				<br>
				<br>
				<a style="color:#ffffff;" href="https://thesafetybus.com/cancellation-policy/">Course Cancellation Policy</a>
				<br>
				<a style="color:#ffffff;" href="https://thesafetybus.com/privacy-policy/">Privacy Policy</a>
				

			</div>
			<div class="footer-training-cources">
				<?php dynamic_sidebar('footer-1'); ?>
			</div>
			<div>
				<?php dynamic_sidebar('footer-2'); ?>
			</div>
			<?php if(get_current_blog_id() != 3){ ?>
			<div class="footer-contact">
				<?php dynamic_sidebar('footer-3'); ?>
			</div>
			<?php } ?>
		</div>
	</section>

	<section class="footer-copy">
		<span>Copyright 2019 Industrial Safety Trainers, All Right Reserved</span>
	</section>

</footer>

	<?php wp_footer(); ?>
	</body>
</html>

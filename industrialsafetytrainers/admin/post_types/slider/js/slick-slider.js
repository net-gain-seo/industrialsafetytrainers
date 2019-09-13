jQuery(document).ready(function(){

	jQuery('.slider').slick({
		dots: false,
		infinite: true,
		speed: 300,
		autoplay: true,
		autoplaySpeed: 10000
	});


	jQuery('.relatedPostsSlider').slick({
		infinite: true,
	  slidesToShow: 3,
	  slidesToScroll: 1
	});
});
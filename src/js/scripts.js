

function elementInViewport(element) {
  var rect = element.getBoundingClientRect();

  var html = document.documentElement;
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || html.clientHeight) &&
    rect.right <= (window.innerWidth || html.clientWidth)
  );
}


function checkOnScrollElements(){
	//fork lift animation
	let animateForkLift = false;
	let forkLiftSelector = document.getElementsByClassName("fork-lift");


	if(elementInViewport(forkLiftSelector[0])){
		if(!forkLiftSelector[0].classList.contains('fork-lift-animated')){
			console.log('adding class');
			forkLiftSelector[0].className += ' fork-lift-animated';
		}
	}
}


// document ready
(function() {
	window.onscroll = function() {checkOnScrollElements()};
})();
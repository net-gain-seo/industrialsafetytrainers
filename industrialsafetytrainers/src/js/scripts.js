

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
	//Sticky Header
	//var docTop = document.documentElement.scrollTop;
	var docTop = document.documentElement.scrollTop || document.body.scrollTop;
	if(docTop >= 120){
		document.body.classList.add("sticky-header");
	}else{
		document.body.classList.remove("sticky-header");
	}


	//fork lift animation
	let animateForkLift = false;
	let forkLiftSelector = document.getElementsByClassName("fork-lift");


	if(forkLiftSelector[0]){
		if(elementInViewport(forkLiftSelector[0])){
			if(!forkLiftSelector[0].classList.contains('fork-lift-animated')){
				console.log('adding class');
				forkLiftSelector[0].className += ' fork-lift-animated';
			}
		}
	}
}


// document ready
(function() {
	window.onscroll = function() {checkOnScrollElements()};
})();

jQuery('.navbar-toggler').click(function(){
	if(jQuery(this).hasClass('collapsed')){
		setTimeout(function(){
			jQuery('.mobileMenuOverlay').css('display','block');
		},600);
	}else{
		jQuery('.mobileMenuOverlay').css('display','none');
	}
});

jQuery('.closen-responsive-nav').click(function(){
	closeResponsiveNav();
});

jQuery('.mobileMenuOverlay').click(function(){
	jQuery('.closen-responsive-nav').click();
});

function closeResponsiveNav(){
	jQuery('.mobileMenuOverlay').css('display','none');
}

jQuery(document).on('change','input[name="course_qty"]',function() {
    var id = jQuery(this).attr('data-id');
    var qty = jQuery(this).val();
    var url = jQuery('a.'+id+'_url').attr('href');
    url = url.substring(0, url.indexOf('quantity=')) + 'quantity='+qty;
    jQuery('a.'+id+'_url').attr('href',url);
});

var months = [];
var locations = [];
var parent = 0;
var myIds = [];
var dateSortState = 'descending';
var dateSortHTML = 'Date';

jQuery('input[name="filter_month"]').on('click',function() {
    dateSortState = 'descending';
    dateSortHTML = 'Date ◆';
    jQuery('.sort-date').html(dateSortHTML);
    jQuery('tbody.course_list').empty();
    jQuery('.loader').show();
    jQuery('input[name="filter_month"]').each(function() {
        if(jQuery(this).is(':checked')) {
            if(!months.includes(jQuery(this).val())) {
                months.push(jQuery(this).val());
            }
        }
        else {
            if(months.includes(jQuery(this).val())) {
                months.splice(months.indexOf(jQuery(this).val()),1);
            }
        }
    });
    jQuery('input[name="filter_location"]').each(function() {
        if(jQuery(this).is(':checked')) {
            if(!locations.includes(jQuery(this).val())) {
                locations.push(jQuery(this).val());
            }
        }
        else {
            if(locations.includes(jQuery(this).val())) {
                locations.splice(locations.indexOf(jQuery(this).val()),1);
            }
        }
    });
    parent = jQuery(this).attr('data-parent');
    console.log('months', months);
    console.log('locatons', locations);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {action: 'course_filter', months: months, locations: locations, parent: parent},
        //dataType: 'json',
        success: function(response) {
            //Do Successful Things
            response = JSON.parse(response);

            if(response.type == 'error') {
                jQuery('div.message').removeClass('success');
                jQuery('div.message').addClass('error');
            }
            else {
                jQuery('div.message').removeClass('error');
                jQuery('div.message').addClass('success');
            }

            if(response.data.length > 0) {
                jQuery('tbody.course_list').html(response.data);
            }
            jQuery('.loader').hide();
        },
        error: function(message) {
            //Do Unsuccessful Things
            var message = 'There was a problem creating your short link. Please try again later.';
            jQuery('div.message').empty();
            jQuery('div.message').html(message);
        }
    });
});

jQuery('input[name="filter_location"]').on('click',function() {
    dateSortState = 'descending';
    dateSortHTML = 'Date ◆';
    jQuery('.sort-date').html(dateSortHTML);
    jQuery('tbody.course_list').empty();
    jQuery('.loader').show();
    jQuery('input[name="filter_location"]').each(function() {
        if(jQuery(this).is(':checked')) {
            if(!locations.includes(jQuery(this).val())) {
                locations.push(jQuery(this).val());
            }
        }
        else {
            if(locations.includes(jQuery(this).val())) {
                locations.splice(locations.indexOf(jQuery(this).val()),1);
            }
        }
    });
    jQuery('input[name="filter_month"]').each(function() {
        if(jQuery(this).is(':checked')) {
            if(!months.includes(jQuery(this).val())) {
                months.push(jQuery(this).val());

            }
        }
        else {
            if(months.includes(jQuery(this).val())) {
                months.splice(months.indexOf(jQuery(this).val()),1);
            }
        }
    });
    parent = jQuery(this).attr('data-parent');
    console.log('months', months);
    console.log('locatons', locations);

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {action: 'course_filter', months: months, locations: locations, parent: parent},
        //dataType: 'json',
        success: function(response) {
            //Do Successful Things
            response = JSON.parse(response);

            if(response.type == 'error') {
                jQuery('div.message').removeClass('success');
                jQuery('div.message').addClass('error');
            }
            else {
                jQuery('div.message').removeClass('error');
                jQuery('div.message').addClass('success');
            }

            if(response.data.length > 0) {
                jQuery('tbody.course_list').html(response.data);
            }
            jQuery('.loader').hide();
        },
        error: function(message) {
            //Do Unsuccessful Things
            var message = 'There was a problem creating your short link. Please try again later.';
            jQuery('div.message').empty();
            jQuery('div.message').html(message);
        }
    });
});

jQuery('input[name="category_ids[]"]').on('click',function() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var order = url.searchParams.get("order");
    var newUrl = ''+url;

    jQuery('input[name="category_ids[]"]').each(function() {
        if(jQuery(this).is(':checked')) {
            if(!myIds.includes(jQuery(this).val())) {
                myIds.push(jQuery(this).val());
            }
        }
        else {
            if(myIds.includes(jQuery(this).val())) {
                myIds.splice(myIds.indexOf(jQuery(this).val()),1);
            }
        }
    });
    console.log(myIds);

    if(order != null) {
        console.log('order is not null');
        newUrl = newUrl.substring(0,newUrl.indexOf('?'));
        newUrl = newUrl + '?order='+order;
        var count = 0;
        jQuery(myIds).each(function(i,v) {
            newUrl += '&category_ids%5B%5D='+v
        });
    }
    else {
        console.log('order is null');
        var count = 0;
        jQuery(myIds).each(function(i,v) {
            if(count == 0) {
                newUrl += '?category_ids%5B%5D='+v
            }
            else {
                newUrl += '&category_ids%5B%5D='+v
            }
            count++;
        });
    }
    jQuery('.course_filter_form').attr('action',newUrl);
    console.log(newUrl);
});

jQuery('.sort-date').on('click',function() {
    var dateArray = [];
    if(dateSortState == 'descending') {
        dateSortState = 'ascending';
        dateSortHTML = 'Date ▲';
    }
    else {
        dateSortState = 'descending';
        dateSortHTML = 'Date ▼';
    }
    jQuery('tbody.course_list tr',document).each(function() {
        dateArray.push(Number(jQuery(this).attr('data-timestamp')));
    });
    dateArray = dateArray.sort();
    jQuery(dateArray).each(function(i,v) {
        if(dateSortState == 'ascending') {
            jQuery('.course-info-'+v).appendTo('tbody.course_list');
            jQuery('.sort-date').html(dateSortHTML);
        }
        else {
            jQuery('.course-info-'+v).prependTo('tbody.course_list');
            jQuery('.sort-date').html(dateSortHTML);
        }
    });
});
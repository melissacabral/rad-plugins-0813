jQuery.noConflict();
jQuery(document).ready(function($){	
	
	$(".slides").responsiveSlides({
		nav : true,
		speed : 500,
		maxwidth : 960  //set to the width of your images
	});

});
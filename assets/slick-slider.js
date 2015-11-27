jQuery(document).ready( function($){
	"use strict";


	$('.product-nav').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		dots: true,
		centerMode: true,
		focusOnSelect: true,
		centerPadding:0
	});


});
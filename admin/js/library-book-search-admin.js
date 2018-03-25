jQuery(document).ready(function( $ ) {
	'use strict';

	//Update the range value when the slider changes
	$(document).on('change', '#lbs-price-range', function(){
		var val = $(this).val();
		$( '#lbs-price-display' ).html( '&#36 ' + val );
		$( '#hidden-book-price' ).val( val );
	});

	//Decrease the price in range
	$(document).on('click', '.lbs-price-dec', function(){
		var price = parseInt( $('#lbs-price-range').val() );
		price = price - 1;
		$( '#lbs-price-display' ).html( '&#36 ' + price );
		$( '#hidden-book-price' ).val( price );
		$( '#lbs-price-range' ).val( price );
	});

	//Increase the price in range
	$(document).on('click', '.lbs-price-inc', function(){
		var price = parseInt( $('#lbs-price-range').val() );
		price = price + 1;
		$( '#lbs-price-display' ).html( '&#36 ' + price );
		$( '#hidden-book-price' ).val( price );
		$( '#lbs-price-range' ).val( price );
	});

	// Visualizing stars on hover
	$('#stars li').on('mouseover', function(){
		var onStar = parseInt($(this).data('value'), 10);
		// Now highlight all the stars that's not after the current hovered star
		$(this).parent().children('li.star').each(function(e){
			if (e < onStar) {
				$(this).addClass('hover');
			}
			else {
				$(this).removeClass('hover');
			}
		});
	}).on('mouseout', function(){
		$(this).parent().children('li.star').each(function(e){
			$(this).removeClass('hover');
		});
	});


	// Select the stars on click
	$('#stars li').on('click', function(){
		var onStar = parseInt($(this).data('value'), 10);
		var stars = $(this).parent().children('li.star');
		var i;
		for (i = 0; i < stars.length; i++) {
			$(stars[i]).removeClass('selected');
		}

		for (i = 0; i < onStar; i++) {
			$(stars[i]).addClass('selected');
		}
		var rating = parseInt($('#stars li.selected').last().data('value'), 10);
		$( '#hidden-book-rating' ).val( rating );
	});
});
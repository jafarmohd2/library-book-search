jQuery(document).ready(function( $ ) {
	'use strict';

	//Remove Datatable Search

	$('.lbs-list-results').DataTable({
		"searching": false
	});

	//Price Slider

	$( "#lbs-price" ).slider({
		range: true,
		min: 1,
		max: 3000,
		values: [ 200, 800 ],
		slide: function( event, ui ) {
			$( "#lbs-min-price" ).val( ui.values[0] );
			$( "#lbs-max-price" ).val( ui.values[1] );
			$( '#lbs-price-display' ).html( '('+ ui.values[0] +' - '+ ui.values[1] +')' );
		}
	});

	$('#lbs-publisher').selectize({
		placeholder		: "Publisher",
		plugins			: ['remove_button'],
	});

	$('#lbs-rating').selectize({
		placeholder		: "Rating",
		plugins			: ['remove_button'],
	});

	//Search the books
	$(document).on('click', '#lbs-search-books', function(){
		var btn = $(this);
		var btn_txt = btn.html();

		var book_name = $('#lbs-book-name').val();
		var author = $('#lbs-author').val();
		var publisher = $('#lbs-publisher').val();
		var rating = $('#lbs-rating').val();
		var min_price = $('#lbs-min-price').val();
		var max_price = $('#lbs-max-price').val();
		btn.html( '<i class="fa fa-refresh fa-spin"></i>  Searching...' );

		var data = {
			'action'		: 'lbs_search_books',
			'book_name'		: book_name,
			'author'		: author,
			'publisher'		: publisher,
			'rating'		: rating,
			'min_price'		: min_price,
			'max_price'		: max_price,
			'security'		: lbs_public_js_obj.security	
		}
		$.ajax({
			url: lbs_public_js_obj.ajaxurl,
			type: 'POST',
			data: data,
			dataType: "JSON",
			success: function( response ) {
				$('.lbs-results').html( response['data']['html'] );
				btn.html( btn_txt );
				$('.lbs-list-results').DataTable({
					"sDom": '<"top"l>rt<"bottom"ip><"clear">'
				});
			},
		});
	});
});
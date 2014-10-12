(function($){
	
	
	function initialize_field( $el ) {

		var container = $el;
		var star_list = $("ul", container);
		var star_list_items = $("li", star_list);
		var star_list_item_stars = $("i", star_list_items);
		var star_field = $("input#star-rating-value", container);
		var clear_value = $("a.clear-button", container);

		star_list_items
			.bind("click", function(){
				var star = $(this).index() + 1;
				star_field.val(star);
				$(this).siblings("li").andSelf().each(function(ind){
					if( ind + 1 <= star ){
						$("i", this).removeClass('fa-star-o').addClass('fa-star');
					}else{
						$("i", this).removeClass('fa-star').addClass('fa-star-o');
					}
				});
			});

		clear_value
			.bind("click", function(){
				star_list_item_stars.removeClass('fa-star').addClass('fa-star-o');
				star_field.val(0);
				return false;
			});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'star_rating'
			acf.get_fields({ type : 'star_rating'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).live('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="star_rating"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);

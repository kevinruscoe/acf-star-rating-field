(function($){
	
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize_field( $field ) {

		var input = $("input", $field);
		var max_stars = input.data('max_stars');
		var allow_half = input.data('allow_half');
		
		input.hide();


		input.after("<div class='acf-star-field-ui'></div>");

		$(".acf-star-field-ui").each(function() {
			var fieldUi = $(this);
			var classList = ['acf-star-field-ui__button'];
			if (allow_half) {
				classList.push('acf-star-field-ui__button--allow-half');
			}

			for (var i = 1; i <= max_stars; i++) {
				$(this).append(
					"<button type='button' class='" + classList.join(" ") + 
					"' data-value='" + 
					i + 
					"'>"
				);
			}

			$(".acf-star-field-ui__button", fieldUi).on("mousemove", function(e) {
				
				var value = $(this).data('value');

				$(".acf-star-field-ui__button", fieldUi)
					.removeClass("acf-star-field-ui__button--half acf-star-field-ui__button--full");
				
				if (allow_half) {
					if ((e.pageX - $(this).offset().left) < $(this).width() / 2) {
						value -= 0.5;

						$(this).addClass("acf-star-field-ui__button--half");
					}

					$(this).addClass("acf-star-field-ui__button--full");
				} else {
					$(this).addClass("acf-star-field-ui__button--full");
				}

				$(this).prevAll().addClass("acf-star-field-ui__button--full");

			});
		});
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=star_rating', initialize_field);
		acf.add_action('append_field/type=star_rating', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="star_rating"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

})(jQuery);

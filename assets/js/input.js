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
		
		input.after(
			$("<div>", {
				class: 'acf-star-field-ui'
			})
		).hide();

		$(".acf-star-field-ui").each(function() {

			var ui = $(this);
			var starClassList = ['acf-star-field-ui__star-button'];

			if (allow_half) {
				starClassList.push('acf-star-field-ui__star-button--allow-half');
			}

			// Add stars
			for (var i = 1; i <= max_stars; i++) {
				$(this).append(
					$("<button>", {
						type: 'button',
						class: starClassList.join(" "),
						'data-value': i
					})
				);
			}

			var stars = $(".acf-star-field-ui__star-button", ui);

			// add reset
			$(this).append(
				$("<button>", {
					type: 'button',
					class: 'acf-star-field-ui__clear-button',
				}).text("Clear")
			);

			var reset_button = $(".acf-star-field-ui__clear-button", ui);

			reset_button.on("click", function(e) {
				stars.removeClass("acf-star-field-ui__star-button--half acf-star-field-ui__star-button--full");
				input.val(0);
			});

			if (input.val() > 0) {

				var normalized_value = input.val().includes(".") ? parseFloat(input.val()) + 0.5 : input.val();

				var max_el = $(".acf-star-field-ui__star-button[data-value='" + normalized_value + "']", ui);

				max_el.addClass("acf-star-field-ui__star-button--full");
				max_el.prevAll().addClass("acf-star-field-ui__star-button--full");

				if (input.val().includes(".")) {
					max_el
						.removeClass("acf-star-field-ui__star-button--full")
						.addClass("acf-star-field-ui__star-button--half");
				}

			}

			stars.on("mousemove", function(e) {
				
				var value = $(this).data('value');

				stars.removeClass("acf-star-field-ui__star-button--half acf-star-field-ui__star-button--full");
				
				if (allow_half && (e.pageX - $(this).offset().left) < $(this).width() / 2) { // left (half)
					value -= 0.5;
					$(this).addClass("acf-star-field-ui__star-button--half");
				} else {
					$(this).addClass("acf-star-field-ui__star-button--full");
				}

				$(this).prevAll().addClass("acf-star-field-ui__star-button--full");

				input.val(value);
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

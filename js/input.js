(function($){
	function initialiseField( $el ) {
		var container = $el;
		var starList = $("ul", container);
		var starListItems = $("li", starList);
		var starListItemStars = $("i", starListItems);
		var starField = $("input", container);
		var clearButton = $("a.clear-button", container);
		var allowHalf = (starField.data('allow-half') == 1);
		var emptyClass = window.starClasses[0];
		var halfClass = window.starClasses[1];
		var fullClass = window.starClasses[2];

		starListItems.bind("click", function(e){
			e.preventDefault();

			var starValue = $(this).index();
			starField.val(starValue + 1);
			
			if (allowHalf) {
				var width = $(this).innerWidth();
				var offset = $(this).offset(); 
				var leftSideClicked = (width / 2) > (e.pageX - offset.left);

				if (leftSideClicked) {
					starField.val(starField.val() - 0.5);
				}
			}

			clearActiveStarClassesFromList();

			starListItems.each(function(index){
				var icon = $('i', $(this));
				var starValue = starField.val();

				if (index < starValue) {
					icon.removeClass(emptyClass)
						.removeClass(halfClass)
						.addClass(fullClass);

					if (allowHalf && (index + .5 == starValue)) {
						icon.removeClass(fullClass);
						icon.addClass(halfClass);
					}
				}
			});

			starField.trigger("change");
		});

		clearButton.bind("click", function(e){
			e.preventDefault();

			clearActiveStarClassesFromList();

			starField.val(0);

			starField.trigger("change");
		});

		function clearActiveStarClassesFromList()
		{
			starListItemStars
				.removeClass(fullClass)
				.removeClass(halfClass)
				.addClass(emptyClass);
		}	
	}
	
	// Instantiate
	acf.add_action('ready append', function($el) {
		acf.get_fields({
			type: 'star_rating_field'
		}, $el).each(function(){
			initialiseField($(this));
		});
	});
})(jQuery);

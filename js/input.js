(function($){
	function initialiseField( $el ) {
		var container = $el;
		var starList = $("ul", container);
		var starListItems = $("li", starList);
		var starListItemStars = $("i", starListItems);
		var starField = $("input", container);
		var clearButton = $("a.clear-button", container);
		var allowHalf = (starField.data('allow-half') == 1);

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
					icon.removeClass('fa-star-o')
						.removeClass('fa-star-half-o')
						.addClass('fa-star');

					if (allowHalf && (index + .5 == starValue)) {
						icon.addClass('fa-star-half-o')
					}
				}
			});
		});

		clearButton.bind("click", function(e){
			e.preventDefault();

			clearActiveStarClassesFromList();

			starField.val(0);
		});

		function clearActiveStarClassesFromList()
		{
			starListItemStars
				.removeClass('fa-star')
				.removeClass('fa-star-half-o')
				.addClass('fa-star-o');
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

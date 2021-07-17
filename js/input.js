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

		starListItems.mouseenter(function ()
		{
			var starValue = $(this).index();
			starField.val(starValue + 1);

					starListItems.each(function(index){
						starListItems[index].children[0].setAttribute('clicked', 'no'); 
						var starValue = starField.val();
						if (index < starValue) {
							if($(this).children()[0].classList.contains('empty'))
							{
						starListItems[index].children[0].removeAttribute("class", emptyClass);
						starListItems[index].children[0].setAttribute("class", fullClass);
							}
						}
					});
			
		});

		starListItems.mouseleave(function ()
		{
			if($(this).children().attr('clicked') != 'yes')
			{
			for(let x = 0; x < starListItems.length;x++)
			{
				starListItems[x].children[0].removeAttribute("class", fullClass);
				starListItems[x].children[0].setAttribute("class", emptyClass);
			}
			}	
		});





		starListItems.bind("click", function(e){
			e.preventDefault();
			$(this).children().attr('clicked', 'yes'); 
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

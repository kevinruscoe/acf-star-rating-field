(function($){

    acf.add_action('change_field_object', function(field) {
        if (field.data.type == 'star_rating') {
            $(".acf-input input[type='number'][name*='max_stars']", field.$el)
                .prop(
                    "step", 
                    $(".acf-input input[type='checkbox'][name*='allow_half']", field.$el).prop("checked") ? 
                        "0.5" : 
                        "any"
            );
        }
    });

})(jQuery);

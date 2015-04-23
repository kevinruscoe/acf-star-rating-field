<?php

class acf_field_star_rating extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'star_rating';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Star Rating', 'acf-star_rating');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'content';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'max_stars'  => 5,
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('star_rating', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-star_rating'),
		);
		
				
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {

		acf_render_field_setting( $field, array(
			'label'			=> __('Maximum Rating','acf-star_rating'),
			'instructions'	=> __('Maximum number of stars','acf-star_rating'),
			'type'			=> 'number',
			'name'			=> 'max_stars'
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Return Type','acf-star_rating'),
			'instructions'	=> __('What should be returned?','acf-star_rating'),
			'type'			=> 'select',
			'layout' 		=> 'horizontal',
			'choices' 		=> array(
				'0' 	=> __('Number', 'num'),
				'1' 	=> __('List (unstyled)', 'list_u'),
				'2' 	=> __('List (fa-awesome)', 'list_fa'),
			),
			'name' 			=> 'return_type'
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		$dir = plugin_dir_url( __FILE__ );

		// register & include CSS
		
		
		$html = '
			<div class="field_type-star_rating">
				%s
			</div>
			<a href="#clear-stars" class="button button-small clear-button">Clear</a>
			<input type="hidden" id="star-rating" name="%s" value="%s">
		';
		
		print sprintf(
			$html, 
			$this->make_list( 
				$field['max_stars'], 
				$field['value'],
				'<li><i class="%s star-%d"></i></li>', 
				array('fa fa-star', 'fa fa-star-o')
			),
			esc_attr($field['name']), esc_attr($field['value'])
		);
		
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function input_admin_enqueue_scripts() {
		
		$dir = plugin_dir_url( __FILE__ );
		
		wp_enqueue_script( 'acf-input-star_rating', "{$dir}js/input.js" );
		wp_enqueue_style( 'font-awesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
		wp_enqueue_style( 'acf-input-star_rating', "{$dir}css/input.css" ); 
		
	}

	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	function load_value( $value, $post_id, $field ) {
		
		return floatval($value);
		
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
		
	function update_value( $value, $post_id, $field ) {
		
		return floatval( $value );
		
	}
	
		
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	
	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}

		switch( $field['return_type'] ){
			case 0: // num
				return floatval( $value );
				break;
			case 1:
				return $this->make_list( 
					$field['max_stars'], 
					$value,
					'<li class="%s">%d</li>', 
					array('empty', 'blank')
				);
				break;
			case 2:
			
				$dir = plugin_dir_url( __FILE__ );

				wp_enqueue_style( 'acf-input-star_rating', "{$dir}css/input.css" ); 
				wp_enqueue_style( 'font-awesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
				
				$html = '
					<div class="field_type-star_rating">
						%s
					</div>
				';
				
				return sprintf(
					$html, 
					$this->make_list( 
						$field['max_stars'], 
						$value,
						'<li><i class="%s star-%d"></i></li>', 
						array('fa fa-star', 'fa fa-star-o') 
					)
				);
				break;

		}

	}
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
		
	function validate_value( $valid, $value, $field, $input ){

		if( $value > $field['max_stars'] )
		{
			$valid = __('The value is too large!','acf-star_rating');
		}
		
		return $valid;
		
	}
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	function load_field( $field ) {
		
		return $field;
		
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	function update_field( $field ) {
		
		return $field;
		
	}
	
	/*
	* make_list()
	*
	* Create a HTML list
	*
	* @return $html (string)
	*/
	
	function make_list($max_stars, $current_star, $li, $classes )
	{
		
		$html = '<ul class="star-rating">';
		
		for( $index = 1; $index < $max_stars + 1; $index++ ):
			$class = ($index <= $current_star) ? $classes[0] : $classes[1];
			$html .= sprintf($li, $class, $index);
		endfor;
				
		$html .= "</ul>";
		
		return $html;
		
	}
	
}


// create field
new acf_field_star_rating();

?>

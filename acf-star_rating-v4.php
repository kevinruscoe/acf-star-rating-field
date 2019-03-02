<?php

class acf_field_star_rating extends acf_field {
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'star_rating';
		$this->label = __('Star Rating');
		$this->category = __("Content",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'max_stars'  => 5,
			'return_type' => 2,
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like below) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		$field = array_merge($this->defaults, $field);
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Maximum Rating",'acf-star_rating'); ?></label>
		<p class="description"><?php _e("Maximum number of stars",'acf-star_rating'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'		=>	'number',
			'name'		=>	'fields['.$key.'][max_stars]',
			'value'		=>	$field['max_stars'],
		));

		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Return Type",'acf-star_rating'); ?></label>
		<p class="description"><?php _e("What should be returned?",'acf-star_rating'); ?></p>
	</td>
	<td>
		<?php

		do_action('acf/create_field', array(
			'type'		=>	'select',
			'name'		=>	'fields['.$key.'][return_type]',
			'value'		=>	$field['return_type'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'0' 	=> __('Number', 'num'),
				'1' 	=> __('List (unstyled)', 'list_u'),
				'2' 	=> __('List (fa-awesome)', 'list_fa'),
			)
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		
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
	*  Use this action to add CSS + JavaScript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
		
		
		// register ACF scripts
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
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in the database
	*/
	
	function load_value( $value, $post_id, $field )
	{
		return floatval($value);
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		return floatval( $value );
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		return floatval( $value );
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
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
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// Note: This function can be removed if not used
		return $field;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// Note: This function can be removed if not used
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

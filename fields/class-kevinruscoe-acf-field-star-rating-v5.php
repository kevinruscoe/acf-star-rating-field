<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('kevinruscoe_acf_field_star_rating') ) :

class kevinruscoe_acf_field_star_rating extends acf_field
{
	/**
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
	
	function __construct( $settings )
	{
		/**
		 *  name (string) Single word, no spaces. Underscores allowed
		 */
		$this->name = 'star_rating';
		
		/**
		 *  label (string) Multiple words, can include spaces, visible when selecting a field type
		 */
		$this->label = __('Star Rating', 'acf-star-rating');

		/**
		 *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		 */

		$this->category = 'choice';
		
		/**
		 *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		 */
		$this->defaults = array(
			'max_stars' => 5,
			'return_type' => 0,
			'allow_half' => false
		);
		
		/**
		 *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		 *  var message = acf._e('star_rating', 'error');
		 */
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf-star-rating'),
		);
		
		/**
		 *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		 */
		$this->settings = $settings;
		
    	parent::__construct();
	}
	
	/**
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
		acf_render_field_setting($field, array(
            'label' => __('Maximum Rating', 'acf-star_rating_field'),
            'instructions' => __('Maximum number of stars', 'acf-star_rating_field'),
            'type' => 'number',
            'name' => 'max_stars'
        ));

        acf_render_field_setting($field, array(
            'label' => __('Return Type', 'acf-star_rating_field'),
            'instructions' => __('What should be returned?', 'acf-star_rating_field'),
            'type' => 'select',
            'layout' => 'horizontal',
            'choices' => array(
                '0'  => __('value', 'value'),
            ),
            'name' => 'return_type'
        ));

        acf_render_field_setting($field, array(
            'label' => __('Allow Half Rating', 'acf-star_rating_field'),
            'instructions' => __('Allow half step ratings?', 'acf-star_rating_field'),
            'type' => 'true_false',
            'name' => 'allow_half'
        ));
	}

	/**
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
		?>
		<input 
			data-allow_half="<?php print esc_attr($field['allow_half']) ?>" 
			data-max_stars="<?php print esc_attr($field['max_stars']) ?>" 
			type="text" 
			name="<?php echo esc_attr($field['name']) ?>" 
			value="<?php echo esc_attr($field['value']) ?>" 
		/>
		<?php
	}
	
	/**
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
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		// register & include JS
		wp_register_script('acf-star-rating-admin', "{$url}assets/js/admin.js", array('acf-input'), $version);
		wp_enqueue_script('acf-star-rating-admin');

		wp_register_script('acf-star-rating', "{$url}assets/js/input.js", array('acf-input'), $version);
		wp_enqueue_script('acf-star-rating');

		wp_register_style('acf-star-rating', "{$url}assets/css/input.css", array('acf-input'), $version);
		wp_enqueue_style('acf-star-rating');
	}

	/**
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
	
	/**
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
		return floatval($value);
	}
	
	/**
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
		
		// // Basic usage
		// if( $value < $field['custom_minimum_setting'] )
		// {
		// 	$valid = false;
		// }
		
		
		// // Advanced usage
		// if( $value < $field['custom_minimum_setting'] )
		// {
		// 	$valid = __('The value is too little!','acf-star-rating'),
		// }
		
		
		// return
		return $valid;

	}
}

// initialize
new kevinruscoe_acf_field_star_rating( $this->settings );

// class_exists check
endif;

?>
<?php

class StarRatingField extends acf_field
{
    /**
     *  __construct
     *
     *  This function will setup the field type data
     *
     *  @type  function
     *  @date  5/03/2014
     *  @since 5.0.0
     *
     *  @return void
     */
    public function __construct()
    {
        $this->name = 'star_rating_field';
        
        $this->label = __('Star Rating', 'acf-star_rating_field');
        
        $this->category = 'content';

        $this->defaults = array(
            'max_stars'  => 5,
        );
        
        $this->l10n = array(
            'error' => __('Error! Please enter a higher value', 'acf-star_rating_field'),
        );

        parent::__construct();
    }
    
    /**
     *  render_field_settings()
     *
     *  Create extra settings for your field. These are visible when editing a field
     *
     *  @type  action
     *  @since 3.6
     *  @date  23/01/13
     *
     *  @param $field (array) the $field being edited
     *
     *  @return void
     */
    public function render_field_settings($field)
    {
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
                '0'  => __('Number', 'num'),
                '1' => __('List (unstyled)', 'list_u'),
                '2' => __('List (fa-awesome)', 'list_fa'),
            ),
            'name' => 'return_type'
        ));

        acf_render_field_setting($field, array(
            'label' => __('Allow Half Rating', 'acf-star_rating_field'),
            'instructions' => __('Allow half ratings?', 'acf-star_rating_field'),
            'type' => 'true_false',
            'name' => 'allow_half'
        ));
    }
    
    /**
     *  render_field()
     *
     *  Create the HTML interface for your field
     *
     *  @type action
     *  @since 3.6
     *  @date 23/01/13
     *
     *  @param array $field the $field being rendered
     *
     *  @return string
     */
    public function render_field($field)
    {
        $dir = plugin_dir_url(__FILE__);
   
        $html = '
            <div class="field_type-star_rating_field">%s</div>
            <a href="#clear-stars" class="button button-small clear-button">%s</a>
            <input type="hidden" id="star-rating" data-allow-half="%s" name="%s" value="%s">
        ';
        
        print sprintf(
            $html,
            $this->make_list(
                $field['max_stars'],
                $field['value'],
                '<li><i class="%s star-%d"></i></li>',
                array('fa fa-star-o', 'fa fa-star-half-o', 'fa fa-star'),
                $field['allow_half']
            ),
            __('Clear', 'acf-star_rating_field'),
            $field['allow_half'],
            esc_attr($field['name']),
            esc_attr($field['value'])
        );
    }
    
    /**
     *  input_admin_enqueue_scripts()
     *
     *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
     *  Use this action to add CSS + JavaScript to assist your render_field() action.
     *
     *  @type    action (admin_enqueue_scripts)
     *  @since   3.6
     *  @date    23/01/13
     *
     *  @return void
     */
    public function input_admin_enqueue_scripts()
    {
        $dir = plugin_dir_url(__FILE__);
        
        wp_enqueue_script('acf-input-star_rating', "{$dir}js/input.js");
        wp_enqueue_style(
            'font-awesome',
            "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
        );
        wp_enqueue_style('acf-input-star_rating', "{$dir}css/input.css");
    }

    /**
     *  load_value()
     *
     *  This filter is applied to the $value after it is loaded from the db
     *
     *  @type    filter
     *  @since   3.6
     *  @date    23/01/13
     *
     *  @param mixed $value the value found in the database
     *  @param mixed $post_id the $post_id from which the value was loaded
     *  @param array $field the field array holding all the field options
     *
     *  @return float $value
     */
    public function load_value($value, $post_id, $field)
    {
        return floatval($value);
    }
    
    /**
     *  update_value()
     *
     *  This filter is applied to the $value before it is saved in the db
     *
     *  @type    filter
     *  @since   3.6
     *  @date    23/01/13
     *
     *  @param mixed $value the value found in the database
     *  @param mixed $post_id the $post_id from which the value was loaded
     *  @param array $field the field array holding all the field options
     *  @return float $value
     */
    public function update_value($value, $post_id, $field)
    {
        return floatval($value);
    }

    /**
     *  format_value()
     *
     *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
     *
     *  @type    filter
     *  @since   3.6
     *  @date    23/01/13
     *
     *  @param mixed $value the value which was loaded from the database
     *  @param mixed $post_id the $post_id from which the value was loaded
     *  @param array $field the field array holding all the field options
     *
     *  @return mixed $value the modified value
     */
    public function format_value($value, $post_id, $field)
    {
        if (empty($value)) {
            return $value;
        }

        switch ($field['return_type']) {
            case 0:
                return floatval($value);
                break;
            case 1:
                return $this->make_list(
                    $field['max_stars'],
                    $value,
                    '<li class="%s">%d</li>',
                    array('blank', 'half', 'full'),
                    $field['allow_half']
                );
                break;
            case 2:
                $dir = plugin_dir_url(__FILE__);

                wp_enqueue_style('acf-input-star_rating', "{$dir}css/input.css");
                wp_enqueue_style(
                    'font-awesome',
                    "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css"
                );
                
                $html = '<div class="field_type-star_rating_field">%s</div>';
                
                return sprintf(
                    $html,
                    $this->make_list(
                        $field['max_stars'],
                        $value,
                        '<li><i class="%s star-%d"></i></li>',
                        array('fa fa-star-o', 'fa fa-star-half-o', 'fa fa-star'),
                        $field['allow_half']
                    )
                );
                break;
        }
    }
    
    /**
     *  validate_value()
     *
     *  This filter is used to perform validation on the value prior to saving.
     *  All values are validated regardless of the field's required setting. This allows you to validate and return
     *  messages to the user if the value is not correct
     *
     *  @type    filter
     *  @date    11/02/2014
     *  @since   5.0.0
     *
     *  @param bool $valid validation status based on the value and the field's required setting
     *  @param mixed $value the $_POST value
     *  @param array $field the field array holding all the field options
     *  @param string $input the corresponding input name for $_POST value
     *  @return string $valid
     */
    public function validate_value($valid, $value, $field, $input)
    {
        if ($value > $field['max_stars']) {
            $valid = __('The value is too large!', 'acf-star_rating_field');
        }
        
        return $valid;
    }
    
    /**
     *  load_field()
     *
     *  This filter is applied to the $field after it is loaded from the database
     *
     *  @type    filter
     *  @date    23/01/2013
     *  @since   3.6.0
     *
     *  @param array $field the field array holding all the field options
     *  @return array $field
     */
    public function load_field($field)
    {
        return $field;
    }
    
    /**
     *  update_field()
     *
     *  This filter is applied to the $field before it is saved to the database
     *
     *  @type    filter
     *  @date    23/01/2013
     *  @since   3.6.0
     *
     *  @param array $field the field array holding all the field options
     *  @return array $field
     */
    public function update_field($field)
    {
        return $field;
    }
    
    /**
     * make_list()
     *
     * Create a HTML list
     *
     * @param int $maxStars
     * @param int $currentStart
     * @param string $li
     * @param array $classes
     * @return string $html
     */
    public function make_list($maxStars = 5, $currentStar = 0, $li = '', $classes = [], $allowHalf = false)
    {
        $html = '<ul class="star-rating">';
        
        for ($index = 1; $index <= $maxStars; $index++) {
            $class = $classes[0];

            if ($index <= $currentStar) {
                $class = $classes[2];
            }

            if ($allowHalf && ($index - .5 == $currentStar)) {
                $class = $classes[1];
            }

            $html .= sprintf($li, $class, $index);
        }
                
        $html .= "</ul>";
        
        return $html;
    }
}

<?php

/**
 * Plugin Name: Advanced Custom Fields: Star Rating Field
 * Plugin URI: https://github.com/kevinruscoe/acf-star-rating-field
 * Description: A simple Star Rating Field for Advanced Custom Fields.
 * Version: 1.0.2
 * Author: Kevin Ruscoe
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

load_plugin_textdomain('acf-star_rating_field', false, dirname(plugin_basename(__FILE__))."/lang/");

add_action('acf/include_field_types', function () {
    include_once('StarRatingField.php');

    new StarRatingField();
});

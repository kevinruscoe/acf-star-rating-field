<?php

/**
 * Plugin Name: Advanced Custom Fields: Star Rating
 * Plugin URI: https://github.com/kevdotbadger/acf-star-rating
 * Description: A simple star rating field for ACF.
 * Version: 1.0.1
 * Author: Kevin Ruscoe
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

load_plugin_textdomain('acf-star_rating', false, dirname(plugin_basename(__FILE__))."/lang/");

add_action('acf/include_field_types', function () {
    include_once('AcfStarRating5.php');

    new AcfStarRating5();
});

add_action('acf/register_fields', function () {
    include_once('acf-star_rating-v4.php');
});

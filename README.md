-----------------------

# ACF Star Rating Field

A simple star rating field for ACF.

-----------------------

## Compatibility

This ACF field type is compatible with:
* ACF 5

## Usage

### In the admin

Fields are presented like:

![alt text](http://i.imgur.com/177YpD1.png "Ohhhh, screenshot")

### In your site

The plugin simply provides a interactive star-rating field in the WP admin. The value returned is bog-standard `int`. The reason for this is you may want to rate your things visually different on your website (i.e. giving something 3/5 pies, rather than 3/5 stars). So just write a simple loop to display, like:

```
$rating = get_field('rating');

for ($i = 0; $i < $rating; $i++) {
    print "<img src='rotating-pie.gif'>";
}

```

## Installation

1. Download the repo and move it into your `wp-content/plugins` folder
2. Activate the Star Rating plugin via the plugins admin page
3. Create a new field via ACF and select the Star Rating type

## PR very much welcome!
Bugs fixes are very much welcome. If you have a feature request, please open an issue before writing your code!
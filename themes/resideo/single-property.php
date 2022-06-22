<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$property_layout_settings = get_option('resideo_property_layout_settings');
$property_layout = isset($property_layout_settings['resideo_property_layout_field']) ? $property_layout_settings['resideo_property_layout_field'] : 'd1';

get_template_part('templates/single_property_' . $property_layout);

if ($property_layout != 'd4' || ($property_layout == 'd4' && !wp_script_is('gmaps', 'enqueued'))) {
    get_footer();
} ?>
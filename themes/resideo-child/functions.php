<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

add_action('wp_enqueue_scripts', 'resideo_enqueue_styles');
function resideo_enqueue_styles() {
    $parenthandle = 'resideo-style';
    $theme = wp_get_theme();

    wp_enqueue_style($parenthandle, get_template_directory_uri() . '/style.css', 
        array(
            'jquery-ui',
            'fileinput',
            'base-font',
            'font-awesome',
            'bootstrap',
            'datepicker',
            'owl-carousel',
            'owl-theme',
            'photoswipe',
            'photoswipe-skin'
        ), 
        $theme->parent()->get('Version')
    );

    wp_enqueue_style('child-style', get_stylesheet_uri(),
        array($parenthandle),
        $theme->get('Version')
    );
}


add_action('wp_enqueue_scripts', 'resideo_enqueue_scripts');

function resideo_enqueue_scripts() {
    $parenthandle = "pxp-main";
    $theme = wp_get_theme();
    wp_enqueue_script('child-main-script', get_stylesheet_directory_uri(). '/js/child-main.js',
    array('jquery'),
    $theme->get('Version'),
    true
    );
}


// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
       return $data;
    }
  
    $filetype = wp_check_filetype( $filename, $mimes );
  
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
  
  }, 10, 4 );
  
  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
  
  function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
  add_action( 'admin_head', 'fix_svg' );


  


?>
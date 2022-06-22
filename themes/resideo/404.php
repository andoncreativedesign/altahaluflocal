<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
?>

<div class="pxp-content">
    <div class="pxp-content-wrapper mt-100">
        <div class="container text-center">
            <img class="pxp-404-img" src="<?php echo esc_url(RESIDEO_LOCATION . '/images/compass.png'); ?>">
            <div class="mt-3 mt-md-4">
                <h1 class="pxp-page-header"><?php esc_html_e("This page is off the map", 'resideo'); ?></h1>
            </div>
            <div class="mt-3 mt-md-4">
                <p class="pxp-text-light"><?php esc_html_e("We can't seem to find the page you're looking for.", 'resideo'); ?></p>
            </div>
            <div class="mt-3 mt-md-4">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-4 pxp-animate"><?php esc_html_e('Go Home', 'resideo'); ?></a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

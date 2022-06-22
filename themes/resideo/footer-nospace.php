<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

$general_settings = get_option('resideo_general_settings');
$copyright        = isset($general_settings['resideo_copyright_field']) ? $general_settings['resideo_copyright_field'] : ''; ?>

    <div class="pxp-footer">
        <div class="container pt-100 pb-100"><?php get_sidebar('footer'); ?><?php if ($copyright != '') { ?>
            <div class="pxp-footer-bottom mt-4 mt-md-5">
                <div class="pxp-footer-copyright"><?php echo esc_html($copyright); ?></div>
            </div><?php } ?></div>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
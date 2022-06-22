<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

$general_settings = get_option('resideo_general_settings');
$copyright        = isset($general_settings['resideo_copyright_field']) ? $general_settings['resideo_copyright_field'] : ''; ?>

            <div class="pxp-footer pxp-content-side-wrapper"><?php if($copyright != '') { ?>
                <div class="pxp-footer-bottom">
                    <div class="pxp-footer-copyright"><?php echo esc_html($copyright); ?></div>
                </div><?php } ?></div>
        </div>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
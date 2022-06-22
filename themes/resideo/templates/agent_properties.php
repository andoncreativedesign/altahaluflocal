<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

$orig_post = $post;

global $paged;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$appearance_settings = get_option('resideo_appearance_settings');
$properties_per_page = isset($appearance_settings['resideo_properties_per_page_agent_field']) ? $appearance_settings['resideo_properties_per_page_agent_field'] : 9;

$args = array(
    'posts_per_page' => intval($properties_per_page),
    'paged' => $paged,
    'post_type' => 'property',
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => 'property_agent',
            'value' => $orig_post->ID
        )
    )
);

$my_query = new wp_query($args);

if ($my_query->have_posts()) {
    $resideo_general_settings = get_option('resideo_general_settings');
    $unit                     = isset($resideo_general_settings['resideo_unit_field']) ? $resideo_general_settings['resideo_unit_field'] : '';
    $beds_label               = isset($resideo_general_settings['resideo_beds_label_field']) ? $resideo_general_settings['resideo_beds_label_field'] : 'BD';
    $baths_label              = isset($resideo_general_settings['resideo_baths_label_field']) ? $resideo_general_settings['resideo_baths_label_field'] : 'BA';
    $currency                 = isset($resideo_general_settings['resideo_currency_symbol_field']) ? $resideo_general_settings['resideo_currency_symbol_field'] : '';
    $currency_pos             = isset($resideo_general_settings['resideo_currency_symbol_pos_field']) ? $resideo_general_settings['resideo_currency_symbol_pos_field'] : '';
    $locale                   = isset($resideo_general_settings['resideo_locale_field']) ? $resideo_general_settings['resideo_locale_field'] : '';
    $decimals                 = isset($resideo_general_settings['resideo_decimals_field']) ? $resideo_general_settings['resideo_decimals_field'] : '';
    setlocale(LC_MONETARY, $locale); ?>

    <h2 class="pxp-section-h2 mt-100"><?php esc_html_e('Listings by', 'resideo'); ?> <?php echo get_the_title(); ?></h2>

    <div class="row mt-4 mt-md-5">
        <?php while ($my_query->have_posts()) {
            $my_query->the_post();

            $s_id = get_the_ID();
            $s_link = get_permalink($s_id);
            $s_title = get_the_title($s_id);

            $s_gallery = get_post_meta($s_id, 'property_gallery', true);
            $s_photos  = explode(',', $s_gallery);
            $first_photo = wp_get_attachment_image_src($s_photos[0], 'pxp-gallery');
            $s_photo = ($first_photo !== false) ? $first_photo[0] : RESIDEO_LOCATION . '/images/ph-gallery.jpg';

            $s_price       = get_post_meta($s_id, 'property_price', true);
            $s_price_label = get_post_meta($s_id, 'property_price_label', true);
            if (is_numeric($s_price)) {
                if ($decimals == 1) {
                    $s_price = money_format('%!i', $s_price);
                } else {
                    $s_price = money_format('%!.0i', $s_price);
                }
                $currency_val = $currency;
            } else {
                $s_price_label = '';
                $currency_val = '';
            }

            $s_beds  = get_post_meta($s_id, 'property_beds', true);
            $s_baths = get_post_meta($s_id, 'property_baths', true);
            $s_size  = get_post_meta($s_id, 'property_size', true); ?>

            <div class="col-sm-12 col-md-6 col-lg-4">
                <a href="<?php echo esc_url($s_link); ?>" class="pxp-prop-card-1 rounded-lg mb-4">
                    <div class="pxp-prop-card-1-fig pxp-cover" style="background-image: url(<?php echo esc_url($s_photo); ?>);"></div>
                    <div class="pxp-prop-card-1-gradient pxp-animate"></div>
                    <div class="pxp-prop-card-1-details">
                        <div class="pxp-prop-card-1-details-title"><?php echo esc_html($s_title); ?></div>
                        <div class="pxp-prop-card-1-details-price">
                            <?php if ($currency_pos == 'before') {
                                echo esc_html($currency_val) . esc_html($s_price) . ' <span>' . esc_html($s_price_label) . '</span>';
                            } else {
                                echo esc_html($s_price) . esc_html($currency_val) . ' <span>' . esc_html($s_price_label) . '</span>';
                            } ?>
                        </div>
                        <div class="pxp-prop-card-1-details-features text-uppercase">
                            <?php if ($s_beds != '') {
                                echo esc_html($s_beds) . ' ' . esc_html($beds_label) . '<span>|</span>';
                            }
                            if ($s_baths != '') {
                                echo esc_html($s_baths) . ' ' . esc_html($baths_label) . '<span>|</span>';
                            }
                            if ($s_size != '') {
                                echo esc_html($s_size) . ' ' . esc_html($unit);
                            } ?>
                        </div>
                    </div>
                    <div class="pxp-prop-card-1-details-cta text-uppercase"><?php esc_html_e('View Details', 'resideo'); ?></div>
                </a>
            </div>
        <?php } ?>
    </div>

    <?php resideo_pagination($my_query->max_num_pages);
}

$post = $orig_post;
wp_reset_postdata(); ?>
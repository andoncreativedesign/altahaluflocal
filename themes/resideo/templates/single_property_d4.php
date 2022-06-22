<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

while (have_posts()) : the_post();
    $prop_id = get_the_ID();
    $user = wp_get_current_user();

    $appearance_settings = get_option('resideo_appearance_settings');
    $layout_settings = get_option('resideo_property_layout_settings');

    $fields_settings   = get_option('resideo_prop_fields_settings');
    $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';
    $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
    $neighborhoods     = get_option('resideo_neighborhoods_settings');
    $cities            = get_option('resideo_cities_settings');

    $address_arr  = array();
    $address      = '';
    $street_no    = get_post_meta($prop_id, 'street_number', true);
    $street       = get_post_meta($prop_id, 'route', true);
    $neighborhood = get_post_meta($prop_id, 'neighborhood', true);
    $city         = get_post_meta($prop_id, 'locality', true);
    $state        = get_post_meta($prop_id, 'administrative_area_level_1', true);
    $zip          = get_post_meta($prop_id, 'postal_code', true);

    $neighborhood_value = resideo_get_field_value($neighborhood_type, $neighborhood, $neighborhoods);
    $city_value         = resideo_get_field_value($city_type, $city, $cities);

    $address_settings = get_option('resideo_address_settings');

    if (is_array($address_settings)) {
        uasort($address_settings, "resideo_compare_position");

        $address_default = array(
            'street_number' => $street_no,
            'street'        => $street,
            'neighborhood'  => $neighborhood_value,
            'city'          => $city_value,
            'state'         => $state,
            'zip'           => $zip
        );

        foreach ($address_settings as $key => $value) {
            if ($address_default[$key] != '') {
                array_push($address_arr, $address_default[$key]);
            }
        }
    } else {
        if ($street_no != '') array_push($address_arr, $street_no);
        if ($street != '') array_push($address_arr, $street);
        if ($neighborhood_value != '') array_push($address_arr, $neighborhood_value);
        if ($city_value != '') array_push($address_arr, $city_value);
        if ($state != '') array_push($address_arr, $state);
        if ($zip != '') array_push($address_arr, $zip);
    }

    if (count($address_arr) > 0) $address = implode(', ', $address_arr);

    $general_settings = get_option('resideo_general_settings');
    $unit             = isset($general_settings['resideo_unit_field']) ? $general_settings['resideo_unit_field'] : '';
    $currency         = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
    $beds_label       = isset($general_settings['resideo_beds_label_field']) ? $general_settings['resideo_beds_label_field'] : 'BD';
    $baths_label      = isset($general_settings['resideo_baths_label_field']) ? $general_settings['resideo_baths_label_field'] : 'BA';
    $currency_pos     = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
    $locale           = isset($general_settings['resideo_locale_field']) ? $general_settings['resideo_locale_field'] : '';
    $decimals         = isset($general_settings['resideo_decimals_field']) ? $general_settings['resideo_decimals_field'] : '';
    setlocale(LC_MONETARY, $locale);

    $price       = get_post_meta($prop_id, 'property_price', true);
    $price_label = get_post_meta($prop_id, 'property_price_label', true);

    $taxes = get_post_meta($prop_id, 'property_taxes', true);
    $hoa_dues = get_post_meta($prop_id, 'property_hoa_dues', true);

    if (!is_numeric($taxes)) {
        $taxes = 0;
    }

    if (!is_numeric($hoa_dues)) {
        $hoa_dues = 0;
    }

    if (is_numeric($price)) {
        if ($decimals == '1') {
            $price = money_format('%!i', $price);
        } else {
            $price = money_format('%!.0i', $price);
        }
    } else {
        $price_label = '';
        $currency = '';
    }

    $beds  = get_post_meta($prop_id, 'property_beds', true);
    $baths = get_post_meta($prop_id, 'property_baths', true);
    $size  = get_post_meta($prop_id, 'property_size', true);

    $gallery = get_post_meta($prop_id, 'property_gallery', true);
    $photos  = explode(',', $gallery);

    $floor_plans = get_post_meta($prop_id, 'property_floor_plans', true);

    $status = wp_get_post_terms($prop_id, 'property_status');
    $type   = wp_get_post_terms($prop_id, 'property_type');

    $custom_fields_settings = get_option('resideo_fields_settings');

    $overview = get_the_content();

    $amenities_settings = get_option('resideo_amenities_settings');
    $amenities_count = 0;

    if (is_array($amenities_settings) && count($amenities_settings) > 0) {
        foreach ($amenities_settings as $key => $value) {
            if (get_post_meta($prop_id, $key, true) == 1) {
                $amenities_count++;
            }
        }
    } 

    $video = get_post_meta($prop_id, 'property_video', true);
    $virtual_tour = get_post_meta($prop_id, 'property_virtual_tour', true);

    $lat = get_post_meta($prop_id, 'property_lat', true);
    $lng = get_post_meta($prop_id, 'property_lng', true);

    $calculator = get_post_meta($prop_id, 'property_calc', true);

    $agent_id = get_post_meta($prop_id, 'property_agent', true);
    $agent    = ($agent_id != '') ? get_post($agent_id) : '';

    $show_print = isset($general_settings['resideo_show_print_property_field']) ? $general_settings['resideo_show_print_property_field'] : '';
    $show_report = isset($general_settings['resideo_show_report_property_field']) ? $general_settings['resideo_show_report_property_field'] : ''; 

    $map_position = isset($layout_settings['resideo_property_layout_map_position_field']) ? $layout_settings['resideo_property_layout_map_position_field'] : 'left';
    $no_map = false;
    $content_class = 'container';
    $wrapper_class = '';
    $details_class = '';
    $gallery_class = '';
    $details_column_class = 'col-md-7 col-lg-12 col-xxl-7';
    $agent_column_class = 'col-md-5 col-lg-12 col-xxl-5';
    $agent_section_class = 'mt-5 mt-md-0 mt-lg-5 mt-xl-5 mt-xxl-0';

    if (!wp_script_is('gmaps', 'enqueued')) {
        $map_position = 'no_map';
    }

    switch ($map_position) {
        case 'left':
            $map_class = 'pxp-map-side pxp-map-left pxp-half';
            $list_class = 'pxp-content-side pxp-content-right pxp-half';
            $content_class = 'pxp-full-height';
            $wrapper_class = 'is-single-property';
        break;
        case 'right':
            $map_class = 'pxp-map-side pxp-map-right pxp-half';
            $list_class = 'pxp-content-side pxp-content-left pxp-half';
            $content_class = 'pxp-full-height';
            $wrapper_class = 'is-single-property';
        break;
        case 'no_map':
            $no_map = true;
            $list_class = 'pxp-no-map';
            $wrapper_class = 'mt-100';
            $details_class = 'pxp-single-property-side-no-map mt-100';
            $gallery_class = 'rounded-lg';
            $details_column_class = 'col-lg-7 col-xl-8';
            $agent_column_class = 'col-lg-5 col-xl-4';
            $agent_section_class = 'mt-5 mt-lg-0';
        break;
        default:
            $map_class = 'pxp-map-side pxp-map-left pxp-half';
            $list_class = 'pxp-content-side pxp-content-right pxp-half';
            $content_class = 'pxp-full-height';
            $wrapper_class = 'is-single-property';
        break;
    }

    $sections_settings = isset($layout_settings['resideo_property_layout_order_field']) ? $layout_settings['resideo_property_layout_order_field'] : '';
    $sections = array();
    if (is_array($sections_settings)) {
        uasort($sections_settings, "resideo_compare_position");

        foreach ($sections_settings as $key => $value) {
            $sections[$key] = $sections_settings[$key];
        }
    } else {
        $sections = array(
            'key_details' => array(
                'name' => __('Key Details', 'resideo'),
                'position' => 0
            ),
            'overview' => array(
                'name' => __('Overview', 'resideo'),
                'position' => 1
            ),
            'amenities' => array(
                'name' => __('Amenities', 'resideo'),
                'position' => 2
            ),
            'video' => array(
                'name' => __('Video', 'resideo'),
                'position' => 3
            ),
            'virtual_tour' => array(
                'name' => __('Virtual Tour', 'resideo'),
                'position' => 4
            ),
            'floor_plans' => array(
                'name' => __('Floor Plans', 'resideo'),
                'position' => 5
            ),
            'explore_area' => array(
                'name' => __('Explore the Area', 'resideo'),
                'position' => 6
            ),
            'payment_calculator' => array(
                'name' => __('Payment Calculator', 'resideo'),
                'position' => 7
            )
        );
    }

    $dropdownClass = 'dropdown-menu-left';
    if (is_rtl()) {
        $dropdownClass = 'dropdown-menu-right';
    } ?>

    <input type="hidden" name="single_id" id="single_id" value="<?php echo esc_attr($prop_id); ?>" />
    <input type="hidden" name="lat" id="lat" value="<?php echo esc_attr($lat); ?>" />
    <input type="hidden" name="lng" id="lng" value="<?php echo esc_attr($lng); ?>" />
    <input type="hidden" name="taxes" id="taxes" value="<?php echo esc_attr($taxes); ?>" />
    <input type="hidden" name="hoa_dues" id="hoa_dues" value="<?php echo esc_attr($hoa_dues); ?>" />

    <div class="pxp-content <?php echo esc_attr($content_class); ?>">
        <?php if ($no_map === false) { ?>
            <div class="<?php echo esc_attr($map_class); ?>">
                <div id="pxp-sp-map" class="is-d4-layout">
                    <div class="pxp-map-placeholder"><img src="<?php print esc_url(RESIDEO_PLUGIN_PATH . 'images/loader-dark.svg'); ?>" class="pxp-loader" alt="..."><br><?php esc_html_e('Loading property', 'resideo'); ?></div>
                </div>
                <a href="javascript:void(0);" class="pxp-list-toggle pxp-is-single"><span class="fa fa-list"></span> <?php esc_html_e('Show details', 'resideo'); ?></a>
            </div>
        <?php } ?>

        <div class="<?php echo esc_attr($list_class); ?>">
            <div class="pxp-content-side-wrapper <?php echo esc_attr($wrapper_class); ?>">
                <div id="pxp-single-property-gallery-d4" class="pxp-single-property-gallery-d4 carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner pxp-single-property-gallery-d4-inner <?php echo esc_attr($gallery_class); ?>" role="listbox" itemscope itemtype="http://schema.org/ImageGallery">
                        <?php for ($i = 0; $i < count($photos); $i++) {
                            $p_photo_full = wp_get_attachment_image_src($photos[$i], 'pxp-full');
                            $p_photo_info = resideo_get_attachment($photos[$i]);
                            $active_class = $i == 0 ? 'active' : ''; ?>

                            <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" class="carousel-item <?php echo esc_attr($active_class); ?>">
                                <a href="<?php echo esc_url($p_photo_full[0]); ?>" itemprop="contentUrl" data-size="<?php echo esc_attr($p_photo_full[1]); ?>x<?php echo esc_attr($p_photo_full[2]); ?>" class="pxp-cover" style="background-image: url(<?php echo esc_url($p_photo_full[0]); ?>);"></a>
                                <figcaption itemprop="caption description"><?php echo esc_html($p_photo_info['caption']); ?></figcaption>
                            </figure>
                        <?php } ?>
                    </div>
                    <a class="pxp-carousel-control-prev pxp-animate" href="#pxp-single-property-gallery-d4" data-slide="prev">
                        <?php if (is_rtl()) { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                    <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                </g>
                            </svg>
                        <?php } else { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                    <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                </g>
                            </svg>
                        <?php } ?>
                    </a>
                    <a class="pxp-carousel-control-next pxp-animate" href="#pxp-single-property-gallery-d4" data-slide="next">
                        <?php if (is_rtl()) { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                                    <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                </g>
                            </svg>
                        <?php } else { ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                                <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                                    <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                    <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"/>
                                </g>
                            </svg>
                        <?php } ?>
                    </a>
                    <div class="pxp-single-property-gallery-d4-thumbs-container">
                        <div class="owl-carousel pxp-single-property-gallery-d4-thumbs">
                            <?php for ($j = 0; $j < count($photos); $j++) {
                                $p_photo_thmb = wp_get_attachment_image_src($photos[$j], 'pxp-thmb');
                                $active_thmb_class = $j == 0 ? 'pxp-active' : ''; ?>
    
                                <div>
                                    <div class="pxp-single-property-gallery-d4-thumbs-item pxp-cover rounded-lg <?php echo esc_attr($active_thmb_class); ?>" style="background-image: url(<?php echo esc_url($p_photo_thmb[0]); ?>);"></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($no_map === false) { ?>
                        <a role="button" class="pxp-map-toggle pxp-is-gallery"><span class="fa fa-map-o"></span></a>
                    <?php } ?>
                </div>

                <div class="pxp-single-property-d4-details <?php echo esc_attr($details_class); ?>">
                    <div class="row">
                        <div class="<?php echo esc_attr($details_column_class); ?>">
                            <h2 class="pxp-sp-top-title"><?php the_title(); ?></h2>
                            <p class="pxp-sp-top-address pxp-text-light"><?php echo esc_html($address); ?></p>
                            <div class="pxp-sp-top-price mt-3">
                                <?php if ($currency_pos == 'before') {
                                    echo esc_html($currency) . esc_html($price) . ' <span>' . esc_html($price_label) . '</span>';
                                } else {
                                    echo esc_html($price) . esc_html($currency) . ' <span>' . esc_html($price_label) . '</span>';
                                } ?>
                            </div>
                            <div class="pxp-sp-top-feat mt-3">
                                <?php if ($beds != '') { ?>
                                    <div><?php echo esc_html($beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                <?php }
                                if ($baths != '') { ?>
                                    <div><?php echo esc_html($baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                <?php }
                                if ($size != '') { ?>
                                    <div><?php echo esc_html($size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                <?php } ?>
                            </div>

                            <div class="pxp-sp-top-btns pxp-is-d4 mt-3">
                                <?php $wishlist = get_user_meta($user->ID, 'property_wishlist', true);
                                if (!empty($wishlist)) {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';

                                        if (in_array($prop_id, $wishlist) === false) {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                        } else {
                                            print '<a href="javascript:void(0);" class="pxp-sp-top-btn pxp-is-saved" id="pxp-sp-top-btn-save"><span class="fa fa-star"></span> ' . esc_html__('Saved', 'resideo') . '</a>';
                                        }
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                } else {
                                    if (is_user_logged_in()) {
                                        print '<input type="hidden" id="pxp-sp-top-uid" value="' . esc_attr($user->ID) . '">';
                                        print '<a href="javascript:void(0);" class="pxp-sp-top-btn" id="pxp-sp-top-btn-save"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    } else {
                                        print '<a href="javascript:void(0);" data-toggle="modal" data-target="#pxp-signin-modal" class="pxp-sp-top-btn"><span class="fa fa-star-o"></span> ' . esc_html__('Save', 'resideo') . '</a>';
                                    }
                                }
                                wp_nonce_field('wishlist_ajax_nonce', 'pxp-single-property-save-security', true); 
                                
                                if (function_exists('resideo_get_share_menu')) {
                                    if (is_rtl()) {
                                        resideo_get_share_menu($prop_id);
                                    } else {
                                        resideo_get_share_menu($prop_id, 'left');
                                    }
                                } ?>

                                <?php if ($show_print != '' || $show_report != '') { ?>
                                    <div class="dropdown">
                                        <a class="pxp-sp-top-btn" href="javascript:void(0);" role="button" id="moreOptionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-ellipsis-h mx-0"></span></a>
                                        <div class="dropdown-menu <?php echo esc_attr($dropdownClass); ?>" aria-labelledby="moreOptionsDropdown">
                                            <?php if ($show_print != '') { ?>
                                                <a class="dropdown-item" href="javascript:void(0);" id="pxp-print-property" data-id="<?php echo esc_attr($prop_id); ?>">
                                                    <span class="fa fa-print"></span> <?php esc_html_e('Print listing', 'resideo'); ?>
                                                </a>
                                                <?php wp_nonce_field('print_ajax_nonce', 'securityPrintProperty', true); ?>
                                            <?php }
                                            if ($show_report != '' && function_exists('resideo_get_report_property_modal')) {
                                                $report_modal_info          = array();
                                                $report_modal_info['link']  = get_permalink($prop_id);
                                                $report_modal_info['title'] = get_the_title(); ?>

                                                <a class="dropdown-item" href="#pxp-report-property-modal" data-toggle="modal" data-target="#pxp-report-property-modal">
                                                    <span class="fa fa-flag-o"></span> <?php esc_html_e('Report problem with listing', 'resideo'); ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="<?php echo esc_attr($agent_column_class); ?>">
                            <?php if ($agent_id != '') { 
                                $agent_avatar       = get_post_meta($agent_id, 'agent_avatar', true);
                                $agent_avatar_photo = wp_get_attachment_image_src($agent_avatar, 'pxp-thmb');

                                if ($agent_avatar_photo != '') {
                                    $a_photo = $agent_avatar_photo[0];
                                } else {
                                    $a_photo = RESIDEO_LOCATION . '/images/avatar-default.png';
                                }

                                $show_rating = isset($general_settings['resideo_agents_rating_field']) ? $general_settings['resideo_agents_rating_field'] : '';
                                $hide_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : '';

                                $agent_email = get_post_meta($agent_id, 'agent_email', true);
                                $agent_phone = get_post_meta($agent_id, 'agent_phone', true); ?>

                                <div class="pxp-single-property-section <?php echo esc_attr($agent_section_class); ?>">
                                    <div class="pxp-sp-agent d-flex">
                                        <a href="<?php echo esc_url(get_permalink($agent_id)); ?>" class="pxp-sp-agent-fig pxp-cover rounded-lg" style="background-image: url(<?php echo esc_attr($a_photo); ?>);"></a>
                                        <div class="pxp-sp-agent-info">
                                            <div class="pxp-sp-agent-info-name"><a href="<?php echo esc_url(get_permalink($agent_id)); ?>"><?php echo esc_attr($agent->post_title); ?></a></div>
                                            <?php if ($show_rating != '') {
                                                print resideo_display_agent_rating(resideo_get_agent_ratings($agent_id), false, 'pxp-sp-agent-info-rating');
                                            }

                                            if ($agent_email != '') { ?>
                                                <div class="pxp-sp-agent-info-email"><a href="mailto:<?php echo esc_attr($agent_email); ?>"><?php echo esc_html($agent_email); ?></a></div>
                                            <?php }

                                            if ($agent_phone != '') { 
                                                if ($hide_phone != '') { ?>
                                                    <div class="pxp-sp-agent-info-show-phone" data-phone="<?php echo esc_attr($agent_phone); ?>"><span class="fa fa-phone"></span> <span class="pxp-is-number"><?php esc_html_e('Show phone number', 'resideo'); ?></span></div>
                                                <?php } else { ?>
                                                    <div class="pxp-sp-agent-info-phone"><span class="fa fa-phone"></span> <?php echo esc_html($agent_phone); ?></div>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                    <?php if (function_exists('resideo_get_contact_agent_modal')) {
                                        $modal_info                   = array();
                                        $modal_info['link']           = get_permalink($prop_id);
                                        $modal_info['title']          = get_the_title();
                                        $modal_info['agent_email']    = $agent_email;
                                        $modal_info['agent_id']       = $agent_id;
                                        $modal_info['agent']          = $agent->post_title;
                                        $modal_info['user_id']        = '';
                                        $modal_info['user_email']     = '';
                                        $modal_info['user_firstname'] = '';
                                        $modal_info['user_lastname']  = '';

                                        if (is_user_logged_in()) {
                                            $user_meta                    = get_user_meta($user->ID);
                                            $modal_info['user_id']        = $user->ID;
                                            $modal_info['user_email']     = $user->user_email;
                                            $user_firstname               = $user_meta['first_name'];
                                            $user_lastname                = $user_meta['last_name'];
                                            $modal_info['user_firstname'] = $user_firstname[0];
                                            $modal_info['user_lastname']  = $user_lastname[0];
                                        }

                                        $cta_is_sticky = isset($appearance_settings['resideo_sticky_agent_cta_field']) ? $appearance_settings['resideo_sticky_agent_cta_field'] : false;
                                        $cta_sticky_class = $cta_is_sticky == '1' ? 'pxp-is-sticky' : ''; ?>

                                        <div class="pxp-sp-agent-btns mt-3 mt-md-4">
                                            <a href="#pxp-contact-agent" class="pxp-sp-agent-btn-main <?php echo esc_attr($cta_sticky_class); ?>" data-toggle="modal" data-target="#pxp-contact-agent"><span class="fa fa-envelope-o"></span><?php esc_html_e('Contact Agent', 'resideo'); ?></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php foreach ($sections as $key => $value) {
                        switch ($key) {
                            case 'key_details': ?>
                                <div class="pxp-single-property-section mt-4 mt-md-5">
                                    <h3><?php esc_html_e('Key Details', 'resideo'); ?></h3>
                                    <div class="row mt-3 mt-md-4">
                                        <?php if ($status) { ?>
                                            <div class="col-sm-6">
                                                <div class="pxp-sp-key-details-item">
                                                    <div class="pxp-sp-kd-item-label text-uppercase"><?php esc_html_e('Status', 'resideo'); ?></div>
                                                    <div class="pxp-sp-kd-item-value"><?php echo esc_html($status[0]->name); ?></div>
                                                </div>
                                            </div>
                                        <?php }

                                        if ($type) { ?>
                                            <div class="col-sm-6">
                                                <div class="pxp-sp-key-details-item">
                                                    <div class="pxp-sp-kd-item-label text-uppercase"><?php esc_html_e('Type', 'resideo'); ?></div>
                                                    <div class="pxp-sp-kd-item-value"><?php echo esc_html($type[0]->name); ?></div>
                                                </div>
                                            </div>
                                        <?php }

                                        if (is_array($custom_fields_settings)) {
                                            uasort($custom_fields_settings, "resideo_compare_position");

                                            foreach ($custom_fields_settings as $key => $value) {
                                                $cf_label = $value['label'];
                                                if (function_exists('icl_translate')) {
                                                    $cf_label = icl_translate('resideo', 'resideo_property_field_' . $value['label'], $value['label']);
                                                }

                                                $field_value = get_post_meta($prop_id, $key, true);

                                                if ($field_value != '') { ?>
                                                    <div class="col-sm-6">
                                                        <div class="pxp-sp-key-details-item">
                                                            <?php if ($value['type'] == 'list_field') {
                                                                $list = explode(',', $value['list']); ?>
                                                                <div class="pxp-sp-kd-item-label text-uppercase"><?php echo esc_html($cf_label); ?></div>
                                                                <div class="pxp-sp-kd-item-value"><?php echo esc_html($list[$field_value]); ?></div>
                                                            <?php } else { ?>
                                                                <div class="pxp-sp-kd-item-label text-uppercase"><?php echo esc_html($cf_label); ?></div>
                                                                <div class="pxp-sp-kd-item-value"><?php echo esc_html($field_value); ?></div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php }
                                            }
                                        } ?>
                                    </div>
                                </div>
                            <?php break;

                            case 'overview': 
                                if ($overview != '') { ?>
                                    <div class="pxp-single-property-section mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Overview', 'resideo'); ?></h3>
                                        <div class="mt-3 mt-md-4">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                <?php }
                            break;

                            case 'amenities': 
                                if ($amenities_count > 0) { ?>
                                    <div class="pxp-single-property-section mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Amenities', 'resideo'); ?></h3>
                                        <div class="row mt-3 mt-md-4">
                                            <?php if (is_array($amenities_settings) && count($amenities_settings) > 0) {
                                                uasort($amenities_settings, "resideo_compare_position");
            
                                                foreach ($amenities_settings as $key => $value) {
                                                    $am_label = $value['label'];
                                                    if (function_exists('icl_translate')) {
                                                        $am_label = icl_translate('resideo', 'resideo_property_amenity_' . $value['label'], $value['label']);
                                                    }
            
                                                    if (get_post_meta($prop_id, $key, true) == 1) { ?>
                                                        <div class="col-sm-6 col-lg-4">
                                                            <div class="pxp-sp-amenities-item"><span class="<?php echo esc_attr($value['icon']); ?>"></span> <?php echo esc_html($am_label); ?></div>
                                                        </div>
                                                    <?php }
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                <?php }
                            break;

                            case 'video': 
                                if ($video != '') {
                                    if (function_exists('resideo_get_property_video')) { ?>
                                        <div class="pxp-single-property-section mt-4 mt-md-5">
                                            <h3><?php esc_html_e('Video', 'resideo'); ?></h3>
                                            <div class="mt-3 mt-md-4">
                                                <?php resideo_get_property_video($video); ?>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            break;

                            case 'virtual_tour': 
                                if ($virtual_tour != '') {
                                    if (function_exists('resideo_get_property_virtual_tour')) { ?>
                                        <div class="pxp-single-property-section mt-4 mt-md-5">
                                            <h3><?php esc_html_e('Virtual Tour', 'resideo'); ?></h3>
                                            <div class="mt-3 mt-md-4">
                                                <?php resideo_get_property_virtual_tour($virtual_tour); ?>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            break;

                            case 'floor_plans': 
                                $floor_plans_list = array(); 

                                if ($floor_plans != '') {
                                    $floor_plans_data = json_decode(urldecode($floor_plans));

                                    if (isset($floor_plans_data)) {
                                        $floor_plans_list = $floor_plans_data->plans;
                                    }
                                } 

                                if (count($floor_plans_list) > 0) { ?>
                                    <div class="pxp-single-property-section mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Floor Plans', 'resideo'); ?></h3>
                                        <div class="accordion" id="pxpFloorPlans">
                                            <?php foreach ($floor_plans_list as $floor_plan) {
                                                $floor_plan_image = wp_get_attachment_image_src($floor_plan->image, 'pxp-full'); ?>

                                                <div class="pxp-sp-floor-plans-item">
                                                    <div class="pxp-sp-floor-plans-item-header" id="pxpSPFloorPlansItemHeader<?php echo esc_attr($floor_plan->image); ?>">
                                                        <div class="pxp-sp-floor-plans-item-trigger collapsed" data-toggle="collapse" data-target="#pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>" aria-expanded="true" aria-controls="pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="pxp-sp-floor-plans-item-title"><span class="fa fa-angle-down pxp-is-plus mr-3"></span><span class="fa fa-angle-up pxp-is-minus mr-3"></span><?php echo esc_html($floor_plan->title); ?></div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="pxp-sp-floor-plans-item-info">
                                                                        <?php if ($floor_plan->beds != '') { ?>
                                                                            <div class="d-inline-block mr-2"><?php echo esc_html($floor_plan->beds); ?> <span><?php echo esc_html($beds_label); ?></span></div>
                                                                        <?php } ?>
                                                                        <?php if ($floor_plan->baths != '') { ?>
                                                                            <div class="d-inline-block mr-2"><?php echo esc_html($floor_plan->baths); ?> <span><?php echo esc_html($baths_label); ?></span></div>
                                                                        <?php } ?>
                                                                        <?php if ($floor_plan->size != '') { ?>
                                                                            <div class="d-inline-block"><?php echo esc_html($floor_plan->size); ?> <span><?php echo esc_html($unit); ?></span></div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="pxpSPFloorPlansCollapse<?php echo esc_attr($floor_plan->image); ?>" class="collapse" aria-labelledby="pxpSPFloorPlansItemHeader<?php echo esc_attr($floor_plan->image); ?>" data-parent="#pxpFloorPlans">
                                                        <?php if ($floor_plan_image != '') { ?>
                                                            <a href="<?php echo esc_url($floor_plan_image[0]); ?>" target="_blank">
                                                                <img class="pxp-sp-floor-plans-item-image" src="<?php echo esc_url($floor_plan_image[0]); ?>" alt="<?php echo esc_attr($floor_plan->title); ?>">
                                                            </a>
                                                        <?php } ?>
                                                        <p class="mt-3"><?php echo esc_html($floor_plan->description); ?></p>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } 
                            break;

                            case 'payment_calculator': 
                                if ($calculator == '1') { ?>
                                    <div class="pxp-single-property-section mt-4 mt-md-5">
                                        <h3><?php esc_html_e('Payment Calculator', 'resideo'); ?></h3>
                                        <div class="pxp-calculator-view mt-3 mt-md-4">
                                            <div class="row">
                                                <div class="col-sm-12 col-lg-5 col-xl-4 align-self-center">
                                                    <div class="pxp-calculator-chart-container">
                                                        <canvas id="pxp-calculator-chart"></canvas>
                                                        <div class="pxp-calculator-chart-result">
                                                            <div class="pxp-calculator-chart-result-sum"></div>
                                                            <div class="pxp-calculator-chart-result-label"><?php esc_html_e('per month', 'resideo'); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-lg-7 col-xl-8 align-self-center mt-3 mt-lg-0">
                                                    <div class="pxp-calculator-data">
                                                        <div class="row justify-content-between">
                                                            <div class="col-8">
                                                                <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('Principal and Interest', 'resideo'); ?></div>
                                                            </div>
                                                            <div class="col-4 text-right">
                                                                <div class="pxp-calculator-data-sum" id="pxp-calculator-data-pi"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-calculator-data">
                                                        <div class="row justify-content-between">
                                                            <div class="col-8">
                                                                <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('Property Taxes', 'resideo'); ?></div>
                                                            </div>
                                                            <div class="col-4 text-right">
                                                                <div class="pxp-calculator-data-sum" id="pxp-calculator-data-pt"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pxp-calculator-data">
                                                        <div class="row justify-content-between">
                                                            <div class="col-8">
                                                                <div class="pxp-calculator-data-label"><span class="fa fa-minus"></span><?php esc_html_e('HOA Dues', 'resideo'); ?></div>
                                                            </div>
                                                            <div class="col-4 text-right">
                                                                <div class="pxp-calculator-data-sum" id="pxp-calculator-data-hd"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pxp-calculator-form mt-3 mt-md-4">
                                            <?php if ($currency_pos == 'before') {
                                                $taxes_value = $currency . money_format('%!.0i', $taxes);
                                                $hoa_dues_value = $currency . money_format('%!.0i', $hoa_dues);
                                                $price_value = $currency . $price;
                                            } else {
                                                $taxes_value = money_format('%!.0i', $taxes) . $currency;
                                                $hoa_dues_value = money_format('%!.0i', $hoa_dues) . $currency;
                                                $price_value = $price . $currency;
                                            } ?>
            
                                            <input type="hidden" id="pxp-calculator-form-property-taxes" value="<?php echo esc_attr($taxes_value); ?>">
                                            <input type="hidden" id="pxp-calculator-form-hoa-dues" value="<?php echo esc_attr($hoa_dues_value); ?>">
            
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="pxp-calculator-form-term"><?php esc_html_e('Term', 'resideo'); ?></label>
                                                        <select class="custom-select" id="pxp-calculator-form-term">
                                                            <option value="30">30 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                            <option value="25">25 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                            <option value="20">20 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                            <option value="15">15 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                            <option value="10">10 <?php esc_html_e('Years Fixed', 'resideo'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="pxp-calculator-form-interest"><?php esc_html_e('Interest', 'resideo'); ?></label>
                                                        <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-interest" data-type="percent" value="3.51%">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="pxp-calculator-form-price"><?php esc_html_e('Home Price', 'resideo'); ?></label>
                                                        <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-price" data-type="currency" value="<?php echo esc_attr($price_value); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="row">
                                                        <div class="col-7 col-sm-7 col-md-8">
                                                            <div class="form-group">
                                                                <label for="pxp-calculator-form-down-price"><?php esc_html_e('Down Payment', 'resideo'); ?></label>
                                                                <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-down-price" data-type="currency" value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-5 col-sm-5 col-md-4">
                                                            <div class="form-group">
                                                                <label for="pxp-calculator-form-down-percentage">&nbsp;</label>
                                                                <input type="text" class="form-control pxp-form-control-transform" id="pxp-calculator-form-down-percentage" data-type="percent" value="10%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            break;

                            default:
                                // Nothing to do here
                            break;
                        }
                    }

                    $show_similar = isset($appearance_settings['resideo_similar_field']) ? $appearance_settings['resideo_similar_field'] : false;
                    if ($show_similar) {
                        if (function_exists('resideo_get_similar_properties')) {
                            if ($no_map === true) {
                                resideo_get_similar_properties('full');
                            } else {
                                resideo_get_similar_properties('side');
                            }
                        }
                    }

                    if (comments_open() || get_comments_number()) {
                        if ($no_map === true) {
                            comments_template('/comments.php');
                        } else {
                            comments_template('/comments-property-d4.php');
                        }
                    } ?>
                </div>
            </div>

            <?php if (isset($modal_info)) { 
                resideo_get_contact_agent_modal($modal_info);
            }

            if (isset($report_modal_info)) {
                resideo_get_report_property_modal($report_modal_info);
            }
            
            if ($no_map === false) {
                get_footer('split');
            } ?>
        </div>
    </div>

    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>
        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>
                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div> 
                </div>
                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>

<?php 
endwhile;
?>
<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (!defined('RESIDEO_LOCATION')) {
    define('RESIDEO_LOCATION', get_template_directory_uri());
}

/**
 * Register required plugins
 */
require_once 'libs/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'resideo_register_required_plugins');

if(!function_exists('resideo_register_required_plugins')): 
    function resideo_register_required_plugins() {
        $plugins = array(
            array(
                'name'         => 'Resideo Plugin',
                'slug'         => 'resideo-plugin',
                'source'       => 'http://pixelprime.co/themes/resideo-wp/plugins/resideo-plugin-2-5-2/resideo-plugin.zip',
                'required'     => true,
                'version'      => '2.5.2',
                'external_url' => ''
            ),
        );

        $config = array(
            'id'           => 'resideo',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'has_notices'  => true,
            'dismissable'  => false,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',

            'strings'      => array(
                'page_title'                      => esc_html__('Install Required Plugins', 'resideo'),
                'menu_title'                      => esc_html__('Install Plugins', 'resideo'),
                'installing'                      => esc_html__('Installing Plugin: %s', 'resideo'),
                'updating'                        => esc_html__('Updating Plugin: %s', 'resideo'),
                'oops'                            => esc_html__('Something went wrong with the plugin API.', 'resideo'),
                'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'resideo'),
                'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'resideo'),
                'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'resideo'),
                'notice_ask_to_update_maybe'      => _n_noop('There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'resideo'),
                'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'resideo'),
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'resideo'),
                'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'resideo'),
                'update_link'                     => _n_noop('Begin updating plugin', 'Begin updating plugins', 'resideo'),
                'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'resideo'),
                'return'                          => esc_html__('Return to Required Plugins Installer', 'resideo'),
                'plugin_activated'                => esc_html__('Plugin activated successfully.', 'resideo'),
                'activated_successfully'          => esc_html__('The following plugin was activated successfully:', 'resideo'),
                'plugin_already_active'           => esc_html__('No action taken. Plugin %1$s was already active.', 'resideo'),
                'plugin_needs_higher_version'     => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'resideo'),
                'complete'                        => esc_html__('All plugins installed and activated successfully. %1$s', 'resideo'),
                'dismiss'                         => esc_html__('Dismiss this notice', 'resideo'),
                'notice_cannot_install_activate'  => esc_html__('There are one or more required or recommended plugins to install, update or activate.', 'resideo'),
                'contact_admin'                   => esc_html__('Please contact the administrator of this site for help.', 'resideo'),
                'nag_type'                        => 'updated',
            ),
        );

        tgmpa($plugins, $config);
    }
endif;

/**
 * Theme setup
 */
if (!function_exists('resideo_setup')):
    function resideo_setup() {
        if (function_exists('add_theme_support')) {
            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('custom-logo');
            add_theme_support('html5', array('style', 'script'));
            add_theme_support('responsive-embeds');
        }

        set_post_thumbnail_size(800, 600, true);
        add_image_size('pxp-thmb', 160, 160, true);
        add_image_size('pxp-icon', 200, 200, true);
        add_image_size('pxp-gallery', 800, 600, true);
        add_image_size('pxp-agent', 800, 800, true);
        add_image_size('pxp-full', 1920, 1280, true);

        if (!isset($content_width)) {
            $content_width = 1140;
        }

        load_theme_textdomain('resideo', RESIDEO_LOCATION . '/languages/');

        register_nav_menus(array(
            'primary' => esc_html__('Top primary menu', 'resideo'),
        ));
    }
endif;
add_action('after_setup_theme', 'resideo_setup');

/**
 * Load scripts
 */
if (!function_exists('resideo_load_scripts')): 
    function resideo_load_scripts() {
        global $paged;
        global $post;

        wp_enqueue_style('jquery-ui', RESIDEO_LOCATION . '/css/jquery-ui.css', array(), '1.11.0', 'all'); 
        wp_enqueue_style('fileinput', RESIDEO_LOCATION . '/css/fileinput.min.css', array(), '4.0', 'all'); 
        wp_enqueue_style('base-font', 'https://fonts.googleapis.com/css?family=Roboto:400,700,900', array(), '1.0', 'all');
        wp_enqueue_style('font-awesome', RESIDEO_LOCATION . '/css/font-awesome.min.css', array(), '4.7.0', 'all');
        wp_enqueue_style('bootstrap', RESIDEO_LOCATION . '/css/bootstrap.min.css', array(), '4.3.1', 'all');
        wp_enqueue_style('datepicker', RESIDEO_LOCATION . '/css/datepicker.css', array(), '1.0', 'all');
        wp_enqueue_style('owl-carousel', RESIDEO_LOCATION . '/css/owl.carousel.min.css', array(), '2.3.4', 'all');
        wp_enqueue_style('owl-theme', RESIDEO_LOCATION . '/css/owl.theme.default.min.css', array(), '2.3.4', 'all');
        wp_enqueue_style('photoswipe', RESIDEO_LOCATION . '/css/photoswipe.css', array(), '4.1.3', 'all');
        wp_enqueue_style('photoswipe-skin', RESIDEO_LOCATION . '/css/default-skin/default-skin.css', array(), '4.1.3', 'all');
        wp_enqueue_style('resideo-style', get_stylesheet_uri(), array(), '1.0', 'all');

        // RTL styles
        wp_style_add_data('resideo-style', 'rtl', 'replace');

        // Include dsIDXpress IDX Style only if plugin is active
        if (function_exists('dsidxpress_InitWidgets')) {
            wp_enqueue_style('resideo-dsidx', RESIDEO_LOCATION . '/css/idx.css', array(), '1.0', 'all');
        }

        wp_deregister_style('common');
        wp_deregister_style('forms');

        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        wp_enqueue_script('jquery-ui', RESIDEO_LOCATION . '/js/jquery-ui.min.js', array('jquery'), '1.11.4', true);
        wp_enqueue_script('popper', RESIDEO_LOCATION . '/js/popper.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('bootstrap', RESIDEO_LOCATION . '/js/bootstrap.min.js', array('jquery'), '4.3.1', true);
        wp_enqueue_script('markerclusterer',    RESIDEO_LOCATION . '/js/markerclusterer.js', array(), '2.0.8', true);
        wp_enqueue_script('datepicker', RESIDEO_LOCATION . '/js/bootstrap-datepicker.js', array(), '1.0', true);
        wp_enqueue_script('numeral', RESIDEO_LOCATION . '/js/numeral.min.js', array(), '2.0.6', true);

        $resideo_gmaps_settings = get_option('resideo_gmaps_settings', '');
        $gmaps_key              = isset($resideo_gmaps_settings['resideo_gmaps_key_field']) ? $resideo_gmaps_settings['resideo_gmaps_key_field'] : '';
        $gmaps_lat              = isset($resideo_gmaps_settings['resideo_gmaps_lat_field']) ? $resideo_gmaps_settings['resideo_gmaps_lat_field'] : 0;
        $gmaps_lng              = isset($resideo_gmaps_settings['resideo_gmaps_lng_field']) ? $resideo_gmaps_settings['resideo_gmaps_lng_field'] : 0;
        $gmaps_zoom             = isset($resideo_gmaps_settings['resideo_gmaps_zoom_field']) ? $resideo_gmaps_settings['resideo_gmaps_zoom_field'] : 13;
        $gmaps_style            = isset($resideo_gmaps_settings['resideo_gmaps_style_field']) ? $resideo_gmaps_settings['resideo_gmaps_style_field'] : '';
        $gmaps_poi              = isset($resideo_gmaps_settings['resideo_gmaps_poi_field']) ? $resideo_gmaps_settings['resideo_gmaps_poi_field'] : '';

        if ($gmaps_key != '') {
            wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . $gmaps_key . '&amp;libraries=geometry&amp;libraries=places', array('jquery'), false, true);
        }

        wp_enqueue_script('google-video',  'https://www.youtube.com/iframe_api', array(), false, true);

        wp_enqueue_script('fileinput', RESIDEO_LOCATION . '/js/fileinput.min.js', array('jquery'), '4.0', true); 
        wp_enqueue_script('photoswipe', RESIDEO_LOCATION . '/js/photoswipe.min.js', array(), '4.1.3', true);
        wp_enqueue_script('photoswipe-ui', RESIDEO_LOCATION . '/js/photoswipe-ui-default.min.js', array(), '4.1.3', true);
        wp_enqueue_script('owl-carousel',  RESIDEO_LOCATION . '/js/owl.carousel.min.js', array(), '2.3.4', true);
        wp_enqueue_script('chart', RESIDEO_LOCATION . '/js/Chart.min.js', array(), '2.9.3', true);
        wp_enqueue_script('sticky', RESIDEO_LOCATION . '/js/jquery.sticky.js', array('jquery'), '1.0.4', true);
        wp_enqueue_script('vibrant', RESIDEO_LOCATION . '/js/vibrant.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('masonry', RESIDEO_LOCATION . '/js/masonry.min.js', array('jquery'), '3.3.2', true);
        wp_enqueue_script('jquery-masonry', RESIDEO_LOCATION . '/js/jquery.masonry.min.js', array('jquery'), '3.1.2b', true);
        wp_enqueue_script('numscroller', RESIDEO_LOCATION . '/js/numscroller-1.0.js', array('jquery'), '1.0', true);
        wp_enqueue_script('anime', RESIDEO_LOCATION . '/js/anime.min.js', array(), '3.2.1', true);

        wp_enqueue_script('pxp-services', RESIDEO_LOCATION . '/js/services.js', array(), '1.0', true);

        if ($gmaps_key != '') {
            wp_enqueue_script('infobox', RESIDEO_LOCATION . '/js/infobox.js', array('gmaps'), '1.1.13', true);
            wp_enqueue_script('pxp-map', RESIDEO_LOCATION . '/js/map.js', array(), '1.0', true);
            wp_enqueue_script('pxp-map-single', RESIDEO_LOCATION . '/js/single-map.js', array(), '1.0', true);
            wp_enqueue_script('pxp-map-contact', RESIDEO_LOCATION . '/js/contact-map.js', array(), '1.0', true);
        }

        $general_settings  = get_option('resideo_general_settings');
        $auto_country            = isset($general_settings['resideo_auto_country_field']) ? $general_settings['resideo_auto_country_field'] : '';
        $currency                = isset($general_settings['resideo_currency_symbol_field']) ? $general_settings['resideo_currency_symbol_field'] : '';
        $currency_pos            = isset($general_settings['resideo_currency_symbol_pos_field']) ? $general_settings['resideo_currency_symbol_pos_field'] : '';
        $map_marker_price_format = isset($general_settings['resideo_map_marker_price_format']) ? $general_settings['resideo_map_marker_price_format'] : 'short';

        $fields_settings   = get_option('resideo_prop_fields_settings');
        $city_type         = isset($fields_settings['resideo_p_city_t_field']) ? $fields_settings['resideo_p_city_t_field'] : '';
        $neighborhood_type = isset($fields_settings['resideo_p_neighborhood_t_field']) ? $fields_settings['resideo_p_neighborhood_t_field'] : '';

        $appearance_settings = get_option('resideo_appearance_settings');
        $theme_mode = isset($appearance_settings['resideo_theme_mode_field']) ? $appearance_settings['resideo_theme_mode_field'] : '';

        if ($gmaps_key != '') {
            wp_enqueue_script('pxp-map-submit', RESIDEO_LOCATION . '/js/submit-property-map.js', array(), '1.0', true);
            wp_localize_script('pxp-map-submit', 'spm_vars', 
                array(
                    'default_lat'       => $gmaps_lat,
                    'default_lng'       => $gmaps_lng,
                    'auto_country'      => $auto_country,
                    'city_type'         => $city_type,
                    'neighborhood_type' => $neighborhood_type,
                    'geocode_error'     => esc_html__('Geocode was not successful for the following reason', 'resideo'),
                    'theme_mode'        => $theme_mode,
                    'gmaps_style'       => $gmaps_style
                )
            );
        }

        wp_enqueue_script('pxp-tilt', RESIDEO_LOCATION . '/js/tilt.js', array(), '1.0', true);
        wp_enqueue_script('pxp-main', RESIDEO_LOCATION . '/js/main.js', array(), '1.0', true);
        wp_enqueue_script('pxp-video', RESIDEO_LOCATION . '/js/video.js', array(), '1.0', true);
        wp_enqueue_script('pxp-gallery', RESIDEO_LOCATION . '/js/gallery.js', array(), '1.0', true);
        wp_enqueue_script('pxp-payment-calculator', RESIDEO_LOCATION . '/js/payment-calculator.js', array(), '1.0', true);

        // Include dsIDXpress IDX Script only if plugin is active
        if (function_exists('dsidxpress_InitWidgets')) {
            wp_enqueue_script('resideo-dsidx-js', RESIDEO_LOCATION . '/js/idx.js', array(), '1.0', true);
        }

        // Search values
        $search_status       = isset($_GET['search_status']) ? sanitize_text_field($_GET['search_status']) : '0';
        $search_address      = isset($_GET['search_address']) ? stripslashes(sanitize_text_field($_GET['search_address'])) : '';
        $search_street_no    = isset($_GET['search_street_no']) ? stripslashes(sanitize_text_field($_GET['search_street_no'])) : '';
        $search_street       = isset($_GET['search_street']) ? stripslashes(sanitize_text_field($_GET['search_street'])) : '';
        $search_neighborhood = isset($_GET['search_neighborhood']) ? stripslashes(sanitize_text_field($_GET['search_neighborhood'])) : '';
        $search_city         = isset($_GET['search_city']) ? stripslashes(sanitize_text_field($_GET['search_city'])) : '';
        $search_state        = isset($_GET['search_state']) ? stripslashes(sanitize_text_field($_GET['search_state'])) : '';
        $search_zip          = isset($_GET['search_zip']) ? sanitize_text_field($_GET['search_zip']) : '';
        $search_type         = isset($_GET['search_type']) ? sanitize_text_field($_GET['search_type']) : '0';
        $search_price_min    = isset($_GET['search_price_min']) ? sanitize_text_field($_GET['search_price_min']) : '';
        $search_price_max    = isset($_GET['search_price_max']) ? sanitize_text_field($_GET['search_price_max']) : '';
        $search_beds         = isset($_GET['search_beds']) ? sanitize_text_field($_GET['search_beds']) : '';
        $search_baths        = isset($_GET['search_baths']) ? sanitize_text_field($_GET['search_baths']) : '';
        $search_size_min     = isset($_GET['search_size_min']) ? sanitize_text_field($_GET['search_size_min']) : '';
        $search_size_max     = isset($_GET['search_size_max']) ? sanitize_text_field($_GET['search_size_max']) : '';
        $search_keywords     = isset($_GET['search_keywords']) ? stripslashes(sanitize_text_field($_GET['search_keywords'])) : '';
        $search_id           = isset($_GET['search_id']) ? sanitize_text_field($_GET['search_id']) : '';
        $featured            = isset($_GET['featured']) ? sanitize_text_field($_GET['featured']) : '';
        $sort                = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';

        $sort_leads    = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
        $leads_page_no = get_query_var('paged');

        $amenities_settings = get_option('resideo_amenities_settings');
        $search_amenities   = array();

        if (is_array($amenities_settings) && count($amenities_settings) > 0) {
            uasort($amenities_settings, "resideo_compare_position");

            foreach ($amenities_settings as $key => $value) {
                if (isset($_GET[$key]) && esc_html($_GET[$key]) == 1) {
                    array_push($search_amenities, $key);
                }
            }
        }

        $custom_fields_settings = get_option('resideo_fields_settings');
        $search_custom_fields = array();

        if (is_array($custom_fields_settings)) {
            uasort($custom_fields_settings, "resideo_compare_position");

            foreach ($custom_fields_settings as $key => $value) {
                if ($value['search'] == 'yes' || $value['filter'] == 'yes') {
                    $field_data = array();

                    if ($value['type'] == 'interval_field') {
                        $search_field_min = isset($_GET[$key . '_min']) ? sanitize_text_field($_GET[$key . '_min']) : '';
                        $search_field_max = isset($_GET[$key . '_max']) ? sanitize_text_field($_GET[$key . '_max']) : '';
                    } else {
                        $search_field = isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : '';
                    }

                    $comparison = $key . '_comparison';
                    $comparison_value = isset($_GET[$comparison]) ? sanitize_text_field($_GET[$comparison]) : '';
                    $field_data['name'] = $key;

                    if ($value['type'] == 'interval_field') {
                        $field_data['value'] = array($search_field_min, $search_field_max);
                    } else {
                        $field_data['value'] = $search_field;
                    }

                    $field_data['compare'] = $comparison_value;
                    $field_data['type'] = $value['type'];

                    array_push($search_custom_fields, $field_data);
                }
            }
        }

        wp_localize_script('pxp-gallery', 'gallery_vars', 
            array(
                'is_rtl' => is_rtl()
            )
        );

        $user_logged_in = 0;
        $user_is_agent = 0;
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_logged_in = 1;
            if (function_exists('resideo_check_user_agent')) {
                if (resideo_check_user_agent($current_user->ID) === true) {
                    $user_is_agent = 1;
                } else {
                    $user_is_agent = 0;
                }
            }
        } else {
            $user_logged_in = 0;
        }

        wp_localize_script('pxp-services', 'services_vars', 
            array(
                'admin_url'           => get_admin_url(),
                'ajaxurl'             => admin_url('admin-ajax.php'),
                'theme_url'           => RESIDEO_LOCATION,
                'base_url'            => home_url(),
                'user_logged_in'      => $user_logged_in,
                'user_is_agent'       => $user_is_agent,
                'wishlist_save'       => esc_html__('Save', 'resideo'),
                'wishlist_saved'      => esc_html__('Saved', 'resideo'),
                'list_redirect'       => function_exists('resideo_get_my_properties_link') ? resideo_get_my_properties_link() : '',
                'leads'               => esc_html__('Leads', 'resideo'),
                'leads_redirect'      => function_exists('resideo_get_myleads_url') ? resideo_get_myleads_url() : '',
                'sort_leads'          => $sort_leads,
                'leads_page_no'       => $leads_page_no,
                'vs_7_days'           => esc_html__('vs last 7 days', 'resideo'),
                'vs_30_days'          => esc_html__('vs last 30 days', 'resideo'),
                'vs_60_days'          => esc_html__('vs last 60 days', 'resideo'),
                'vs_90_days'          => esc_html__('vs last 90 days', 'resideo'),
                'vs_12_months'        => esc_html__('vs last 12 months', 'resideo'),
                'leads'               => esc_html__('Leads', 'resideo'),
                'contacted'           => esc_html__('Contacted', 'resideo'),
                'not_contacted'       => esc_html__('Not contacted', 'resideo'),
                'none'                => esc_html__('None', 'resideo'),
                'fit'                 => esc_html__('Fit', 'resideo'),
                'ready'               => esc_html__('Ready', 'resideo'),
                'engaged'             => esc_html__('Engaged', 'resideo'),
                'messages_list_empty' => esc_html__('No messages.', 'resideo'),
                'wl_list_empty'       => esc_html__('No properties in wish list.', 'resideo'),
                'searches_list_empty' => esc_html__('No saved searches.', 'resideo'),
                'related_property'    => esc_html__('Related Property', 'resideo'),
                'loading_messages'    => esc_html__('Loading messages', 'resideo'),
                'loading_wl'          => esc_html__('Loading wish list', 'resideo'),
                'loading_searches'    => esc_html__('Loading saved searches', 'resideo'),
                'account_redirect'    => function_exists('resideo_get_account_url') ? resideo_get_account_url() : '',
                'theme_mode'          => $theme_mode
            )
        );

        wp_localize_script('pxp-main', 'main_vars', 
            array(
                'theme_url'         => RESIDEO_LOCATION,
                'auto_country'      => $auto_country,
                'default_lat'       => $gmaps_lat,
                'default_lng'       => $gmaps_lng,
                'city_type'         => $city_type,
                'neighborhood_type' => $neighborhood_type,
                'interest'          => esc_html__('Principal and Interest', 'resideo'),
                'taxes'             => esc_html__('Property Taxes', 'resideo'),
                'hoa_dues'          => esc_html__('HOA Dues', 'resideo'),
                'currency'          => $currency,
                'currency_pos'      => $currency_pos,
                'is_rtl'            => is_rtl()
            )
        );

        wp_localize_script('pxp-map', 'map_vars', 
            array(
                'admin_url'             => get_admin_url(),
                'ajaxurl'               => admin_url('admin-ajax.php'),
                'theme_url'             => RESIDEO_LOCATION,
                'base_url'              => home_url(),
                'default_lat'           => $gmaps_lat,
                'default_lng'           => $gmaps_lng,
                'default_zoom'          => $gmaps_zoom,
                'search_status'         => $search_status,
                'search_address'        => $search_address,
                'search_street_no'      => $search_street_no,
                'search_street'         => $search_street,
                'search_neighborhood'   => $search_neighborhood,
                'search_city'           => $search_city,
                'search_state'          => $search_state,
                'search_zip'            => $search_zip,
                'search_type'           => $search_type,
                'search_price_min'      => $search_price_min,
                'search_price_max'      => $search_price_max,
                'search_beds'           => $search_beds,
                'search_baths'          => $search_baths,
                'search_size_min'       => $search_size_min,
                'search_size_max'       => $search_size_max,
                'search_keywords'       => $search_keywords,
                'search_id'             => $search_id,
                'search_amenities'      => $search_amenities,
                'search_custom_fields'  => $search_custom_fields,
                'featured'              => $featured,
                'sort'                  => $sort,
                'page'                  => $paged,
                'theme_mode'            => $theme_mode,
                'gmaps_style'           => $gmaps_style,
                'marker_price_format'   => $map_marker_price_format,
                'transportations_title' => esc_html__('Transportation', 'resideo'),
                'restaurants_title'     => esc_html__('Restaurants', 'resideo'),
                'shopping_title'        => esc_html__('Shopping', 'resideo'),
                'cafes_title'           => esc_html__('Cafes & Bars', 'resideo'),
                'arts_title'            => esc_html__('Arts & Entertainment', 'resideo'),
                'fitness_title'         => esc_html__('Fitness', 'resideo'),
                'gmaps_poi'             => $gmaps_poi
            )
        );

        wp_localize_script('pxp-map-single', 'map_single_vars', 
            array(
                'theme_mode'  => $theme_mode,
                'gmaps_style' => $gmaps_style
            )
        );

        wp_localize_script('pxp-map-contact', 'map_contact_vars', 
            array(
                'theme_mode'  => $theme_mode,
                'gmaps_style' => $gmaps_style
            )
        );

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'resideo_load_scripts' );

if (!function_exists('resideo_wp_title')) :
    function resideo_wp_title($title, $sep) {
        global $page, $paged;

        $title .= get_bloginfo('name', 'display');
        $site_description = get_bloginfo('description', 'display');

        if ($site_description && (is_home() || is_front_page() || is_archive() || is_search())) {
            $title .= " $sep $site_description";
        }

        return $title;
    }
endif;
add_filter('wp_title', 'resideo_wp_title', 10, 2);

if (!function_exists('resideo_compare_position')) :
    function resideo_compare_position($a, $b) {
        return intval($a["position"]) - intval($b["position"]);
    }
endif;

if (!function_exists('resideo_get_attachment')) :
    function resideo_get_attachment($id) {
        $attachment = get_post($id);

        return array(
            'alt'         => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption'     => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'title'       => $attachment->post_title
        );
    }
endif;

/**
 * Custom excerpt lenght
 */
if (!function_exists('resideo_custom_excerpt_length')): 
    function resideo_custom_excerpt_length($length) {
        return 30;
    }
endif;
add_filter('excerpt_length', 'resideo_custom_excerpt_length', 999);

/**
 * Custom excerpt ending
 */
function resideo_excerpt_more($more) {
    return '&#46;&#46;&#46;';
}
add_filter('excerpt_more', 'resideo_excerpt_more');

if (!function_exists('resideo_get_excerpt_by_id')): 
    function resideo_get_excerpt_by_id($post_id) {
        $the_post       = get_post($post_id);
        $the_excerpt    = $the_post->post_content;
        $excerpt_length = 30;
        $the_excerpt    = strip_tags(strip_shortcodes($the_excerpt));
        $words          = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '...');
            $the_excerpt = implode(' ', $words);
        endif;

        wp_reset_postdata();

        return $the_excerpt;
    }
endif;

/**
 * Register sidebars
 */
if (!function_exists('resideo_widgets_init')): 
    function resideo_widgets_init() {
        register_sidebar(array(
            'name'          => esc_html__('Main Widget Area', 'resideo'),
            'id'            => 'pxp-main-widget-area',
            'description'   => esc_html__('The main widget area', 'resideo'),
            'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        if (function_exists('dsidxpress_InitWidgets')) {
            register_sidebar(array(
                'name'          => esc_html__('IDX Properties Page Search Widget Area', 'resideo'),
                'id'            => 'pxp-idx-search-widget-area',
                'description'   => esc_html__('IDX properties page search form widget area', 'resideo'),
                'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>'
            ));
        }

        register_sidebar(array(
            'name'          => esc_html__('Column #1 Footer Widget Area', 'resideo'),
            'id'            => 'pxp-first-footer-widget-area',
            'description'   => esc_html__('The first column footer widget area', 'resideo'),
            'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => esc_html__('Column #2 Footer Widget Area', 'resideo'),
            'id'            => 'pxp-second-footer-widget-area',
            'description'   => esc_html__('The second column footer widget area', 'resideo'),
            'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => esc_html__('Column #3 Footer Widget Area', 'resideo'),
            'id'            => 'pxp-third-footer-widget-area',
            'description'   => esc_html__('The third column footer widget area', 'resideo'),
            'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => esc_html__('Column #4 Footer Widget Area', 'resideo'),
            'id'            => 'pxp-fourth-footer-widget-area',
            'description'   => esc_html__('The fourth column footer widget area', 'resideo'),
            'before_widget' => '<div id="%1$s" class="pxp-side-section mt-4 mt-md-5 %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
    }
endif;
add_action('widgets_init', 'resideo_widgets_init');

/**
 * Custom comments
 */

if (!function_exists('resideo_comment_ratings')): 
    function resideo_comment_ratings($comment_id) {
        if (isset($_POST['rate'])) {
            add_comment_meta($comment_id, 'rate', $_POST['rate']);
        }
    }
endif;
add_action('comment_post','resideo_comment_ratings');

if (!function_exists('resideo_comment')): 
    function resideo_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>

        <<?php echo esc_html($tag); ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">

        <div class="media mt-3 mt-md-4">
            <?php if ($args['avatar_size'] != 0) {
                echo get_avatar($comment, $args['avatar_size']);
            }

            if ('div' != $args['style']) : ?>
                <div id="div-comment-<?php comment_ID() ?>" class="comment-body media-body">
            <?php endif; ?>

            <h5><?php echo get_comment_author_link(); ?> <span class="pxp-blog-post-comments-author-label"><?php esc_html_e('Author', 'resideo'); ?></span></h5>

            <div class="pxp-blog-post-comments-date">
                <div class="comment-meta commentmetadata">
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>"><?php printf(esc_html__('%1$s at %2$s', 'resideo'), get_comment_date(), get_comment_time()); ?></a>
                </div>
            </div>

            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'resideo'); ?></em>
                <br />
            <?php endif; ?>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <ul class="pxp-comment-ops">
                <li><?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></li>
                <li><?php edit_comment_link(esc_html__('Edit', 'resideo')); ?></li>
            </ul>

            <?php if ('div' != $args['style']) : ?>
                </div>
            <?php endif; ?>
        </div>
    <?php }
endif;

if (!function_exists('resideo_agent_review')): 
    function resideo_agent_review($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>

        <<?php echo esc_html($tag); ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">

        <div class="media mt-3 mt-md-4">
            <?php if ($args['avatar_size'] != 0) {
                echo get_avatar($comment, $args['avatar_size']);
            }

            if ('div' != $args['style']) : ?>
                <div id="div-comment-<?php comment_ID() ?>" class="comment-body media-body">
            <?php endif; ?>

            <h5><?php echo get_comment_author_link(); ?></h5>

            <div class="pxp-blog-post-comments-date">
                <div class="comment-meta commentmetadata">
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>"><?php printf(esc_html__('%1$s at %2$s', 'resideo'), get_comment_date(), get_comment_time()); ?></a>
                </div>
            </div>

            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php esc_html_e('Your review is awaiting moderation.', 'resideo'); ?></em>
                <br />
            <?php endif;

            $rate = get_comment_meta($comment->comment_ID, 'rate');

            if (isset($rate[0]) && $rate[0] != '') {
                print resideo_display_agent_rating(array('avarage' => $rate[0], 'users' => 0), false, 'pxp-agent-review-rating');
            }

            comment_text(); ?>

            <?php if ('div' != $args['style']) : ?>
                </div>
            <?php endif; ?>
        </div>
    <?php }
endif;

if(!function_exists('resideo_get_field_value')): 
    function resideo_get_field_value($field_type, $field_value, $list) {
        $field_text = '';

        if ($field_value != '') {
            if ($field_type == 'list') {
                if (is_array($list) && count($list) > 0) {
                    foreach ($list as $key => $value) {
                        if ($field_value == $key) {
                            $field_text = $value['name'];
                        }
                    }
                }
            } else {
                return $field_text = $field_value;
            }
        }

        return $field_text;
    }
endif;

/**
 * Pagination
 */
if (!function_exists('resideo_pagination')): 
    function resideo_pagination($pages = '', $range = 2) {
        $showitems = ($range * 2) + 1;

        global $paged;
        if (empty($paged)) {
            $paged = 1;
        }

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) {
            echo '<ul class="pagination pxp-paginantion mt-2 mt-md-4">';

            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link(1)) . '"><span class="fa fa-angle-double-left"></span></a></li>';
            }

            if ($paged > 1 && $showitems < $pages) {
                echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($paged - 1)) . '"><span class="fa fa-angle-left"></span></a></li>';
            }

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    if ($paged == $i) {
                        echo '<li class="page-item active"><a class="page-link" href="#">' . esc_html($i) . '</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '">' . esc_html($i) . '</a></li>';
                    }
                }
            }

            if ($paged < $pages && $showitems < $pages) {
                echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($paged + 1)) . '"><span class="fa fa-angle-right"></span></a></li>';
            }

            if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($pages)) . '"><span class="fa fa-angle-double-right"></span></a></li>';
            }

            echo '</ul>';
        }
    }
endif;

if (!function_exists('resideo_sanitize_item')) :
    function resideo_sanitize_item($item) {
        return sanitize_text_field($item);
    }
endif;

if (!function_exists('resideo_sanitize_multi_array')) :
    function resideo_sanitize_multi_array(&$item, $key) {
        $item = sanitize_text_field($item);
    }
endif;

if (!function_exists('money_format')) :
    function money_format($format, $number) {
        while (true) { 
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 

            if ($replaced != $number) { 
                $number = $replaced; 
            } else { 
                break; 
            }
        }

        return $number; 
    }
endif;

if (!function_exists('resideo_get_client_ip_env')): 
    function resideo_get_client_ip_env() {
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if(getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if(getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if(getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if(getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if(getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }
endif;

if (!function_exists('resideo_add_dark_mode_class')): 
    function resideo_add_dark_mode_class($classes) {
        $appearance_settings = get_option('resideo_appearance_settings');
        $theme_mode = isset($appearance_settings['resideo_theme_mode_field']) ? $appearance_settings['resideo_theme_mode_field'] : '';

        if ($theme_mode == 'dark') {
            $classes[] = 'pxp-dark-mode';
        }

        return $classes;
    }
endif;
add_filter('body_class', 'resideo_add_dark_mode_class');
?>
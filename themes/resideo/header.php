<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php if (function_exists('resideo_get_social_meta')) {
        resideo_get_social_meta();
    }

    wp_head(); ?>
</head>

<?php 
$submit_url       = function_exists('resideo_get_submit_url') ? resideo_get_submit_url() : '';
$wishlist_url     = function_exists('resideo_get_wishlist_url') ? resideo_get_wishlist_url() : '';
$searches_url     = function_exists('resideo_get_searches_url') ? resideo_get_searches_url() : '';
$myproperties_url = function_exists('resideo_get_myproperties_url') ? resideo_get_myproperties_url() : '';
$myleads_url      = function_exists('resideo_get_myleads_url') ? resideo_get_myleads_url() : '';
$account_url      = function_exists('resideo_get_account_url') ? resideo_get_account_url() : ''; ?>

<body <?php body_class(); ?>>
    <?php if (!function_exists( 'wp_body_open')) {
        function wp_body_open() {
            do_action('wp_body_open');
        }
    }

    $header_class = '';
    $header_container_class = 'container';

    $template = '';
    $post_type = '';
    if (isset($post)) {
        $template = get_post_meta($post->ID, 'page_template_type', true);
        $post_type = get_post_type($post);
    }

    $property_layout_settings = get_option('resideo_property_layout_settings');
    $property_layout = isset($property_layout_settings['resideo_property_layout_field']) ? $property_layout_settings['resideo_property_layout_field'] : 'd1';

    if ((is_page_template('property-search.php') && ($template == 'half_map_left' || $template == 'half_map_right') && wp_script_is('gmaps', 'enqueued')) 
        || (is_page_template('idx-map-left.php') && wp_script_is('gmaps', 'enqueued'))
        || (is_page_template('idx-map-right.php') && wp_script_is('gmaps', 'enqueued'))
        || ($post_type == 'property' && $property_layout == 'd4' && wp_script_is('gmaps', 'enqueued'))) {
        $header_class = 'pxp-full';
        $header_container_class = 'pxp-container-full';
    } else {
        $post = get_post();

        if (isset($post)) {
            $header_type = get_post_meta($post->ID, 'page_header_type', true);

            if (isset($header_type) && ($header_type == '' || $header_type == 'none')) {
                $header_class = 'pxp-animate pxp-no-bg';
            } else {
                $header_class = 'pxp-animate';
            }
        } else {
            $header_class = 'pxp-animate pxp-no-bg';
        }
    } 

    $appearance_settings = get_option('resideo_appearance_settings');
    $header_bg = isset($appearance_settings['resideo_header_background_field']) ? $appearance_settings['resideo_header_background_field'] : 'transparent';
    $header_bg_class = $header_bg == 'opaque' ? 'pxp-is-opaque' : ''; ?>

    <div class="pxp-header fixed-top <?php echo esc_html($header_class); ?> <?php echo esc_html($header_bg_class); ?>">
        <div class="<?php echo esc_html($header_container_class); ?>">
            <div class="row align-items-center no-gutters">
                <div class="col-6 col-lg-2 pxp-rtl-align-right">
                    <?php $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id , 'pxp-full');
                    $logo_class = $logo !== false ? 'pxp-has-img' : '';
                    
                    $second_logo_id = get_theme_mod('resideo_second_logo');
                    $second_logo = wp_get_attachment_image_src($second_logo_id , 'pxp-full');
                    $first_logo_class = $second_logo !== false ? 'pxp-first-logo' : ''; ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="pxp-logo text-decoration-none <?php echo esc_attr($logo_class); ?>">
                        <?php $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id , 'pxp-full');

                        if ($logo !== false) {
                            print '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="' . esc_attr($first_logo_class) . '"/>';
                            if ($second_logo !== false) {
                                print '<img src="' . esc_url($second_logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="pxp-second-logo"/>';
                            }
                        } else {
                            print esc_html(get_bloginfo('name'));
                        } ?>
                    </a>
                </div>
                <div class="col-1 col-lg-8 text-center">
                    <div class="pxp-nav">
                        <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
                    </div>
                </div>
                <div class="col-5 col-lg-2 text-right">
                    <a href="javascript:void(0);" class="pxp-header-nav-trigger"><span class="fa fa-bars"></span></a>
                    <?php $auth_settings = get_option('resideo_authentication_settings');
                    $user_nav = isset($auth_settings['resideo_user_registration_field']) ? $auth_settings['resideo_user_registration_field'] : '';

                    if ($user_nav == '1') {
                        if (is_user_logged_in()) {
                            global $current_user;

                            $current_user = wp_get_current_user();
                            $user_avatar  = get_the_author_meta('avatar' , $current_user->ID);
                            $avatar_src   = wp_get_attachment_image_src($user_avatar, 'pxp-thmb');
                            $is_agent     = function_exists('resideo_check_user_agent') ? resideo_check_user_agent($current_user->ID) : false;

                            if ($avatar_src !== false) { ?>
                                <a href="javascript:void(0);" class="pxp-header-user-loggedin pxp-header-user-avatar" style="background-image: url(<?php echo esc_url($avatar_src[0]); ?>)"><span class="fa fa-user-o"></span></a>
                            <?php } else { ?>
                                <a href="javascript:void(0);" class="pxp-header-user-loggedin pxp-header-user"><span class="fa fa-user-o"></span></a>
                            <?php } ?>

                            <ul class="pxp-user-menu">
                                <?php if ($is_agent === true) { ?>
                                    <li><a href="<?php echo esc_url($submit_url); ?>"><?php esc_html_e('Submit New Property', 'resideo'); ?></a></li>
                                <?php } ?>

                                <li><a href="<?php echo esc_url($wishlist_url); ?>"><?php esc_html_e('Wish List', 'resideo'); ?></a></li>
                                <li><a href="<?php echo esc_url($searches_url); ?>"><?php esc_html_e('Saved Searches', 'resideo'); ?></a></li>

                                <?php if ($is_agent === true) { ?>
                                    <li><a href="<?php echo esc_url($myproperties_url); ?>"><?php esc_html_e('My Properties', 'resideo'); ?></a></li>
                                <?php } ?>

                                <?php if ($is_agent === true) { ?>
                                    <li><a href="<?php echo esc_url($myleads_url); ?>"><?php esc_html_e('My Leads', 'resideo'); ?></a></li>
                                <?php } ?>

                                <li><a href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('Account Settings', 'resideo'); ?></a></li>
                                <li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php esc_html_e('Sign Out', 'resideo'); ?></a></li>
                            </ul>
                        <?php } else { ?>
                            <a href="javascript:void(0);" class="pxp-header-user pxp-signin-trigger"><span class="fa fa-user-o"></span></a>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>

<?php if (function_exists('resideo_get_user_modal')) {
    resideo_get_user_modal();
} ?>
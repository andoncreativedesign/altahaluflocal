<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();
$resideo_general_settings = get_option('resideo_general_settings');
$show_rating = isset($resideo_general_settings['resideo_agents_rating_field']) ? $resideo_general_settings['resideo_agents_rating_field'] : '';

$appearance_settings = get_option('resideo_appearance_settings');
$hide_phone = isset($appearance_settings['resideo_hide_agents_phone_field']) ? $appearance_settings['resideo_hide_agents_phone_field'] : ''; ?>

<div class="pxp-content">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">
            <?php while(have_posts()) : the_post();
                $agent_id  = get_the_ID();
                $title     = get_post_meta($agent_id, 'agent_title', true);
                $avatar    = get_post_meta($agent_id, 'agent_avatar', true);
                $phone     = get_post_meta($agent_id, 'agent_phone', true);
                $email     = get_post_meta($agent_id, 'agent_email', true);
                $skype     = get_post_meta($agent_id, 'agent_skype', true);
                $facebook  = get_post_meta($agent_id, 'agent_facebook', true);
                $twitter   = get_post_meta($agent_id, 'agent_twitter', true);
                $linkedin  = get_post_meta($agent_id, 'agent_linkedin', true);
                $pinterest = get_post_meta($agent_id, 'agent_pinterest', true);
                $instagram = get_post_meta($agent_id, 'agent_instagram', true);
                $specs     = get_post_meta($agent_id, 'agent_specs', true); ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="row">
                        <div class="col-sm-12 col-lg-8">
                            <h1 class="pxp-page-header float-left"><?php echo get_the_title(); ?></h1>

                            <?php if ($show_rating != '') {
                                print resideo_display_agent_rating(resideo_get_agent_ratings($agent_id), true, 'pxp-agent-rating');
                            } ?>

                            <div class="clearfix"></div>

                            <?php if ($title != '') { ?>
                                <p><?php echo esc_html($title); ?></p>
                            <?php } ?>

                            <div class="mt-4 mt-md-5">
                                <?php if ($email != '') { ?>
                                    <div class="pxp-agent-email"><a href="mailto:<?php echo esc_attr($email); ?>"><span class="fa fa-envelope-o"></span> <?php echo esc_html($email); ?></a></div>
                                <?php } ?>

                                <?php if ($phone != '') {
                                    if ($hide_phone != '') { ?>
                                        <div class="pxp-agent-show-phone" data-phone="<?php echo esc_attr($phone); ?>"><span class="fa fa-phone"></span> <span class="pxp-is-number"><?php esc_html_e('Show phone number', 'resideo'); ?></span></div>
                                    <?php } else  { ?>
                                        <div class="pxp-agent-phone"><span class="fa fa-phone"></span> <?php echo esc_html($phone); ?></div>
                                    <?php }
                                } ?>
                            </div>
                            <div class="mt-4 mt-md-5">
                                <a href="#pxp-work-with-modal" class="pxp-agent-contact-btn" data-toggle="modal" data-target="#pxp-work-with-modal"><?php esc_html_e('Work with', 'resideo'); ?> <?php echo get_the_title(); ?></a>
                                <?php if (function_exists('resideo_work_with_agent_modal')) {
                                    $modal_info = array();

                                    $modal_info['user_id'] = '';
                                    $modal_info['user_email'] = '';
                                    $modal_info['user_firstname'] = '';
                                    $modal_info['user_lastname'] = '';

                                    $modal_info['agent_name'] = get_the_title();
                                    $modal_info['agent_email'] = $email;
                                    $modal_info['agent_id'] = $agent_id;

                                    if (is_user_logged_in()) {
                                        $user = wp_get_current_user();
                                        $user_meta = get_user_meta($user->ID);
                                        $user_firstname = $user_meta['first_name'];
                                        $user_lastname  = $user_meta['last_name'];

                                        $modal_info['user_id'] = $user->ID;
                                        $modal_info['user_email'] = $user->user_email;
                                        $modal_info['user_firstname'] = $user_firstname[0];
                                        $modal_info['user_lastname'] = $user_lastname[0];
                                    }

                                    resideo_work_with_agent_modal($modal_info);
                                } ?>
                            </div>
                        </div>
                        <div class="col-sm-12 offset-lg-1 pxp-agent-photo-container col-lg-3">
                            <?php $avatar_photo = wp_get_attachment_image_src($avatar, 'pxp-agent');
                            $a_photo = $avatar_photo != '' ? $avatar_photo[0] : RESIDEO_LOCATION . '/images/avatar-default.png'; ?>

                            <div class="pxp-agent-photo pxp-cover rounded-lg mt-4 mt-md-5 mt-lg-0" style="background-image: url(<?php echo esc_url($a_photo); ?>); background-position: 50% 0%;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-lg-8">
                            <div class="pxp-agent-section mt-4 mt-md-5">
                                <h3><?php esc_html_e('About', 'resideo'); ?> <?php echo get_the_title(); ?></h3>
                                <div class="mt-3 mt-md-4">
                                    <div class="entry-content pxp-agent-content">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 offset-lg-1 pxp-agent-specs-container">
                            <?php if ($specs != '') { 
                                $specs_list = explode(',', $specs); ?>
                                <div class="pxp-agent-section mt-4 mt-md-5">
                                    <h3><?php esc_html_e('Specialities', 'resideo'); ?></h3>
                                    <ul class="list-unstyled pxp-agent-specialities mt-3 mt-md-4">
                                        <?php foreach ($specs_list as $spec) { ?>
                                            <li><?php echo esc_html($spec); ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php }

                            if ($facebook != '' || $twitter != '' || $linkedin != '' || $pinterest != '' || $instagram != '') { ?>
                                <div class="pxp-agent-section mt-4 mt-md-5">
                                    <h3><?php esc_html_e('Social Media', 'resideo'); ?></h3>
                                    <ul class="list-unstyled pxp-agent-social mt-3 mt-md-4">
                                        <?php if ($facebook != '') { ?>
                                            <li>
                                                <a href="<?php echo esc_url($facebook); ?>"><span class="fa fa-facebook"></span></a>
                                            </li>
                                        <?php }
                                        if ($twitter != '') { ?>
                                            <li>
                                                <a href="<?php echo esc_url($twitter); ?>"><span class="fa fa-twitter"></span></a>
                                            </li>
                                        <?php }
                                        if ($linkedin != '') { ?>
                                            <li>
                                                <a href="<?php echo esc_url($linkedin); ?>"><span class="fa fa-linkedin"></span></a>
                                            </li>
                                        <?php }
                                        if ($pinterest != '') { ?>
                                            <li>
                                                <a href="<?php echo esc_url($pinterest); ?>"><span class="fa fa-pinterest"></span></a>
                                            </li>
                                        <?php }
                                        if ($instagram != '') { ?>
                                            <li>
                                                <a href="<?php echo esc_url($instagram); ?>"><span class="fa fa-instagram"></span></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php get_template_part('templates/agent_properties');

                    if ($show_rating != '' && (comments_open() || get_comments_number())) {
                        comments_template();
                    } ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$resideo_appearance_settings = get_option('resideo_appearance_settings', '');
$sidebar_position            = isset($resideo_appearance_settings['resideo_sidebar_field']) ? $resideo_appearance_settings['resideo_sidebar_field'] : 'right';
$resideo_general_settings    = get_option('resideo_general_settings'); 


$is_sidebar = is_active_sidebar('pxp-main-widget-area');
$content_column_class = 'col-12';
$items_column_class = 'col-sm-12 col-md-6 col-lg-4';
if ($is_sidebar === true) {
    $content_column_class = 'col-sm-12 col-lg-9';
    $items_column_class = 'col-sm-12 col-md-6';
}

$sidebar_class = '';
if ($sidebar_position == 'left') {
    $sidebar_class = 'order-first';
} ?>

<div class="pxp-content">
    <?php while(have_posts()) : the_post();
        $post_ID     = get_the_ID();
        $header_type = get_post_meta($post_ID, 'page_header_type', true);

        $header_info                     = array();
        $header_info['post_id']          = $post_ID;
        $header_info['header_type']      = $header_type;
        $header_info['general_settings'] = $resideo_general_settings;

        if (function_exists('resideo_get_page_header')) {
            resideo_get_page_header($header_info);
        }

        $hide_page_title = get_post_meta($post_ID, 'page_title_hide', true);

        $content_wrapper_class = '';
        $page_title_margin = 'mt-100';
        $container_margin = 'mt-100';

        if ($header_type == 'none' || $header_type == '') {
            $content_wrapper_class = 'pxp-content-wrapper mt-100';
            $page_title_margin = '';
            $container_margin = '';
        } ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="<?php echo esc_attr($content_wrapper_class); ?>">
                <?php if (!is_front_page() && $hide_page_title != '1') {
                    $container_margin = 'mt-4 mt-md-5'; ?>

                    <div class="container <?php echo esc_attr($page_title_margin); ?>">
                        <div class="row">
                            <div class="col-sm-12 col-md-7">
                                <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="container <?php echo esc_attr($container_margin); ?>">
                    <div class="row">
                        <div class="<?php echo esc_attr($content_column_class); ?>">
                            <div class="page-content">
                                <?php the_content(); ?>
                            </div>
                            <div class="clearfix"></div>
                            <?php wp_link_pages(
                                array(
                                    'before'      => '<div class="pagination pxp-paginantion mt-2 mt-md-4">',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                                    'pagelink'    => '%',
                                    'separator'   => '',
                                )
                            ); ?>

                            <div class="pxp-page-comments">
                                <?php if (comments_open() || get_comments_number()) {
                                    comments_template();
                                } ?>
                            </div>
                        </div>

                        <?php if ($is_sidebar === true) { ?>
                            <div class="col-sm-12 col-lg-3 mt-4 mt-md-5 mt-lg-0 <?php echo esc_attr($sidebar_class); ?>">
                                <?php get_sidebar(); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>

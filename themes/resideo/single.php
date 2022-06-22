<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

global $post;
get_header();

$resideo_appearance_settings = get_option('resideo_appearance_settings', '');
$sidebar_position = isset($resideo_appearance_settings['resideo_sidebar_field']) ? $resideo_appearance_settings['resideo_sidebar_field'] : '';

$is_sidebar = is_active_sidebar('pxp-main-widget-area');

$sidebar_class = '';
if ($sidebar_position == 'left') {
    $sidebar_class = 'order-first';
} ?>

<div class="pxp-content">
    <?php while (have_posts()) : the_post();
        $post_id    = get_the_ID();
        $post_date  = get_the_date();
        $categories = get_the_category();
        $author     = get_the_author();

        $post_hero = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'pxp-full'); ?>

        <div class="pxp-blog-posts pxp-content-wrapper mt-100">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-9 col-lg-7">
                        <div class="pxp-blog-post-category">
                            <span><?php echo esc_html($post_date); ?></span>
                            <?php if ($categories) { ?>
                                <span>
                                    <?php $separator = ', ';
                                    $output = '';

                                    foreach ($categories as $category) {
                                        $output .= $category->cat_name . $separator;
                                    }

                                    echo trim($output, $separator); ?>
                                </span>
                            <?php } ?>
                        </div>
                        <h1 class="pxp-page-header"><?php echo get_the_title(); ?></h1>
                        <div class="pxp-blog-post-author"><?php echo esc_html__('By', 'resideo') . ' ' . esc_html($author); ?></div>
                    </div>
                </div>
                <?php if ($post_hero !== false) { ?>
                    <div class="pxp-blog-post-hero mt-4 mt-md-5">
                        <div class="pxp-blog-post-hero-fig pxp-cover" style="background-image: url(<?php echo esc_url($post_hero[0]); ?>)"></div>
                    </div>
                <?php } ?>
            </div>


            <div class="container mt-100">
                <div class="row">
                    <?php $content_class = '';

                    if (function_exists('resideo_get_post_share_menu')) {
                        resideo_get_post_share_menu($post_id); 
                        if ($is_sidebar === true) { ?>
                            <div class="col-sm-12 col-lg-8">
                        <?php } else { ?>
                            <div class="col-sm-12 col-lg-11">
                        <?php }
                    } else { 
                        if ($is_sidebar === true) { 
                            $content_class = 'pxp-no-share'; ?>
                            <div class="col-sm-12 col-lg-9">
                        <?php } else { 
                            $content_class = 'pxp-no-share pxp-no-side'; ?>
                            <div class="col-12">
                        <?php }
                    } ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="entry-content <?php echo esc_attr($content_class); ?>">
                                <?php the_content(); ?>
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
                                <div class="mt-4 mt-md-5">
                                    <?php the_tags('<div class="pxp-single-post-tags"><span class="fa fa-tags"></span>', '', '</div>'); ?>
                                </div>
                            </div>

                            <div class="<?php echo esc_attr($content_class); ?>">
                                <?php if (comments_open() || get_comments_number()) {
                                    comments_template();
                                } ?>
                            </div>
                        </div>

                    </div>

                    <?php if ($is_sidebar === true) { ?>
                        <div class="col-sm-12 col-lg-3 mt-4 mt-md-5 mt-lg-0 <?php echo esc_attr($sidebar_class); ?>">
                            <?php get_sidebar(); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php $related_posts = isset($resideo_appearance_settings['resideo_related_posts_field']) ? $resideo_appearance_settings['resideo_related_posts_field'] : '';

            if ($related_posts != '') {
                get_template_part('templates/related_posts');
            } ?>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
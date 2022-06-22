<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

get_header();

$resideo_appearance_settings = get_option('resideo_appearance_settings', '');
$sidebar_position = isset($resideo_appearance_settings['resideo_sidebar_field']) ? $resideo_appearance_settings['resideo_sidebar_field'] : 'right';

$is_sidebar = is_active_sidebar('pxp-main-widget-area');
$content_column_class = 'col-12';
$items_column_class = 'col-sm-12 col-md-6 col-lg-4';
if ($is_sidebar === true) {
    $content_column_class = 'col-sm-12 col-lg-9';
    $items_column_class = 'col-sm-12 col-md-6';
}
?>

<div class="pxp-content">
    <div class="pxp-content-wrapper mt-100">
        <div class="container">

            <?php the_archive_title('<h1 class="pxp-page-header">', '</h1>');

            $sidebar_class = '';
            if ($sidebar_position == 'left') {
                $sidebar_class = 'order-first';
            } ?>

            <div class="row mt-4 mt-md-5">
                <div class="<?php echo esc_attr($content_column_class); ?>">
                    <div class="row pxp-masonry">
                        <?php $temp = isset($postslist) ? $postslist : null;
                        $postslist  = null; 
                        $paged      = get_query_var('paged') ? get_query_var('paged') : 1;
                        $term       = get_queried_object();
                        $term_id    = $term ? $term->term_id : '';
                        $year       = get_query_var('year');
                        $monthnum   = get_query_var('monthnum');
                        $day        = get_query_var('day');

                        $args = array(
                            'posts_per_page' => get_option('posts_per_page'),
                            'paged'          => $paged,
                            'post_type'      => 'post'
                        );

                        if (is_date()) {
                            $args['year']     = $year;
                            $args['monthnum'] = $monthnum;
                            $args['day']      = $day;
                        } else {
                            $args['tax_query'] = array(
                                'relation' => 'OR',
                                array(
                                    'taxonomy' => 'category',
                                    'terms'    => $term_id,
                                )
                            );
                            $args['tag_id'] = $term_id;
                        }

                        $postslist = new WP_Query($args);

                        if ($postslist->have_posts()) {
                            while ($postslist->have_posts()) : $postslist->the_post();
                                $p_id         = get_the_ID();
                                $p_link       = get_permalink($p_id);
                                $p_title      = get_the_title($p_id);
                                $p_image      = wp_get_attachment_image_src(get_post_thumbnail_id($p_id), 'pxp-gallery');
                                $p_card_image = ($p_image !== false) ? $p_image[0] : false;
                                $p_date       = get_the_date();

                                $categories = get_the_category();
                                $separator  = ' | ';
                                $output     = '';

                                if ($categories) {
                                    foreach ($categories as $category) {
                                        $output .= esc_html($category->cat_name) . esc_html($separator);
                                    }
                                    $p_categories = trim($output, $separator);
                                }

                                $item_class = $p_card_image === false ? 'pxp-no-image' : '';
                                $sticky_class = is_sticky($p_id) ? 'pxp-sticky' : ''; ?>

                                <div class="pxp-grid-item <?php echo esc_attr($items_column_class); ?>">
                                    <a href="<?php echo esc_url($p_link); ?>" class="pxp-posts-1-item <?php echo esc_attr($item_class); ?> <?php echo esc_attr($sticky_class); ?>">
                                        <div class="pxp-posts-1-item-fig-container">
                                            <?php if (is_sticky($p_id)) { ?>
                                                <div class="pxp-posts-1-item-featured-label"><?php esc_html_e('Featured', 'resideo'); ?></div>
                                            <?php } ?>
                                            <?php if ($p_card_image !== false) { ?>
                                                <div class="pxp-posts-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($p_card_image); ?>);"></div>
                                            <?php } ?>
                                        </div>
                                        <div class="pxp-posts-1-item-details">
                                            <?php if (isset($p_categories)) { ?>
                                                <div class="pxp-posts-1-item-details-category"><?php echo esc_html($p_categories); ?></div>
                                            <?php } ?>
                                            <div class="pxp-posts-1-item-details-title"><?php echo esc_html($p_title); ?></div>
                                            <div class="pxp-posts-1-item-details-date mt-2"><?php echo esc_html($p_date); ?></div>
                                            <div class="pxp-posts-1-item-cta text-uppercase"><?php esc_html_e('Read Article', 'resideo'); ?></div>
                                        </div>
                                    </a>
                                </div>
                            <?php endwhile;
                        } else { ?>
                            <div class="col-xs-12">
                                <?php esc_html_e('No articles found', 'resideo'); ?>
                            </div>
                        <?php }

                        wp_reset_postdata(); ?>
                    </div>

                    <?php if ($postslist->max_num_pages > 1) { ?>
                        <ul class="pagination pxp-paginantion mt-3 mt-md-4">
                            <li class="page-item"><?php next_posts_link('<span class="fa fa-angle-left"></span>&nbsp;&nbsp;' . esc_html__('Older Articles', 'resideo'), esc_html($postslist->max_num_pages)); ?></li>
                            <li class="page-item"><?php previous_posts_link(esc_html__('Newer Articles', 'resideo') . '&nbsp;&nbsp;<span class="fa fa-angle-right"></span>'); ?></li>
                        </ul>
                    <?php } ?>

                    <?php $postslist = null;
                    $postslist = $temp; ?>
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

<?php get_footer(); ?>
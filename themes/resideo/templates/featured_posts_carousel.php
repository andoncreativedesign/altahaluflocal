<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

$feat_args = array(
    'posts_per_page' => 4,
    'post_type'      => 'post',
    'orderby'        => 'post_date',
    'order'          => 'DESC',
    'meta_key'       => 'post_featured',
    'meta_value'     => '1',
    'post_status'    => 'publish'
);

$feat_posts = new wp_query($feat_args);
$total_feat_posts = $feat_posts->found_posts;
$posts_arr = array();

while ($feat_posts->have_posts()) {
    $feat_posts->the_post();

    $post_id = get_the_ID();

    $post = array();

    $post_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'pxp-full');
    $post['img'] = $post_image !== false ? $post_image[0] : false;

    $post_categories = get_the_category($post_id);
    $cat_separator   = ' | ';
    $cat_output      = '';

    if ($post_categories) {
        foreach ($post_categories as $category) {
            $cat_output .= esc_html($category->cat_name) . esc_html($cat_separator);
        }

        $post['categories'] = trim($cat_output, $cat_separator);
    }

    $post['title'] = get_the_title($post_id);
    $post['excerpt'] = get_the_excerpt($post_id);
    $post['permalink'] = get_permalink($post_id);

    array_push($posts_arr, $post);
}

wp_reset_postdata(); ?>

<div class="pxp-blog-posts-carousel-1 mt-4 mt-md-5">
    <div id="pxp-blog-posts-carousel-1-img" class="carousel slide pxp-blog-posts-carousel-1-img" data-ride="carousel" data-pause="false" data-interval="false">
        <div class="carousel-inner">
            <?php $counter = 0;
            foreach ($posts_arr as $post_item) { 
                $item_class = $post_item['img'] === false ? 'pxp-no-image' : ''; ?>

                <div class="carousel-item <?php if ($counter === 0) echo 'active'; ?> <?php echo esc_attr($item_class); ?>" data-slide="<?php echo esc_attr($counter); ?>">
                    <?php if ($post_item['img'] === false) { ?>
                        <div class="pxp-blog-posts-carousel-1-img-caption">
                            <div class="row">
                                <div class="col-lg-8 col-xl-6">
                                    <?php if (isset($post_item['categories'])) { ?>
                                        <div class="pxp-blog-posts-carousel-1-category"><?php echo esc_html($post_item['categories']); ?></div>
                                    <?php } ?>
                                    <div class="pxp-blog-posts-carousel-1-title"><?php echo esc_html($post_item['title']); ?></div>
                                    <div class="pxp-blog-posts-carousel-1-summary"><?php echo esc_html($post_item['excerpt']); ?></div>
                                    <a href="<?php echo esc_url($post_item['permalink']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-4 pxp-animate"><?php esc_html_e('Read Article', 'resideo'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="pxp-hero-bg pxp-cover" style="background-image: url(<?php echo esc_url($post_item['img']); ?>); background-position: 50% 50%;"></div>
                    <?php } ?>
                </div>
                <?php $counter++;
            } ?>
        </div>
    </div>

    <?php if ($counter > 1) { ?>
        <div class="pxp-carousel-controls">
            <a class="pxp-carousel-control-prev" role="button" data-slide="prev">
                <?php if (is_rtl()) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                        <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                            <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                        </g>
                    </svg>
                <?php } else { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                        <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                            <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                        </g>
                    </svg>
                <?php } ?>
            </a>
            <a class="pxp-carousel-control-next" role="button" data-slide="next">
                <?php if (is_rtl()) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                        <g id="Group_30" data-name="Group 30" transform="translate(-1845.086 -1586.086)">
                            <line id="Line_2" data-name="Line 2" x1="30" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(1846.5 1587.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(1846.5 1596.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                        </g>
                    </svg>
                <?php } else { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.414" height="20.828" viewBox="0 0 32.414 20.828">
                        <g id="Symbol_1_1" data-name="Symbol 1 – 1" transform="translate(-1847.5 -1589.086)">
                            <line id="Line_5" data-name="Line 2" x2="30" transform="translate(1848.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_6" data-name="Line 3" x2="9" y2="9" transform="translate(1869.5 1590.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                            <line id="Line_7" data-name="Line 4" y1="9" x2="9" transform="translate(1869.5 1599.5)" fill="none" stroke="#333" stroke-linecap="round" stroke-width="2"></line>
                        </g>
                    </svg>
                <?php } ?>
            </a>
        </div>
    <?php } ?>

    <div class="pxp-blog-posts-carousel-1-caption-container">
        <div id="pxp-blog-posts-carousel-1-caption" class="carousel slide pxp-blog-posts-carousel-1-caption" data-ride="carousel" data-pause="false" data-interval="false">
            <div class="carousel-inner">
                <?php $counter = 0;
                foreach ($posts_arr as $post_info) { ?>
                    <div class="carousel-item <?php if ($counter === 0) echo 'active'; ?>" data-slide="<?php echo esc_attr($counter); ?>">
                        <?php if (isset($post_info['categories'])) { ?>
                            <div class="pxp-blog-posts-carousel-1-caption-category"><?php echo esc_html($post_info['categories']); ?></div>
                        <?php } ?>
                        <div class="pxp-blog-posts-carousel-1-caption-title"><?php echo esc_html($post_info['title']); ?></div>
                        <div class="pxp-blog-posts-carousel-1-caption-summary"><?php echo esc_html($post_info['excerpt']); ?></div>
                        <a href="<?php echo esc_url($post_info['permalink']); ?>" class="pxp-primary-cta text-uppercase mt-3 mt-md-4 pxp-animate"><?php esc_html_e('Read Article', 'resideo'); ?></a>
                    </div>
                    <?php $counter++;
                } ?>
            </div>
        </div>
    </div>

    <div class="pxp-blog-posts-carousel-1-badge"><?php esc_html_e('Featured', 'resideo'); ?></div>
</div>


<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */
?>

<div class="container mt-100">
    <h2 class="pxp-section-h2"><?php esc_html_e('Related Articles', 'resideo'); ?></h2>
    <div class="row mt-4 mt-md-5">
        <?php $orig_post = $post;
        $tags = wp_get_post_tags($post->ID);

        if ($tags) {
            $tag_ids = array();

            foreach ($tags as $individual_tag) {
                $tag_ids[] = $individual_tag->term_id;
            }

            $args = array(
                'tag__in'             => $tag_ids,
                'post__not_in'        => array($post->ID),
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => false,
            );

            $my_query = new wp_query($args);

            if ($my_query->have_posts()) {
                while ($my_query->have_posts()) {
                    $my_query->the_post();

                    $r_id        = get_the_ID();
                    $r_link      = get_permalink($r_id);
                    $r_title     = get_the_title($r_id);
                    $r_image     = wp_get_attachment_image_src(get_post_thumbnail_id($r_id), 'pxp-gallery');
                    $r_image_src = ($r_image !== false) ? $r_image[0] : false;
                    $r_date      = get_the_date(); 
                    
                    $categories = get_the_category();
                    $separator  = ' | ';
                    $output     = '';

                    if ($categories) {
                        foreach ($categories as $category) {
                            $output .= esc_html($category->cat_name) . esc_html($separator);
                        }
                        $r_categories = trim($output, $separator);
                    }

                    $r_item_class = $r_image_src === false ? 'pxp-no-image' : ''; ?>

                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <a href="<?php echo esc_url($r_link); ?>" class="pxp-posts-1-item <?php echo esc_attr($r_item_class); ?>">
                            <div class="pxp-posts-1-item-fig-container">
                                <?php if ($r_image_src !== false) { ?>
                                    <div class="pxp-posts-1-item-fig pxp-cover" style="background-image: url(<?php echo esc_url($r_image_src); ?>);"></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-posts-1-item-details">
                                <?php if (isset($r_categories)) { ?>
                                    <div class="pxp-posts-1-item-details-category"><?php echo esc_html($r_categories); ?></div>
                                <?php } ?>
                                <div class="pxp-posts-1-item-details-title"><?php echo esc_html($r_title); ?></div>
                                <div class="pxp-posts-1-item-details-date mt-2"><?php echo esc_html($r_date); ?></div>
                                <div class="pxp-posts-1-item-cta text-uppercase"><?php esc_html_e('Read Article', 'resideo'); ?></div>
                            </div>
                        </a>
                    </div>
                <?php }
            } else { ?>
                <div class="col-sm-12 col-md-6 col-lg-4"><?php esc_html_e('No related articles', 'resideo'); ?></div>
            <?php }
        } else { ?>
            <div class="col-sm-12 col-md-6 col-lg-4"><?php esc_html_e('No related articles', 'resideo'); ?></div>
        <?php }

        $post = $orig_post;
        wp_reset_postdata(); ?>
    </div>
</div>

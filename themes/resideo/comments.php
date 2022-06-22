<?php
/**
 * @package WordPress
 * @subpackage Resideo
 */

if (post_password_required()) {
    return;
}

global $current_user;
global $post;

$post_type = $post->post_type; ?>

<div class="mt-100">
    <div class="pxp-blog-comments-block">
        <div id="comments" class="comments-area pxp-blog-post-comments">
            <?php if (have_comments()) : 
                $comments_number = absint( get_comments_number() ); ?>
                <h4>
                    <?php if ($post_type == 'agent') {
                        if ($comments_number === 1) {
                            echo number_format_i18n(get_comments_number()) . ' ' . esc_html__('Review', 'resideo'); 
                        } else {
                            echo number_format_i18n(get_comments_number()) . ' ' . esc_html__('Reviews', 'resideo'); 
                        }
                    } else {
                        if ($comments_number === 1) {
                            echo number_format_i18n(get_comments_number()) . ' ' . esc_html__('Comment', 'resideo'); 
                        } else {
                            echo number_format_i18n(get_comments_number()) . ' ' . esc_html__('Comments', 'resideo'); 
                        }
                    } ?>
                </h4>

                <div class="mt-3 mt-md-4">
                    <ol class="comments-list">
                        <?php if ($post_type == 'agent') {
                            $callback = 'resideo_agent_review';
                        } else {
                            $callback = 'resideo_comment';
                        }

                        wp_list_comments(array(
                            'style'      => 'ol',
                            'callback'   => $callback,
                            'short_ping' => true,
                        )); ?>
                    </ol>
                </div>

                <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                    <nav id="comment-nav-below" class="navigation comment-navigation pagination mt-3 mt-md-4" role="navigation">
                        <div class="nav-previous">
                            <?php  if ($post_type == 'agent') {
                                previous_comments_link('<span class="fa fa-angle-left"></span> ' . esc_html__('Older Reviews', 'resideo')); 
                            } else {
                                previous_comments_link('<span class="fa fa-angle-left"></span> ' . esc_html__('Older Comments', 'resideo')); 
                            } ?>
                        </div>
                        <div class="nav-next">
                            <?php  if ($post_type == 'agent') {
                                next_comments_link(esc_html__('Newer Reviews', 'resideo') . ' <span class="fa fa-angle-right"></span>'); 
                            } else {
                                next_comments_link(esc_html__('Newer Comments', 'resideo') . ' <span class="fa fa-angle-right"></span>'); 
                            } ?>
                        </div>
                    </nav>
                <?php endif; ?>

                <?php if (!comments_open()) : ?>
                    <p class="no-comments">
                        <?php if ($post_type == 'agent') {
                            esc_html_e('Reviews are closed.', 'resideo');
                        } else {
                            esc_html_e('Comments are closed.', 'resideo'); 
                        } ?>
                    </p>
                <?php endif;
            endif;

            $commenter     = wp_get_current_commenter();
            $req           = get_option('require_name_email');
            $aria_req      = ($req ? " aria-required='true'" : '');
            $required_text = '  ';

            if ($post_type == 'agent') {
                $title_reply = esc_html__('Write a Review','resideo');
                $title_reply_to = esc_html__('Write a Review to %s', 'resideo');
                $cancel_reply_link = esc_html__('Cancel Review', 'resideo');
                $label_submit = esc_html__('Post Review', 'resideo');
                $comment_field = esc_html__('Your review helps others decide on the right agent for them. Please tell others why you recommend this agent.', 'resideo');
                $rating = '<div class="form-group">
                                <label class="d-block">' . esc_html__('Rate the agent', 'resideo') . '</label>
                                <span class="pxp-single-agent-rating"><span data-rating="5"></span><span data-rating="4"></span><span data-rating="3"></span><span data-rating="2"></span><span data-rating="1"></span></span>
                                <div class="clearfix"></div>
                                <input type="hidden" name="rate" id="rate" value="" />
                            </div>';
            } else {
                $title_reply = esc_html__('Leave a Reply', 'resideo');
                $title_reply_to = esc_html__('Leave a Reply to %s', 'resideo');
                $cancel_reply_link = esc_html__('Cancel Reply', 'resideo');
                $label_submit = esc_html__('Post Comment', 'resideo');
                $comment_field = esc_html__('Write your comment...', 'resideo');
                $rating = '';
            }

            $args = array(
                'id_form' => 'commentform',
                'class_form' => 'comment-form pxp-blog-post-comments-form mt-3 mt-md-4',
                'id_submit' => 'submit',
                'class_submit' => 'pxp-blog-post-comments-form-btn',
                'title_reply' => $title_reply,
                'title_reply_to' => $title_reply_to,
                'title_reply_before' => have_comments() ? '<h4 id="reply-title" class="comment-reply-title mt-4 mt-md-5">' : '<h4 id="reply-title" class="comment-reply-title">',
                'title_reply_after' => '</h4>',
                'cancel_reply_link' => $cancel_reply_link,
                'label_submit' => $label_submit,
                'comment_notes_before' => '<p class="comment-notes">' .
                                            esc_html__('Your email address will not be published. ', 'resideo') . ($req ? esc_html($required_text) : '') .
                                        '</p>',
                'comment_field' => $rating . 
                                    '<div class="form-group">
                                        <label for="comment">' . esc_html__('Comment', 'resideo') . '</label>
                                        <textarea id="comment" class="form-control" name="comment" rows="5" aria-required="true" placeholder="' . $comment_field . '"></textarea>
                                    </div>',
                'fields' => apply_filters(
                                'comment_form_default_fields', 
                                array(
                                    'author' => '
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="author">' . esc_html__('Name', 'resideo') . '</label>
                                                    <input id="author" name="author" type="text" class="form-control" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . '" />
                                                </div>
                                            </div>',
                                    'email' => '
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="email">' . esc_html__('Email', 'resideo') . '</label>
                                                    <input id="email" name="email" type="text" class="form-control"  value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . '" />
                                                </div>
                                            </div>
                                        </div>',
                                    'url' => '
                                        <div class="form-group">
                                            <label for="url">' . esc_html__('Website', 'resideo') . '</label>
                                            <input id="url" name="url" type="text" class="form-control"  value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />
                                        </div>'
                                )
                            )
            );

            if ($post_type == 'agent') {
                if (is_user_logged_in()) {
                    $user_review = get_comments(array('user_id' => $current_user->ID, 'post_id' => $post->ID));

                    if (!$user_review) {
                        comment_form($args);
                    }
                } else {
                    if (comments_open()) {
                        if (!have_comments()) {
                            print '<h4>' . esc_html__('Agent Reviews', 'resideo') . '</h4>';
                        }

                        print '<a href="javascript:void(0);" class="pxp-blog-post-comments-form-btn pxp-signin-trigger mt-3 mt-md-4" data-toggle="modal" data-target="#modal-signin">' . esc_html__('Sign In and Write a Review', 'resideo') . '</a>';
                    }
                }
            } else {
                comment_form($args);
            } ?>
        </div>
    </div>
</div>

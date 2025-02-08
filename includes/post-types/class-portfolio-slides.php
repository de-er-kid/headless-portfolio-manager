<?php

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Slides
{

    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
        add_filter('admin_post_thumbnail_html', array($this, 'set_featured_image_label'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_action('admin_head', array($this, 'add_admin_styles'));
    }


    public function add_admin_styles()
    {
        echo '<style>
                #slide_subtitle {
                    width: 100%;
                    // max-width: 600px;
                }
                @media (max-width: 600px) {
                    #slide_subtitle {
                        width: 100%;
                        max-width: 100%;
                    }
                }
            </style>';
    }

    public function register_post_type()
    {
        $labels = array(
            'name' => _x('Slides', 'post type general name', 'textdomain'),
            'singular_name' => _x('Slide', 'post type singular name', 'textdomain'),
            'menu_name' => _x('Slides', 'admin menu', 'textdomain'),
            'name_admin_bar' => _x('Slide', 'add new on admin bar', 'textdomain'),
            'add_new' => _x('Add New', 'slide', 'textdomain'),
            'add_new_item' => __('Add New Slide', 'textdomain'),
            'new_item' => __('New Slide', 'textdomain'),
            'edit_item' => __('Edit Slide', 'textdomain'),
            'view_item' => __('View Slide', 'textdomain'),
            'all_items' => __('All Slides', 'textdomain'),
            'search_items' => __('Search Slides', 'textdomain'),
            'parent_item_colon' => __('Parent Slides:', 'textdomain'),
            'not_found' => __('No slides found.', 'textdomain'),
            'not_found_in_trash' => __('No slides found in Trash.', 'textdomain'),
            'featured_image' => 'Background Image',
            'set_featured_image' => 'Set bg image',
            'remove_featured_image' => 'Remove bg image',
            'use_featured_image' => 'Use as bg image',
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => array('title', 'thumbnail'),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-slides',
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => 'testimonials'),
            'has_archive' => false,
            'menu_position' => null,
            'exclude_from_search' => true,
            'register_meta_box_cb' => array($this, 'add_custom_meta_box')
        );

        register_post_type('slide', $args);
    }

    public function add_custom_meta_box()
    {
        add_meta_box(
            'slide_subtitle',
            __('Slide Subtitle', 'textdomain'),
            array($this, 'render_meta_box_content'),
            'slide',
            'normal',
            'high'
        );
    }

    public function render_meta_box_content($post)
    {
        wp_nonce_field('save_slide_subtitle', 'slide_subtitle_nonce');
        $value = get_post_meta($post->ID, '_slide_subtitle', true);
        echo '<label for="slide_subtitle">';
        _e('Subtitle for this slide', 'textdomain');
        echo '</label> ';
        echo '<textarea id="slide_subtitle" name="slide_subtitle" rows="5" cols="30">' . esc_attr($value) . '</textarea>';
    }

    public function save_meta_box_data($post_id)
    {
        if (!isset($_POST['slide_subtitle_nonce'])) {
            return;
        }
        if (!wp_verify_nonce($_POST['slide_subtitle_nonce'], 'save_slide_subtitle')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['slide_subtitle'])) {
            $data = sanitize_text_field($_POST['slide_subtitle']);
            update_post_meta($post_id, '_slide_subtitle', $data);
        }
    }

    public function set_featured_image_label($content)
    {
        if (get_post_type() === 'slide') {
            return str_replace(__('Featured Image'), __('Slide Background Image'), $content);
        }
        return $content;
    }

}

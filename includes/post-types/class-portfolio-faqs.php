<?php

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_FAQs {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_faq_meta_box'));
        add_action('save_post', array($this, 'save_faq_meta'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => 'FAQs',
            'singular_name'      => 'FAQ',
            'menu_name'          => 'FAQs',
            'add_new'           => 'Add New',
            'add_new_item'      => 'Add New FAQ',
            'edit_item'         => 'Edit FAQ',
            'new_item'          => 'New FAQ',
            'view_item'         => 'View FAQ',
            'search_items'      => 'Search FAQs',
            'not_found'         => 'No FAQs found',
            'not_found_in_trash'=> 'No FAQs found in Trash',
        );

        $args = array(
            'labels'              => $labels,
            'public'              => 0,
            'has_archive'         => 0,
            // 'publicly_queryable'  => 0,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'supports'           => array('title'),
            'menu_icon'          => 'dashicons-editor-help',
        );

        register_post_type('faq', $args);
    }

    public function add_faq_meta_box() {
        add_meta_box(
            'faq_meta',
            'FAQ Answer',
            array($this, 'render_faq_meta_box'),
            'faq',
            'normal',
            'default'
        );
    }

    public function render_faq_meta_box($post) {
        $faq_answer = get_post_meta($post->ID, '_faq_answer', true);
        wp_nonce_field('faq_meta', 'faq_meta_nonce');
        ?>
        <label for="faq_answer">Answer</label>
        <textarea name="faq_answer" id="faq_answer" class="widefat" rows="5"><?php echo esc_html($faq_answer); ?></textarea>
        <?php
    }

    public function save_faq_meta($post_id) {
        if (!isset($_POST['faq_meta_nonce']) || !wp_verify_nonce($_POST['faq_meta_nonce'], 'faq_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['faq_answer'])) {
            update_post_meta($post_id, '_faq_answer', sanitize_text_field($_POST['faq_answer']));
        }
    }
}
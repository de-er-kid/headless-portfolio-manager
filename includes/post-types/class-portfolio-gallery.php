<?php
// includes/post-types/class-portfolio-gallery.php

class Portfolio_Gallery {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    public function register_post_type() {
        register_post_type('gallery', array(
            'labels' => array(
                'name' => 'Gallery',
                'singular_name' => 'Gallery Item',
                'add_new' => 'Add New Image',
                'add_new_item' => 'Add New Gallery Image',
                'edit_item' => 'Edit Gallery Image',
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'portfolio-manager',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => false,
            'menu_position' => 21,
        ));
    }

    public function add_meta_boxes() {
        add_meta_box(
            'gallery_details',
            'Gallery Details',
            array($this, 'render_meta_box'),
            'gallery',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        $description = get_post_meta($post->ID, '_gallery_description', true);
        wp_nonce_field('gallery_meta_box', 'gallery_meta_box_nonce');
        ?>
        <p>
            <label for="gallery_description">Description:</label>
            <textarea id="gallery_description" name="gallery_description" class="widefat" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['gallery_meta_box_nonce'])) return;
        if (!wp_verify_nonce($_POST['gallery_meta_box_nonce'], 'gallery_meta_box')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['gallery_description'])) {
            update_post_meta($post_id, '_gallery_description', sanitize_textarea_field($_POST['gallery_description']));
        }
    }
}
<?php
// includes/post-types/class-portfolio-testimonials.php

class Portfolio_Testimonials {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    public function register_post_type() {
        register_post_type('testimonials', array(
            'labels' => array(
                'name' => 'Testimonials',
                'singular_name' => 'Testimonial'
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'portfolio-manager',
            'supports' => array('title', 'thumbnail'),
            'labels' => array(
                'featured_image' => 'Customer Image',
                'set_featured_image' => 'Set customer image',
                'remove_featured_image' => 'Remove customer image',
                'use_featured_image' => 'Use as customer image',
            ),
            'show_in_rest' => true,
        ));
    }

    public function add_meta_boxes() {
        add_meta_box(
            'testimonial_details',
            'Testimonial Details',
            array($this, 'render_meta_box'),
            'testimonials',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        $role = get_post_meta($post->ID, '_testimonial_role', true);
        $review = get_post_meta($post->ID, '_testimonial_review', true);
        wp_nonce_field('testimonial_meta_box', 'testimonial_meta_box_nonce');
        ?>
        <p>
            <label for="testimonial_role">Role:</label>
            <input type="text" id="testimonial_role" name="testimonial_role" value="<?php echo esc_attr($role); ?>" class="widefat">
        </p>
        <p>
            <label for="testimonial_review">Review:</label>
            <textarea id="testimonial_review" name="testimonial_review" class="widefat" rows="5"><?php echo esc_textarea($review); ?></textarea>
        </p>
        <?php
    }

    public function save_meta_boxes($post_id) {
        if (!isset($_POST['testimonial_meta_box_nonce'])) return;
        if (!wp_verify_nonce($_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['testimonial_role'])) {
            update_post_meta($post_id, '_testimonial_role', sanitize_text_field($_POST['testimonial_role']));
        }
        if (isset($_POST['testimonial_review'])) {
            update_post_meta($post_id, '_testimonial_review', sanitize_textarea_field($_POST['testimonial_review']));
        }
    }
}

new Portfolio_Testimonials();
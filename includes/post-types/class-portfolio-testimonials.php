<?php
/**
 * Class Portfolio_Testimonials
 *
 * Registers a custom post type for testimonials and meta boxes.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Testimonials
{

    /**
     * Constructor: Hooks into WordPress actions.
     */
    public function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    /**
     * Registers the custom post type for Testimonials.
     */
    public function register_post_type()
    {
        $labels = array(
            'name' => __('Testimonials', 'headless-portfolio-manager'),
            'singular_name' => __('Testimonial', 'headless-portfolio-manager'),
            'add_new' => __('Add New', 'headless-portfolio-manager'),
            'add_new_item' => __('Add New Testimonial', 'headless-portfolio-manager'),
            'edit_item' => __('Edit Testimonial', 'headless-portfolio-manager'),
            'new_item' => __('New Testimonial', 'headless-portfolio-manager'),
            'view_item' => __('View Testimonial', 'headless-portfolio-manager'),
            'all_items' => __('Testimonials', 'headless-portfolio-manager'),
            'search_items' => __('Search Testimonials', 'headless-portfolio-manager'),
            'not_found' => __('No testimonials found.', 'headless-portfolio-manager'),
            'not_found_in_trash' => __('No testimonials found in Trash.', 'headless-portfolio-manager'),
            'featured_image' => 'Customer Image',
            'set_featured_image' => 'Set customer image',
            'remove_featured_image' => 'Remove customer image',
            'use_featured_image' => 'Use as customer image',
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => array('title', 'thumbnail'),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-format-quote',
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => 'testimonials'),
            'has_archive' => false,
            'menu_position' => 55,
            'exclude_from_search' => true,
        );

        register_post_type('testimonials', $args);
    }

    /**
     * Adds a meta box for testimonial details.
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'testimonial_details',
            __('Testimonial Details', 'headless-portfolio-manager'),
            array($this, 'render_meta_box'),
            'testimonials',
            'normal',
            'high'
        );
    }

    /**
     * Renders the testimonial meta box.
     *
     * @param WP_Post $post The current post object.
     */
    public function render_meta_box($post)
    {
        $role = get_post_meta($post->ID, '_testimonial_role', true);
        $review = get_post_meta($post->ID, '_testimonial_review', true);

        wp_nonce_field('testimonial_meta_box', 'testimonial_meta_box_nonce');

        ?>
        <p>
            <label for="testimonial_role"><?php esc_html_e('Role:', 'headless-portfolio-manager'); ?></label>
            <input type="text" id="testimonial_role" name="testimonial_role" value="<?php echo esc_attr($role); ?>"
                class="widefat">
        </p>
        <p>
            <label for="testimonial_review"><?php esc_html_e('Review:', 'headless-portfolio-manager'); ?></label>
            <textarea id="testimonial_review" name="testimonial_review" class="widefat"
                rows="5"><?php echo esc_textarea($review); ?></textarea>
        </p>
        <?php
    }

    /**
     * Saves the meta box data.
     *
     * @param int $post_id The post ID.
     */
    public function save_meta_boxes($post_id)
    {
        if (
            !isset($_POST['testimonial_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box')
        ) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['testimonial_role'])) {
            update_post_meta($post_id, '_testimonial_role', sanitize_text_field($_POST['testimonial_role']));
        }

        if (isset($_POST['testimonial_review'])) {
            update_post_meta($post_id, '_testimonial_review', sanitize_textarea_field($_POST['testimonial_review']));
        }
    }
}

new Portfolio_Testimonials();
?>
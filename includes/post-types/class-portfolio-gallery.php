<?php
/**
 * Portfolio Gallery Post Type
 */
if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Gallery {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_gallery_meta_box'));
        add_action('save_post', array($this, 'save_gallery_meta'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => 'Services',
            'singular_name'      => 'Service',
            'menu_name'          => 'Services',
            'add_new'           => 'Add New',
            'add_new_item'      => 'Add New Service',
            'edit_item'         => 'Edit Service',
            'new_item'          => 'New Service',
            'view_item'         => 'View Service',
            'search_items'      => 'Search Services',
            'not_found'         => 'No services found',
            'not_found_in_trash'=> 'No services found in Trash',
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'supports'           => array('title', 'thumbnail'),
            'menu_icon'          => 'dashicons-images-alt2',
        );

        register_post_type('service', $args);
    }

    public function add_gallery_meta_box() {
        add_meta_box(
            'service_gallery',
            'Service Gallery',
            array($this, 'render_gallery_meta_box'),
            'service',
            'normal',
            'high'
        );
    }

    public function render_gallery_meta_box($post) {
        wp_nonce_field('service_gallery_nonce', 'service_gallery_nonce');
        $gallery_ids = get_post_meta($post->ID, '_service_gallery', true);
        ?>
        <div class="service-gallery-wrapper">
            <input type="hidden" id="service_gallery_ids" name="service_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>">
            <button type="button" class="button" id="service_gallery_upload">Add Images</button>
            <div id="service_gallery_container">
                <?php
                if ($gallery_ids) {
                    $gallery_ids_array = explode(',', $gallery_ids);
                    foreach ($gallery_ids_array as $image_id) {
                        echo wp_get_attachment_image($image_id, 'thumbnail');
                    }
                }
                ?>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                var frame;
                $('#service_gallery_upload').on('click', function(e) {
                    e.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media({
                        title: 'Select Images for Gallery',
                        button: {
                            text: 'Add to gallery'
                        },
                        multiple: true
                    });
                    frame.on('select', function() {
                        var attachments = frame.state().get('selection').map(function(attachment) {
                            return attachment.id;
                        });
                        $('#service_gallery_ids').val(attachments.join(','));
                        $('#service_gallery_container').html('');
                        attachments.forEach(function(id) {
                            wp.media.attachment(id).fetch().then(function(data) {
                                $('#service_gallery_container').append('<img src="' + data.sizes.thumbnail.url + '" />');
                            });
                        });
                    });
                    frame.open();
                });
            });
        </script>
        <?php
    }

    public function save_gallery_meta($post_id) {
        if (!isset($_POST['service_gallery_nonce']) || 
            !wp_verify_nonce($_POST['service_gallery_nonce'], 'service_gallery_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['service_gallery_ids'])) {
            update_post_meta($post_id, '_service_gallery', sanitize_text_field($_POST['service_gallery_ids']));
        }
    }
}

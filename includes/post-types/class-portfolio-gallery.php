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
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts($hook) {
        global $post;
        if ($hook == 'post.php' || $hook == 'post-new.php') {
            if ('service' === $post->post_type) {
                wp_enqueue_script('jquery-ui-sortable');
            }
        }
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
        <style>
            .service-gallery-wrapper {
                margin: 20px 0;
            }
            #service_gallery_container {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-top: 15px;
                min-height: 60px;
                padding: 15px;
                background: #f9f9f9;
                border: 1px dashed #ccc;
            }
            .gallery-image-wrapper {
                position: relative;
                width: 150px;
                background: #fff;
                padding: 5px;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: move;
            }
            .gallery-image-wrapper img {
                width: 100%;
                height: 150px;
                object-fit: cover;
                display: block;
            }
            .remove-image {
                position: absolute;
                top: -10px;
                right: -10px;
                background: #dc3545;
                color: white;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                text-align: center;
                line-height: 24px;
                cursor: pointer;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            .remove-image:hover {
                background: #c82333;
            }
            .gallery-placeholder {
                border: 2px dashed #999;
                background: #f5f5f5;
                width: 150px;
                height: 150px;
            }
            #service_gallery_upload {
                background: #0073aa;
                color: white;
                border: none;
                padding: 8px 16px;
                cursor: pointer;
                border-radius: 4px;
            }
            #service_gallery_upload:hover {
                background: #006291;
            }
        </style>

        <div class="service-gallery-wrapper">
            <input type="hidden" id="service_gallery_ids" name="service_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>">
            <button type="button" class="button" id="service_gallery_upload">Add Images</button>
            <div id="service_gallery_container">
                <?php
                if ($gallery_ids) {
                    $gallery_ids_array = explode(',', $gallery_ids);
                    foreach ($gallery_ids_array as $image_id) {
                        echo '<div class="gallery-image-wrapper" data-id="' . esc_attr($image_id) . '">';
                        echo wp_get_attachment_image($image_id, 'thumbnail');
                        echo '<div class="remove-image" title="Remove image">×</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                var frame;
                
                // Make gallery sortable
                $('#service_gallery_container').sortable({
                    placeholder: 'gallery-placeholder',
                    tolerance: 'pointer',
                    update: function() {
                        updateGalleryOrder();
                    }
                });

                // Update hidden input with new order
                function updateGalleryOrder() {
                    var ids = [];
                    $('.gallery-image-wrapper').each(function() {
                        ids.push($(this).data('id'));
                    });
                    $('#service_gallery_ids').val(ids.join(','));
                }

                // Handle image removal
                $(document).on('click', '.remove-image', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).parent('.gallery-image-wrapper').remove();
                    updateGalleryOrder();
                });

                // Media upload handler
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
                        
                        // Add new images to container
                        attachments.forEach(function(id) {
                            wp.media.attachment(id).fetch().then(function(data) {
                                var wrapper = $('<div>', {
                                    class: 'gallery-image-wrapper',
                                    'data-id': id
                                });
                                wrapper.append($('<img>', {
                                    src: data.sizes.thumbnail.url
                                }));
                                wrapper.append($('<div>', {
                                    class: 'remove-image',
                                    title: 'Remove image',
                                    text: '×'
                                }));
                                $('#service_gallery_container').append(wrapper);
                                updateGalleryOrder();
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

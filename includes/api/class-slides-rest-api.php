<?php

if (!defined('ABSPATH')) {
    exit;
}
class Portfolio_Slides_REST_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints() {
        register_rest_route('portfolio/v1', '/slides', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_slides'),
            'permission_callback' => '__return_true'
        ));
    }

    public function get_slides() {
        $args = array(
            'post_type' => 'slide',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $posts = get_posts($args);
        $slides = array();

        foreach ($posts as $post) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            
            $slides[] = array(
                'image' => $image ? $image[0] : null,
                'title' => $post->post_title,
                'subtitle' => get_post_meta($post->ID, '__slide_subtitle', true),
            );
        }

        return new WP_REST_Response($slides, 200);
    }
}

new Portfolio_Slides_REST_API();


// next js variable:
// const slides = [
//     {
//       image:
//         "/gallery/listing/Concert-Festival/event (81).webp",
//       title: "Capturing Timeless Moments",
//       subtitle: "Professional photography services in Ontario",
//     },
//     {
//       image:
//         "/gallery/listing/Weddings/wedding (3).webp?height=1080&width=1920",
//       title: "Creating Lasting Memories",
//       subtitle: "Wedding, Portrait & Event Photography",
//     },
//     {
//       image: "/gallery/listing/Maternity/maternity (4).webp?height=1080&width=1920",
//       title: "Your Story, Beautifully Told",
//       subtitle: "Available throughout Canada",
//     },
//   ]
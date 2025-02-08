<?php

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Services_REST_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints() {
        register_rest_route('portfolio/v1', '/services', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_services'),
            'permission_callback' => '__return_true'
        ));
    }

    public function get_services() {
        $args = array(
            'post_type' => 'service',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $posts = get_posts($args);
        $services = array();

        foreach ($posts as $post) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            
            $services[] = array(
                'title' => $post->post_title,
                'image' => $image ? $image[0] : null,
            );
        }

        return new WP_REST_Response($services, 200);
    }
}

new Portfolio_Services_REST_API();

// next js variable:
// const services = [
//   { title: "Weddings", image: "/gallery/listing/Weddings/wedding (10).webp?height=600&width=800" },
//   { title: "Engagement", image: "/gallery/listing/Family/family (5).webp?height=600&width=800" },
//   { title: "Concert/Festival", image: "/gallery/listing/Concert-Festival/event (61).webp?height=600&width=800" },
//   { title: "Family", image: "/gallery/listing/Engagement/engagement (2).webp?height=600&width=800" },
//   { title: "Maternity", image: "/gallery/listing/Maternity/maternity (3).webp?height=600&width=800" },
//   { title: "Newborn", image: "/gallery/listing/Newborn/newborn (4).webp?height=600&width=800" },
// ]
<?php
// includes/api/class-rest-api.php

class Portfolio_REST_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints() {
        register_rest_route('portfolio/v1', '/testimonials', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_testimonials'),
            'permission_callback' => '__return_true'
        ));
    }

    public function get_testimonials() {
        $args = array(
            'post_type' => 'testimonials',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $posts = get_posts($args);
        $testimonials = array();

        foreach ($posts as $post) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            
            $testimonials[] = array(
                'name' => $post->post_title,
                'role' => get_post_meta($post->ID, '_testimonial_role', true),
                'image' => $image ? $image[0] : null,
                'text' => get_post_meta($post->ID, '_testimonial_review', true)
            );
        }

        return new WP_REST_Response($testimonials, 200);
    }
}

new Portfolio_REST_API();
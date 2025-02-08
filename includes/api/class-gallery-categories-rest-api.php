<?php

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Gallery_Category_REST_API
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints()
    {
        register_rest_route('portfolio/v1', '/gallery-categories', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_gallery_categories'),
            'permission_callback' => '__return_true'
        ));
    }

    public function get_gallery_categories()
    {
        $args = [
            'post_type' => 'service',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $query = new WP_Query($args);
        $post_titles = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $post_titles[] = get_the_title();
            }
            wp_reset_postdata();
        }

        // Ensure "All" is at the beginning
        array_unshift($post_titles, "All");

        return new WP_REST_Response($post_titles, 200);
    }
}

new Portfolio_Gallery_Category_REST_API();



// next js variable:
// All at first, then the rest of the Posts titles for servive post type structure below
// const categories: string[] = ["All", "Family", "Concert/Festival", "Weddings", "Engagement", "Maternity", "Newborn"]

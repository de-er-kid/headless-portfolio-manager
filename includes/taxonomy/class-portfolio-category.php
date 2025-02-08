<?php
// includes/taxonomy/class-portfolio-category.php

class Portfolio_Category {
    public function __construct() {
        add_action('init', array($this, 'register_taxonomy'));
    }

    public function register_taxonomy() {
        $labels = array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'menu_name' => 'Services',
            'all_items' => 'All Services',
            'edit_item' => 'Edit Service',
            'view_item' => 'View Service',
            'update_item' => 'Update Service',
            'add_new_item' => 'Add New Service',
            'new_item_name' => 'New Service Name',
            'search_items' => 'Search Services',
        );

        register_taxonomy('services', 'gallery', array(
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'service'),
            'show_in_rest' => true,
        ));
    }
}
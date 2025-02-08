<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Portfolio_Headless_Theme
{
    /**
     * Constructor: Hooks all necessary actions & filters.
     */
    public function __construct()
    {
        // Disable Full Site Editing (FSE) & Gutenberg
        add_action('after_setup_theme', [$this, 'disable_fse_and_gutenberg']);

        // Remove Pages & Other Unwanted Admin Menus
        add_action('admin_menu', [$this, 'remove_admin_menus'], 999);

        // Disable Pages Post Type
        add_filter('register_post_type_args', [$this, 'disable_pages_post_type'], 10, 2);

        // Hide Theme & Plugin Editor for Security
        add_action('init', [$this, 'disable_file_editing']);

        // Redirect frontend users to headless frontend (but keep admin access)
        add_action('template_redirect', [$this, 'redirect_frontend_users']);
    }

    /**
     * Disables Full Site Editing (FSE) & Gutenberg block editor.
     */
    public function disable_fse_and_gutenberg()
    {
        remove_theme_support('block-templates');
        remove_theme_support('block-template-parts');
        remove_theme_support('editor-styles');
        remove_theme_support('custom-line-height');
        remove_theme_support('custom-spacing');
        remove_theme_support('appearance-tools');
        remove_theme_support('align-wide');

        // Disable Gutenberg globally
        add_filter('use_block_editor_for_post', '__return_false', 10);

        // Disable block widgets
        add_filter('gutenberg_use_widgets_block_editor', '__return_false');
        add_filter('use_widgets_block_editor', '__return_false');

        // Remove block styles
        add_action('wp_enqueue_scripts', function () {
            wp_dequeue_style('global-styles');
            wp_dequeue_style('wp-block-library');
        }, 100);
    }

    /**
     * Removes "Pages", "Media", "Plugins", "Users" and other unwanted admin menu items.
     */
    public function remove_admin_menus()
    {
        // Remove the following admin menu items
        remove_menu_page('edit.php'); // Hide "Posts"
        remove_menu_page('edit-comments.php'); // Hide "Comments"
        remove_menu_page('themes.php'); // Hide "Appearance"
        remove_menu_page('tools.php'); // Hide "Tools"
        remove_menu_page('options-general.php'); // Hide "Settings"
        remove_menu_page('edit.php?post_type=page'); // Hide "Pages"
        remove_menu_page('upload.php'); // Hide "Media"
        remove_menu_page('plugins.php'); // Hide "Plugins"
        remove_menu_page('users.php'); // Hide "Users"
    }

    /**
     * Disables Pages post type from REST API & admin.
     */
    public function disable_pages_post_type($args, $post_type)
    {
        if ('page' === $post_type) {
            $args['show_in_rest'] = false;
            $args['public'] = false;
        }
        return $args;
    }

    /**
     * Disables the theme & plugin editor for security.
     */
    public function disable_file_editing()
    {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        if (!defined('DISALLOW_FILE_MODS')) {
            define('DISALLOW_FILE_MODS', true);
        }
    }

    /**
     * Redirects frontend users to headless frontend, but allows admin access.
     */
    public function redirect_frontend_users()
    {
        if (!is_admin() && !wp_doing_ajax() && !defined('REST_REQUEST') && strpos($_SERVER['REQUEST_URI'], '/wp-json/') === false) {
            wp_redirect(get_option('headless_frontend_url', 'http://localhost:3000'));
            exit;
        }
    }
}

new Portfolio_Headless_Theme();

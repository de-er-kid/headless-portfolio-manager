<?php
/**
 * Class Portfolio_Admin_Settings
 * Handles the admin settings for the portfolio plugin
 */
class Portfolio_Admin_Settings {
    private $options;
    private static $option_name = 'headless_frontend_url';

    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'init_settings'));
        add_action('admin_notices', array($this, 'show_settings_notice'));
    }

    /**
     * Add the settings page as a custom menu item
     */
    public function add_settings_page() {
        add_menu_page(
            'Settings',          // Page title
            'Settings',          // Menu title
            'manage_options',    // Capability required
            'portfolio-settings',// Menu slug
            array($this, 'render_settings_page'), // Callback function
            'dashicons-admin-generic', // Menu icon (cog)
            99                   // Menu position (bottom)
        );
    }

    /**
     * Show settings saved notice
     */
    public function show_settings_notice() {
        $screen = get_current_screen();
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] && 
            $screen && $screen->id === 'toplevel_page_portfolio-settings') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e('Settings saved successfully.', 'portfolio'); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Initialize settings
     */
    public function init_settings() {
        register_setting(
            'portfolio_settings_group', // Option group
            self::$option_name,         // Option name
            array($this, 'sanitize')    // Sanitize callback
        );

        add_settings_section(
            'portfolio_headless_section',     // ID
            'Headless Frontend Settings',     // Title
            array($this, 'section_info'),     // Callback
            'portfolio-settings'              // Page
        );

        add_settings_field(
            'headless_frontend_url',           // ID
            'Frontend URL',                    // Title
            array($this, 'url_field_callback'), // Callback
            'portfolio-settings',              // Page
            'portfolio_headless_section'       // Section
        );

        // Get the saved option
        $this->options = get_option(self::$option_name);
    }

    /**
     * Sanitize user input
     */
    public function sanitize($input) {
        if (is_array($input)) {
            $input = $input['headless_frontend_url'];
        }
        return esc_url_raw(trailingslashit($input));
    }

    /**
     * Section info callback
     */
    public function section_info() {
        echo 'Configure the URL for your headless frontend application.';
    }

    /**
     * URL field callback
     */
    public function url_field_callback() {
        $value = $this->options ? esc_attr($this->options) : '';
        
        printf(
            '<input type="url" id="headless_frontend_url" name="%s" value="%s" class="regular-text">',
            esc_attr(self::$option_name),
            $value
        );
        echo '<p class="description">Enter the full URL of your frontend application (e.g., https://my-frontend.com/)</p>';
    }

    /**
     * Render the settings page
     */
    public function render_settings_page() {
        require_once plugin_dir_path(__FILE__) . 'views/settings-page.php';
    }

    /**
     * Get the frontend URL
     */
    public static function get_frontend_url() {
        return esc_url(get_option(self::$option_name, ''));
    }
}

// Initialize the settings class
new Portfolio_Admin_Settings();
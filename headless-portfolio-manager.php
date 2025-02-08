<?php
/**
 * Plugin Name: Headless Portfolio Manager
 * Description: Manages portfolio, testimonials and API endpoints for headless setup
 * Version: 1.0
 * Author: Sinan
 * Author URI: https://github.com/de-er-kid
 * Text Domain: headless-portfolio-manager
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Tested up to: 5.8
 * Stable tag: 1.0
 * Tags: headless, portfolio, testimonials, api
 * 
 */

 defined('ABSPATH') || exit;

 class Headless_Portfolio_Manager {
     private static $instance = null;
     private $settings;
 
     public static function get_instance() {
         if (null === self::$instance) {
             self::$instance = new self();
         }
         return self::$instance;
     }
 
     private function __construct() {
         // Define constants
         define('HPM_PLUGIN_PATH', plugin_dir_path(__FILE__));
         define('HPM_PLUGIN_URL', plugin_dir_url(__FILE__));
         
         // Load files
         $this->load_dependencies();
         
         // Initialize components
         $this->init_components();
         
     }
 
     private function load_dependencies() {
         // Admin
         require_once HPM_PLUGIN_PATH . 'includes/admin/class-portfolio-admin-settings.php';
         
         // Post Types
         require_once HPM_PLUGIN_PATH . 'includes/post-types/class-portfolio-gallery.php';
        //  require_once HPM_PLUGIN_PATH . 'includes/post-types/class-portfolio-packages.php';
        //  require_once HPM_PLUGIN_PATH . 'includes/post-types/class-portfolio-slides.php';
         require_once HPM_PLUGIN_PATH . 'includes/post-types/class-portfolio-testimonials.php';
        //  require_once HPM_PLUGIN_PATH . 'includes/post-types/class-portfolio-faqs.php';
         
         // Taxonomies
         require_once HPM_PLUGIN_PATH . 'includes/taxonomy/class-portfolio-category.php';
        //  require_once HPM_PLUGIN_PATH . 'includes/taxonomy/class-portfolio-category-image-meta.php';
         
         // API
         require_once HPM_PLUGIN_PATH . 'includes/api/class-testimonials-rest-api.php';
         require_once HPM_PLUGIN_PATH . 'includes/api/class-services-rest-api.php';

        //  Theme
        require_once HPM_PLUGIN_PATH . 'includes/class-portfolio-headless-theme.php';
     }
 
     private function init_components() {
        //  $this->settings = new Portfolio_Admin_Settings();
         new Portfolio_Gallery();
         new Portfolio_Testimonials();
         new Portfolio_Category();
         new Portfolio_REST_API();
         //  new Portfolio_Slides();
         //  new Portfolio_FAQs();
         //  new Portfolio_Packages();
     }

 
    //  public function remove_frontend_features() {
    //     if (!is_admin() && !wp_doing_ajax() && !defined('REST_REQUEST') && strpos($_SERVER['REQUEST_URI'], '/wp-json/') === false) {
    //         wp_redirect(get_option('headless_frontend_url', 'http://localhost:3000'));
    //         exit;
    //     }
    // }
 }
 
 // Initialize plugin
 function headless_portfolio_manager() {
     return Headless_Portfolio_Manager::get_instance();
 }
 
 headless_portfolio_manager();
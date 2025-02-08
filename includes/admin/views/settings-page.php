<?php
/**
 * Admin settings page template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('portfolio_settings_group');
        do_settings_sections('portfolio-settings');
        submit_button();
        ?>
    </form>
</div>
<?php
/**
 * Plugin Name: Proseo AI Services Hub
 * Description: AI Services Hub foundation (admin dashboard, secure settings, REST endpoints).
 * Version: 0.1.0
 * Requires at least: 6.2
 * Requires PHP: 8.0
 * Author: Proseo
 * License: GPLv2 or later
 * Text Domain: proseo-ai-services-hub
 */

defined('ABSPATH') || exit;

define('PROSEO_AISH_VERSION', '0.1.0');
define('PROSEO_AISH_PLUGIN_FILE', __FILE__);
define('PROSEO_AISH_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PROSEO_AISH_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Bootstrap.php';

add_action('plugins_loaded', static function () {
    \Proseo\AIServiceHub\Bootstrap::init();
});

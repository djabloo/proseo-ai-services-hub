<?php
namespace Proseo\AIServiceHub\Admin;

use Proseo\AIServiceHub\Security\Capabilities;

defined('ABSPATH') || exit;

final class DashboardPage {

    public static function render(): void {
        if (!Capabilities::can_manage()) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'proseo-ai-services-hub'));
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('AI Services Hub', 'proseo-ai-services-hub') . '</h1>';

        echo '<p>' . esc_html__('Foundation dashboard. Next: settings, n8n webhook integration, payments scaffolding.', 'proseo-ai-services-hub') . '</p>';

        // Accessible status panel (minimal).
        echo '<section aria-labelledby="proseo-aish-status-title">';
        echo '<h2 id="proseo-aish-status-title">' . esc_html__('Status', 'proseo-ai-services-hub') . '</h2>';
        echo '<ul>';
        echo '<li>' . esc_html__('Plugin loaded: yes', 'proseo-ai-services-hub') . '</li>';
        echo '<li>' . esc_html__('REST routes: registered', 'proseo-ai-services-hub') . '</li>';
        echo '</ul>';
        echo '</section>';

        echo '</div>';
    }
}

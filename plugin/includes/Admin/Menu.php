<?php
namespace Proseo\AIServiceHub\Admin;

use Proseo\AIServiceHub\Security\Capabilities;

defined('ABSPATH') || exit;

final class Menu {

    public static function register(): void {
        add_action('admin_menu', [__CLASS__, 'add_menu']);
    }

    public static function add_menu(): void {
        add_menu_page(
            __('AI Services Hub', 'proseo-ai-services-hub'),
            __('AI Services Hub', 'proseo-ai-services-hub'),
            Capabilities::ADMIN_CAP,
            'proseo-ai-services-hub',
            [ElectricalEditorPage::class, 'render'],
            'dashicons-admin-generic',
            58
        );
    }
}

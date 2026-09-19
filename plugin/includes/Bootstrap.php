<?php
namespace Proseo\AIServiceHub;

defined('ABSPATH') || exit;

final class Bootstrap {

    public static function init(): void {
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Security/Capabilities.php';
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Admin/Menu.php';
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Admin/DashboardPage.php';
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Admin/ElectricalEditorPage.php';
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Electrical/ProjectRepository.php';
        require_once PROSEO_AISH_PLUGIN_DIR . 'includes/Rest/Routes.php';

        Admin\Menu::register();
        Admin\ElectricalEditorPage::register_assets();
        Rest\Routes::register();
    }
}

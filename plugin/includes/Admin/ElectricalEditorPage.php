<?php
namespace Proseo\AIServiceHub\Admin;

use Proseo\AIServiceHub\Electrical\ProjectRepository;
use Proseo\AIServiceHub\Security\Capabilities;

defined('ABSPATH') || exit;

final class ElectricalEditorPage {
    public static function register_assets(): void { add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_assets']); }

    /** @param string $hook_suffix Current WordPress admin page hook. */
    public static function enqueue_assets(string $hook_suffix): void {
        if ('toplevel_page_proseo-ai-services-hub' !== $hook_suffix) { return; }
        wp_enqueue_style('proseo-aish-electrical-editor', PROSEO_AISH_PLUGIN_URL . 'assets/css/electrical-editor.css', [], PROSEO_AISH_VERSION);
        wp_enqueue_script('proseo-aish-electrical-editor', PROSEO_AISH_PLUGIN_URL . 'assets/js/electrical-editor.js', [], PROSEO_AISH_VERSION, true);
        wp_localize_script('proseo-aish-electrical-editor', 'proseoElectricalEditor', [
            'project' => ProjectRepository::get_project(),
            'apiUrl' => esc_url_raw(rest_url('proseo-aish/v1/electrical-project')),
            'nonce' => wp_create_nonce('wp_rest'),
            'strings' => [
                'saved' => __('Progetto salvato.', 'proseo-ai-services-hub'),
                'saveFailed' => __('Impossibile salvare il progetto.', 'proseo-ai-services-hub'),
                'wireStart' => __('Seleziona il punto finale del conduttore.', 'proseo-ai-services-hub'),
            ],
        ]);
    }

    public static function render(): void {
        if (!Capabilities::can_manage()) { wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'proseo-ai-services-hub')); }
        ?>
        <div class="wrap proseo-electrical-editor">
            <h1><?php esc_html_e('Editor schemi elettrici', 'proseo-ai-services-hub'); ?></h1>
            <p class="description"><?php esc_html_e('MVP: aggiungi simboli, collegali e salva lo schema come bozza.', 'proseo-ai-services-hub'); ?></p>
            <div class="proseo-editor-toolbar" role="toolbar" aria-label="<?php esc_attr_e('Strumenti dello schema', 'proseo-ai-services-hub'); ?>">
                <label for="proseo-project-name"><?php esc_html_e('Nome progetto', 'proseo-ai-services-hub'); ?></label>
                <input id="proseo-project-name" type="text" maxlength="120" />
                <button class="button button-primary" id="proseo-save-project" type="button"><?php esc_html_e('Salva bozza', 'proseo-ai-services-hub'); ?></button>
                <span id="proseo-save-status" aria-live="polite"></span>
            </div>
            <div class="proseo-editor-layout">
                <aside class="proseo-symbol-library" aria-label="<?php esc_attr_e('Libreria simboli', 'proseo-ai-services-hub'); ?>">
                    <h2><?php esc_html_e('Libreria simboli', 'proseo-ai-services-hub'); ?></h2>
                    <button class="proseo-symbol-button" data-symbol-type="contactor" type="button">KM — <?php esc_html_e('Contattore', 'proseo-ai-services-hub'); ?></button>
                    <button class="proseo-symbol-button" data-symbol-type="terminal" type="button">X — <?php esc_html_e('Morsetto', 'proseo-ai-services-hub'); ?></button>
                    <button class="proseo-symbol-button" data-symbol-type="fuse" type="button">F — <?php esc_html_e('Fusibile', 'proseo-ai-services-hub'); ?></button>
                    <button class="proseo-symbol-button" data-symbol-type="lamp" type="button">H — <?php esc_html_e('Spia', 'proseo-ai-services-hub'); ?></button>
                    <hr />
                    <button class="button" id="proseo-wire-mode" type="button"><?php esc_html_e('Disegna conduttore', 'proseo-ai-services-hub'); ?></button>
                    <button class="button-link-delete" id="proseo-clear-project" type="button"><?php esc_html_e('Svuota schema', 'proseo-ai-services-hub'); ?></button>
                </aside>
                <main class="proseo-canvas-panel">
                    <p id="proseo-canvas-help" class="screen-reader-text"><?php esc_html_e('Clicca su un simbolo della libreria per aggiungerlo al foglio. Trascina i simboli per spostarli.', 'proseo-ai-services-hub'); ?></p>
                    <svg id="proseo-schematic-canvas" viewBox="0 0 1200 700" role="application" aria-describedby="proseo-canvas-help" tabindex="0"></svg>
                </main>
            </div>
        </div>
        <?php
    }
}

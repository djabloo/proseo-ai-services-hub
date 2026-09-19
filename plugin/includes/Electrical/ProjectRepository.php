<?php
namespace Proseo\AIServiceHub\Electrical;

defined('ABSPATH') || exit;

final class ProjectRepository {
    private const OPTION_NAME = 'proseo_aish_electrical_project_v1';

    /** @return array<string, mixed> */
    public static function get_project(): array {
        $project = get_option(self::OPTION_NAME, null);
        return is_array($project) ? $project : self::starter_project();
    }

    /**
     * @param array<string, mixed> $project Draft supplied by the editor.
     * @return array<string, mixed>
     */
    public static function save_project(array $project): array {
        $sanitized = self::sanitize_project($project);
        update_option(self::OPTION_NAME, $sanitized, false);
        return $sanitized;
    }

    /** @return array<string, mixed> */
    private static function starter_project(): array {
        return [
            'version' => 1,
            'name' => __('Nuovo schema elettrico', 'proseo-ai-services-hub'),
            'symbols' => [],
            'wires' => [],
        ];
    }

    /**
     * @param array<string, mixed> $project
     * @return array<string, mixed>
     */
    private static function sanitize_project(array $project): array {
        $symbols = [];
        $wires = [];
        if (isset($project['symbols']) && is_array($project['symbols'])) {
            foreach (array_slice($project['symbols'], 0, 250) as $symbol) {
                if (!is_array($symbol)) { continue; }
                $type = isset($symbol['type']) ? sanitize_key((string) $symbol['type']) : '';
                if (!in_array($type, ['contactor', 'terminal', 'fuse', 'lamp'], true)) { continue; }
                $symbols[] = [
                    'id' => isset($symbol['id']) ? sanitize_key((string) $symbol['id']) : wp_generate_uuid4(),
                    'type' => $type,
                    'label' => isset($symbol['label']) ? sanitize_text_field((string) $symbol['label']) : '',
                    'x' => isset($symbol['x']) ? max(0, min(1800, (int) $symbol['x'])) : 80,
                    'y' => isset($symbol['y']) ? max(0, min(1000, (int) $symbol['y'])) : 80,
                ];
            }
        }
        if (isset($project['wires']) && is_array($project['wires'])) {
            foreach (array_slice($project['wires'], 0, 500) as $wire) {
                if (!is_array($wire)) { continue; }
                $wires[] = [
                    'id' => isset($wire['id']) ? sanitize_key((string) $wire['id']) : wp_generate_uuid4(),
                    'x1' => isset($wire['x1']) ? max(0, min(1800, (int) $wire['x1'])) : 0,
                    'y1' => isset($wire['y1']) ? max(0, min(1000, (int) $wire['y1'])) : 0,
                    'x2' => isset($wire['x2']) ? max(0, min(1800, (int) $wire['x2'])) : 0,
                    'y2' => isset($wire['y2']) ? max(0, min(1000, (int) $wire['y2'])) : 0,
                ];
            }
        }
        return [
            'version' => 1,
            'name' => isset($project['name']) ? sanitize_text_field((string) $project['name']) : '',
            'symbols' => $symbols,
            'wires' => $wires,
        ];
    }
}

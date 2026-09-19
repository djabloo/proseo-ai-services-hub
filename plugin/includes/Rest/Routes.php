<?php
namespace Proseo\AIServiceHub\Rest;

use Proseo\AIServiceHub\Electrical\ProjectRepository;
use Proseo\AIServiceHub\Security\Capabilities;

defined('ABSPATH') || exit;

final class Routes {

    public static function register(): void {
        add_action('rest_api_init', [__CLASS__, 'routes']);
    }

    public static function routes(): void {
        register_rest_route(
            'proseo-aish/v1',
            '/health',
            [
                'methods'             => 'GET',
                'callback'            => [__CLASS__, 'health'],
                'permission_callback' => static function () {
                    return Capabilities::can_manage();
                },
            ]
        );
        register_rest_route(
            'proseo-aish/v1',
            '/electrical-project',
            [
                'methods' => ['GET', 'PUT'],
                'callback' => [__CLASS__, 'electrical_project'],
                'permission_callback' => static function () { return Capabilities::can_manage(); },
            ]
        );
    }

    public static function electrical_project(\WP_REST_Request $request): \WP_REST_Response {
        if ('PUT' === $request->get_method()) {
            $project = $request->get_json_params();
            if (!is_array($project)) {
                return new \WP_REST_Response(['message' => __('Invalid project payload.', 'proseo-ai-services-hub')], 400);
            }
            return new \WP_REST_Response(ProjectRepository::save_project($project), 200);
        }
        return new \WP_REST_Response(ProjectRepository::get_project(), 200);
    }

    public static function health(\WP_REST_Request $request): \WP_REST_Response {
        return new \WP_REST_Response(
            [
                'ok'      => true,
                'version' => defined('PROSEO_AISH_VERSION') ? PROSEO_AISH_VERSION : null,
            ],
            200
        );
    }
}

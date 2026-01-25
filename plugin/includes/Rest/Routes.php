<?php
namespace Proseo\AIServiceHub\Rest;

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

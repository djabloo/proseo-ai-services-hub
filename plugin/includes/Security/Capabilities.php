<?php
namespace Proseo\AIServiceHub\Security;

defined('ABSPATH') || exit;

final class Capabilities {

    /**
     * Capability baseline for admin access.
     * Later: introduce custom capability and assign on activation.
     */
    public const ADMIN_CAP = 'manage_options';

    public static function can_manage(): bool {
        return current_user_can(self::ADMIN_CAP);
    }
}

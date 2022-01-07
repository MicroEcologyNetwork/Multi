<?php

namespace MicroEcology\Multi\Auth;

use MicroEcology\Multi\Facades\Multi;
use MicroEcology\Multi\Middleware\Pjax;

class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isMultiistrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([self::class, 'check'], $permission);
            });

            return;
        }

        if (Multi::user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isMultiistrator()) {
            return true;
        }

        if (!Multi::user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Don't check permission.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isMultiistrator()) {
            return true;
        }

        if (Multi::user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    public static function error()
    {
        $response = response(Multi::content()->withError(trans('multi.deny')));

        if (!request()->pjax() && request()->ajax()) {
            abort(403, trans('multi.deny'));
        }

        Pjax::respond($response);
    }

    /**
     * If current user is multiistrator.
     *
     * @return mixed
     */
    public static function isMultiistrator()
    {
        return Multi::user()->isRole('multiistrator');
    }
}

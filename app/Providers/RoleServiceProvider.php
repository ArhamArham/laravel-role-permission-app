<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class RoleServiceProvider
{
    public static function define()
    {
        Gate::before(function ($user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });


        try {
            if (Schema::hasTable('permissions')) {
                foreach (self::getPermissions() as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasRole($permission->roles);
                    });
                }
            }
        } catch (\Exception $e) {

        }
    }


    public static function getPermissions()
    {
        try {
            return Cache::rememberForever('roles', function () {
                return Permission::with('roles')->get();
            });
        } catch (\Exception $e) {
            return [];
        }
    }
}

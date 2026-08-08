<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AutoPermission
{
    public function handle($request, Closure $next)
    {
        $name = $request->route()?->getName();

        if ($name) {
            try {
                $permission = Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]);

                $superAdmin = Role::where('name', 'super admin')->first();
                if ($superAdmin && !$superAdmin->hasPermissionTo($permission)) {
                    $superAdmin->givePermissionTo($permission);
                }
            } catch (\Exception $e) {
                // Fail silently in case of concurrent requests or DB issues
            }
        }

        return $next($request);
    }
}

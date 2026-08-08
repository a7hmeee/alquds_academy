<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()?->getName();
        $user = $request->user();

        if ($routeName && $user) {
            // Student accessing admin routes — block
            if ($user->hasRole('student') && !str_starts_with($routeName, 'student.')) {
                abort(403, 'صفحة غير مصرح بها');
            }

            // Admin/Teacher accessing student-specific routes — block
            if (str_starts_with($routeName, 'student.') && !$user->hasRole('student')) {
                abort(403, 'صفحة غير مصرح بها');
            }

            if ($user->hasRole('super admin') || $user->hasRole('admin')) {
                return $next($request);
            }

            if (! $user->can($routeName)) {
                // For teacher-specific routes, check via policy instead
                if (str_starts_with($routeName, 'submissions.') && $user->teacherProfile) {
                    return $next($request);
                }
                if (str_starts_with($routeName, 'circles.progress') && $user->teacherProfile) {
                    return $next($request);
                }
                abort(403, 'ليس لديك صلاحية الوصول');
            }
        }

        return $next($request);
    }
}

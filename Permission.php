<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {

      //  return $next($request);
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        //dd($permissions);
        foreach ($permissions as $permission) {
            if (app('auth')->user()->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        if (app('auth')->user()->hasRole('principal')) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}

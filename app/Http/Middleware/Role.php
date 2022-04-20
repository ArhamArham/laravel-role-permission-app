<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        foreach ($this->getPermissions() as $key => $value) {
            if ($request->routeIs($value)) {
                if (Gate::forUser(auth()->user())->allows($value)
                ) {
                    break;
                }
                abort(403);
            }
        }
        return $next($request);
    }

    public function getPermissions()
    {
        return [
            'Product Management'  => 'product.*',
            'Category Management' => 'category.*',
            'Brand Management'    => 'brand.*'
        ];
    }
}

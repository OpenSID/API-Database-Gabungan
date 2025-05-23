<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;

class EasyAuthorize
{
    /**
     * The gate instance.
     *
     * @var Gate
     */
    protected $gate;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $ability
     * @param array|null               ...$models
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next, $permission)
    {
        $mapPermission = [
            'index' => 'read',
            'store' => 'create',
            'create' => 'create',
            'show' => 'read',
            'destroy' => 'delete',
            'update' => 'update',
            'edit' => 'update',
        ];
        $route = $request->route()->getName();
        $tmp = explode('.', $route);
        $arrLength = count($tmp);
        $ability = $mapPermission[$tmp[$arrLength - 1]].'-'.$permission ?? $tmp[$arrLength - 1];
        $this->gate->authorize($ability);

        return $next($request);
    }
}

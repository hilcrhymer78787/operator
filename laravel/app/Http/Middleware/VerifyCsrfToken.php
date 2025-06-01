<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'line/*',
    ];

    public function handle($request, \Closure $next)
    {
        \Log::info('VerifyCsrfToken middleware called for URI: ' . $request->path());
        return parent::handle($request, $next);
    }
}

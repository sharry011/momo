<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */

    public function handle(Request $request, Closure $next): Response
    {
        $admin_link = Settings::find(1)->admin_link;
        $request->merge(['admin_link' => $admin_link]);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && !$request->is('admin*') && !$request->is('_debugbar*')) {
            $path = '/' . ltrim($request->path(), '/');

            $redirect = Cache::rememberForever("redirect_{$path}", function () use ($path) {
                return Redirect::where('old_url', $path)
                    ->where('is_active', true)
                    ->first();
            });

            if ($redirect) {
                $redirect->increment('hits');
                $target = $redirect->new_url;
                if (str_starts_with($target, 'http://') || str_starts_with($target, 'https://')) {
                    $host = parse_url($target, PHP_URL_HOST);
                    $allowedHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if ($host && $host !== $allowedHost) {
                        return $next($request);
                    }
                }
                return redirect($target, $redirect->status_code);
            }
        }

        return $next($request);
    }
}

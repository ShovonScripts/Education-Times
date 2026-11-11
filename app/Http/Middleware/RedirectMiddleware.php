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

            // Cache the whole active redirect map once (not one row per URL),
            // and invalidate it whenever redirects are created/updated/deleted.
            $redirects = Cache::remember('active_redirects', 3600, function () {
                return Redirect::where('is_active', true)
                    ->get(['id', 'old_url', 'new_url', 'status_code'])
                    ->keyBy('old_url')
                    ->map(fn (Redirect $r) => [
                        'id' => $r->id,
                        'new_url' => $r->new_url,
                        'status_code' => $r->status_code,
                    ])
                    ->all();
            });

            $redirect = $redirects[$path] ?? null;

            if ($redirect) {
                Redirect::where('id', $redirect['id'])->increment('hits');

                $target = $redirect['new_url'];
                if (str_starts_with($target, 'http://') || str_starts_with($target, 'https://')) {
                    $host = parse_url($target, PHP_URL_HOST);
                    $allowedHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if ($host && $host !== $allowedHost) {
                        return $next($request);
                    }
                }
                return redirect($target, $redirect['status_code']);
            }
        }

        return $next($request);
    }
}

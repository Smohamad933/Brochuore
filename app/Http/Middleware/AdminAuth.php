<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simple session guard for the concierge / admin review panel.
 * Credentials come from config('negah.admin') (env: NEGAH_ADMIN_*).
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('admin_logged_in')) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please sign in to access the admin dashboard.');
        }

        return $next($request);
    }
}

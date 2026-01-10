<?php

namespace Webkul\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Installer\Helpers\DatabaseManager;

class CanInstall
{
    /**
     * Handles Requests for Installer middleware.
     *
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {
        if (Str::contains($request->getPathInfo(), '/install')) {
            if ($this->isAlreadyInstalled()) {
                if (! $request->ajax()) {
                    return redirect()->route('shop.home.index');
                }

                return response()->json([
                    'message'=> trans('installer::app.installer.middleware.already-installed'),
                ], 403);
            }
        } else {
            if (! $this->isAlreadyInstalled()) {
                return redirect()->route('installer.index');
            }
        }

        return $next($request);
    }

    /**
     * Application Already Installed.
     *
     * @return bool
     */
    public function isAlreadyInstalled()
    {
        // Check environment variable first (for Railway/cloud deployment)
        if (env('BAGISTO_INSTALLED', false)) {
            return true;
        }

        // Check database directly (more reliable for cloud deployments)
        try {
            if (app(DatabaseManager::class)->isInstalled()) {
                Event::dispatch('bagisto.installed');
                return true;
            }
        } catch (\Exception $e) {
            // Database not ready or tables don't exist
            return false;
        }

        // Fallback to file check (for local development)
        if (file_exists(storage_path('installed'))) {
            return true;
        }

        return false;
    }
}

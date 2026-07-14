<?php

namespace App\Http\Middleware;

use App\Services\SiteVisitorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackSiteVisitors
{
    public function __construct(
        private readonly SiteVisitorService $siteVisitorService,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->siteVisitorService->track($request);
        } catch (Throwable) {
            // Keep visitor tracking best-effort so the storefront never breaks.
        }

        return $next($request);
    }
}

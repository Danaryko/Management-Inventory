<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Activity;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log if user is authenticated and request is successful
        if ($request->user() && $response->getStatusCode() < 400) {
            $this->logActivity($request);
        }

        return $response;
    }

    protected function logActivity(Request $request)
    {
        $method = $request->method();
        $path = $request->path();
        $action = $this->getActionFromRequest($request);

        if ($action) {
            Activity::create([
                'user_id' => $request->user()->id,
                'action' => $action,
                'description' => $this->getDescriptionFromRequest($request, $action),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    protected function getActionFromRequest(Request $request)
    {
        $method = $request->method();
        $path = $request->path();

        if ($method === 'POST' && str_contains($path, 'login')) {
            return 'login';
        }

        if ($method === 'POST' && str_contains($path, 'logout')) {
            return 'logout';
        }

        if ($method === 'POST' && !str_contains($path, '/edit')) {
            return 'create';
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            return 'update';
        }

        if ($method === 'DELETE') {
            return 'delete';
        }

        if ($method === 'GET' && str_contains($path, '/show')) {
            return 'view';
        }

        return null; // Don't log GET requests unless they're specific views
    }

    protected function getDescriptionFromRequest(Request $request, string $action)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        $resource = 'system';
        if (count($segments) > 0) {
            $resource = $segments[0];
        }

        return ucfirst($action) . ' ' . str_replace('-', ' ', $resource);
    }
}

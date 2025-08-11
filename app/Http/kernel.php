// Add RoleMiddleware to $routeMiddleware
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
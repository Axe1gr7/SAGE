protected $routeMiddleware = [
    // ...
    'admin.auth' => \App\Http\Middleware\AdminAuth::class,
];
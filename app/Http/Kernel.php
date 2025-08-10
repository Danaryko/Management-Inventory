<?php

// app/Http/Kernel.php
protected $middlewareAliases = [
    // ...
    'roles' => \App\Http\Middleware\RoleMiddleware::class,
];

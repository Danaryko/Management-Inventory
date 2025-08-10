<?php

// app/Http/Kernel.php
protected $middlewareAliases = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];

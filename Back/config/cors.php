<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
<<<<<<< HEAD
    'allowed_origins' => ['http://localhost:5173'], 
=======
    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173'], 
>>>>>>> 50f1347702246fae5a2ded8433859d2dd8f2f12c
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
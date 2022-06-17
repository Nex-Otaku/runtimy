Подключение модуля:
-------------------------

## 1. CORS

    config/cors.php

    'paths' => ['api/*', 'yookassa/*', 'sanctum/csrf-cookie'],

## 2. Подключаем роуты
   
```app/Providers/RouteServiceProvider.php```


    $this->routes(function () {
        ...

        Route::middleware('yookassa')
            ->prefix('yookassa')
            ->group(base_path('app/Module/Payment/Routes/yookassa.php'));

        ...
    });

## 3. Middleware

```app/Http/Kernel.php```

    protected $middlewareGroups = [
        'web' => [...],

        'api' => [...],

        'yookassa' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:yookassa',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

TODO 

Переделать на loadRoutesFrom, пример см. в Nova

<?php

namespace App\Modules\Auth;

use Closure;
use FastRoute\RouteCollector;

class AuthConfig
{

    /**
     * @return array<class-string, \DI\Definition\Helper\AutowireDefinitionHelper|\DI\Definition\Helper\CreateDefinitionHelper>
     */
    public static function di(): array
    {
        return [
        ];
    }

    /**
     * 
     * @return Closure(\FastRoute\RouteCollector): void
     */
    public static function router(): Closure
    {
        return function (RouteCollector $r): void {
            $r->addRoute('POST', '/api/auth/login', [ AuthController::class, 'login' ]);
        };
    }
}
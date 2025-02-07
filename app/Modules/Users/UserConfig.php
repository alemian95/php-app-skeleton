<?php

namespace App\Modules\Users;

use Closure;
use FastRoute\RouteCollector;

class UserConfig
{

    /**
     * @return array<class-string, \DI\Definition\Helper\AutowireDefinitionHelper|\DI\Definition\Helper\CreateDefinitionHelper>
     */
    public static function di(): array
    {
        return [
            \App\Modules\Users\UserRepository::class => \DI\autowire(\App\Modules\Users\UserRepository::class),
            \App\Modules\Users\UserController::class => \DI\autowire(\App\Modules\Users\UserController::class),
        ];
    }

    /**
     * 
     * @return Closure(\FastRoute\RouteCollector): void
     */
    public static function router(): Closure
    {
        return function (RouteCollector $r): void {
            $r->addRoute('GET', '/api/users', [ UserController::class, 'all' ]);
        };
    }
}
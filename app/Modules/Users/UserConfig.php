<?php

namespace App\Modules\Users;

class UserConfig
{

    public static function di(): array
    {
        return [
            \App\Modules\Users\UserRepository::class => \DI\autowire(\App\Modules\Users\UserRepository::class)
        ];
    }

    public static function router()
    {}
}
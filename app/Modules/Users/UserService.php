<?php

namespace App\Modules\Users;

/**
 * @method static array<\App\Entities\User> all()
 * 
 * 
 * @method static bool validateEmail(string $email)
 * 
 */
class UserService extends \Src\Components\Facade
{
    protected static function getFacadeAccessor(): string {
        return UserRepository::class;
    }
}
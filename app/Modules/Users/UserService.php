<?php

namespace App\Modules\Users;

use Src\Components\Facade;

/**
 * @method static array<User> all()
 */
class UserService extends Facade
{
    protected static function getFacadeAccessor(): string {
        return UserRepository::class;
    }
}
<?php

namespace App\Modules\Users;

use App\Entities\User;

/**
 * @method static array<User> all()
 * 
 * @method static void save(User $user)
 * @method static User|null findByEmail(string $email)
 * 
 */
class UserService extends \Src\Facades\Facade
{
    protected static function getFacadeAccessor(): string {
        return UserRepository::class;
    }
}
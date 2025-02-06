<?php

namespace App\Entities;

use App\Modules\Users\UserService;
use Doctrine\ORM\Mapping\Column;

#[\Doctrine\ORM\Mapping\Entity]
#[\Doctrine\ORM\Mapping\Table(name: 'users')]
class User
{

    #[\Doctrine\ORM\Mapping\Id]
    #[Column(name: 'id', type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    public int|null $id;

    #[Column(name: 'name', type: 'string', length: 255)]
    public $name;

    #[Column(name: 'email', type: 'string', length: 255, unique: true)]
    public $email;

    #[Column(name: 'password', type: 'string', length: 255, unique: true)]
    private $password;


    public function validateEmail(): bool
    {
        return UserService::validateEmail($this->email);
    }
}
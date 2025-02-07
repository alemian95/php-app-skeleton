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
    public string $name;

    #[Column(name: 'email', type: 'string', length: 255, unique: true)]
    public string $email;

    #[Column(name: 'password', type: 'string', length: 255, unique: true)]
    private string $password;


    public function validateEmail(): bool
    {
        return UserService::validateEmail($this->email);
    }

    public function setPassword(string $newPassword): void
    {
        $this->password = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
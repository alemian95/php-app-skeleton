<?php

namespace App\Entities;

use App\Modules\Users\UserService;
use Doctrine\ORM\Mapping\Column;
use Src\Entities\Entity;
use Src\Entities\Traits\HasId;
use Src\Entities\Traits\HasTimestamps;

#[\Doctrine\ORM\Mapping\Entity]
#[\Doctrine\ORM\Mapping\Table(name: 'users')]
class User extends Entity
{

    use HasId, HasTimestamps;

    #[Column(name: 'name', type: 'string', length: 255)]
    public string $name;

    #[Column(name: 'email', type: 'string', length: 255, unique: true)]
    public string $email;

    #[Column(name: 'password', type: 'string', length: 255)]
    private string $password;

    public function setPassword(string $newPassword): void
    {
        $this->password = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

}
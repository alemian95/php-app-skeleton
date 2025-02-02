<?php

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'users')]
class User
{

    #[Id]
    #[Column(name: 'id', type: 'integer')]
    #[GeneratedValue]
    private int|null $id;

    #[Column(name: 'name', type: 'string', length: 255)]
    private $name;

    #[Column(name: 'email', type: 'string', length: 255, unique: true)]
    private $email;

    #[Column(name: 'password', type: 'string', length: 255, unique: true)]
    private $password;
}
<?php

namespace App\Modules\Users;

/**
 * @extends \Src\Components\Repository<\App\Entities\User>
 */
class UserRepository extends \Src\Components\Repository
{

    /** @var class-string<\App\Entities\User> */
    private const ENTITY = \App\Entities\User::class;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, self::ENTITY);
    }

    public function validateEmail(string $email): bool
    {
        return true;
    }

}
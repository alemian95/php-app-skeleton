<?php

namespace App\Modules\Users;

use App\Entities\User;

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

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function validateEmail(string $email): bool
    {
        return true;
    }

}
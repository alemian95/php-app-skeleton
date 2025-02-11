<?php

namespace App\Modules\Users;

use App\Entities\User;

/**
 * @extends \Src\Entities\Repository<\App\Entities\User>
 */
class UserRepository extends \Src\Entities\Repository
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

    public function findByEmail(string $email): User|null
    {
        $results = $this->repository->findBy([ 'email' => $email ]);
        if (count($results)) {
            return $results[0];
        } else {
            return null;
        }
    }

}
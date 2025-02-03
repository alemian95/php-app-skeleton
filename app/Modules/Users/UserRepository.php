<?php

namespace App\Modules\Users;

class UserRepository extends \Src\Components\Repository
{

    private const ENTITY = \App\Entities\User::class;

    private \Doctrine\ORM\EntityRepository $repository;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $this->em->getRepository(self::ENTITY);
    }

    public function all()
    {
        return $this->repository->findAll();
    }

}
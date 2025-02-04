<?php

namespace Src\Components;

/**
 * @template T of object
 */
class Repository
{

    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected \Doctrine\ORM\EntityManagerInterface $em;

    /** @var \Doctrine\ORM\EntityRepository<T> */
    protected \Doctrine\ORM\EntityRepository $repository;

    /** @var class-string<T> */
    protected string $className;

    /**
     * 
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param class-string<T> $className
     * 
     */
    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager, string $className)
    {
        $this->em = $entityManager;
        $this->className = $className;
        $this->repository = $this->em->getRepository($this->className);
    }

    /**
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository<T>
     * 
     * @template T of object
     */
    public function getRepository(): \Doctrine\ORM\EntityRepository
    {
        return $this->repository;
    }

    /**
     * @return array<T>
     * 
     * @template T of object
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }
}
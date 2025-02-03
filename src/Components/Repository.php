<?php

namespace Src\Components;

class Repository
{

    public \Doctrine\ORM\EntityManagerInterface $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
}
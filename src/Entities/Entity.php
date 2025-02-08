<?php

namespace Src\Entities;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Src\Facades\EntityManager;

class Entity
{

    #[\Doctrine\ORM\Mapping\Id]
    #[Column(name: 'id', type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    public int|null $id;

    #[Column(name: 'created_at', type: 'datetime')]
    public DateTimeInterface $created_at;

    #[Column(name: 'updated_at', type: 'datetime')]
    public DateTimeInterface $updated_at;

    public function save(): void
    {

        if (empty($this->created_at)) {
            $this->created_at = new DateTime();
        }
        $this->updated_at = new DateTime();

        EntityManager::persist($this);
        EntityManager::flush();
    }
}
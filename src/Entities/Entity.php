<?php

namespace Src\Entities;

use Closure;
use Src\Facades\EntityManager;

class Entity
{

    /** @var array<Closure> */
    protected array $beforeSave;

    public function save(): void
    {

        foreach ($this->beforeSave as $beforeSaveHook) {
            $beforeSaveHook();
        }

        EntityManager::persist($this);
        EntityManager::flush();
    }
}
<?php

namespace Src\Entities;

use Closure;
use Src\Facades\EntityManager;

abstract class Entity
{

    /** @var array<Closure> */
    protected abstract function beforeSave(): array;

    public function save(): void
    {

        foreach ($this->beforeSave() as $beforeSaveHook) {
            $beforeSaveHook();
        }

        EntityManager::persist($this);
        EntityManager::flush();
    }
}
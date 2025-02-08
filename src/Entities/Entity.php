<?php

namespace Src\Entities;

use Src\Facades\EntityManager;

class Entity
{

    public function save(): void
    {

        foreach (class_uses($this) as $trait) {
            if (\Src\Entities\Traits\HasTimestamps::class === $trait && method_exists($this, 'updateTimestampsBeforeSave')) {
                $this->{'updateTimestampsBeforeSave'}();
            }
        }

        EntityManager::persist($this);
        EntityManager::flush();
    }
}
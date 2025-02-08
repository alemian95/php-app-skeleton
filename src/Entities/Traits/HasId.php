<?php

namespace Src\Entities\Traits;

use Doctrine\ORM\Mapping\Column;

trait HasId
{

    #[\Doctrine\ORM\Mapping\Id]
    #[Column(name: 'id', type: 'integer')]
    #[\Doctrine\ORM\Mapping\GeneratedValue]
    public int|null $id;

}
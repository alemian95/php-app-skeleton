<?php

namespace Src\Entities\Traits;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait HasTimestamps
{

    #[Column(name: 'created_at', type: 'datetime')]
    public DateTimeInterface $created_at;

    #[Column(name: 'updated_at', type: 'datetime')]
    public DateTimeInterface $updated_at;

    public function updateTimestampsBeforeSave()
    {
        if (empty($this->created_at)) {
            $this->created_at = new DateTime();
        }
        $this->updated_at = new DateTime();
    }

}
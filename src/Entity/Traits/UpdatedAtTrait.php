<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{
    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): DateTimeImmutable
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this->updatedAt;
    }

    public function getUpdatedAtAgo(): ?string
    {
        return $this->updatedAt ? Carbon::instance($this->updatedAt)->diffForHumans() : null;
    }
}

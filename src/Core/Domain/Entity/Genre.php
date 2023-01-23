<?php


namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodsMagicsTrait;
    public function __construct(
        protected Uuid|null $id = null,
        protected string $name,
        protected bool $isActive = true,
        protected DateTime|null $createdAt = null
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
    }
}

<?php


namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string    $name,
        protected ?Uuid     $id = null,
        protected bool      $isActive = true,
        protected array     $categoriesId = [],
        protected ?DateTime $createdAt = null
    )
    {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }


    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function update(string $name): void
    {
        $this->name = $name;

        $this->validate();
    }

    public function addCategory(string $categoryId): void
    {
        $this->categoriesId[] = $categoryId;
    }

    public function removeCategory(string $categoryId): void
    {
        $this->categoriesId = array_filter($this->categoriesId, function ($element) use ($categoryId) {
            return $element !== $categoryId;
        });
    }


    protected function validate(): void
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}

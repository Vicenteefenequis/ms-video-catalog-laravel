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

    protected array $categoriesId = [];

    public function __construct(
        protected string    $name,
        protected ?Uuid     $id = null,
        protected bool      $isActive = true,
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
        array_push($this->categoriesId,$categoryId);
    }


    protected function validate(): void
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}

<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video
{
    use MethodsMagicsTrait;

    protected array $categoriesId = [];
    protected array $genresId = [];

    protected array $castMembersId = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int    $yearLaunched,
        protected int    $duration,
        protected bool   $opened,
        protected Rating $rating,
        protected ?Uuid  $id = null,
        protected bool   $published = false,
        protected ?DateTime $createdAt = null,
    )
    {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();
    }

    public function addCategoryId(string $categoryId)
    {
        $this->categoriesId[] = $categoryId;
    }

    public function removeCategoryId(string $categoryId)
    {
        $this->categoriesId = array_filter($this->categoriesId, function ($search) use ($categoryId) {
            return $search != $categoryId;
        });
    }

    public function addGenreId(string $genreId)
    {
        $this->genresId[] = $genreId;
    }

    public function removeGenreId(string $genreId)
    {
        $this->genresId = array_filter($this->genresId, function ($search) use ($genreId) {
            return $search != $genreId;
        });
    }

    public function addCastMemberId(string $castMemberId)
    {
        $this->castMembersId[] = $castMemberId;
    }

    public function removeCastMemberId(string $castMemberId)
    {
        $this->castMembersId = array_filter($this->castMembersId, function ($search) use ($castMemberId) {
            return $search != $castMemberId;
        });
    }
}

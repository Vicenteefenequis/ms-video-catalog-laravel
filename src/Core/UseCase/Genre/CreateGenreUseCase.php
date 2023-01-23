<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\Create\{
    GenreCreateInputDto,
    GenreCreateOutputDto
};
use Core\UseCase\Interface\TransactionInterface;

class CreateGenreUseCase
{
    protected $repository;
    protected $transaction;

    protected $categoryRepository;

    public function __construct(GenreRepositoryInterface $repository, CategoryRepositoryInterface $categoryRepository, TransactionInterface $transaction)
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->transaction = $transaction;
    }

    public function execute(GenreCreateInputDto $input): GenreCreateOutputDto
    {

        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId,
            );

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->insert($genre);
            return new GenreCreateOutputDto(
                id: (string)$genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );

            $this->transaction->commit();
        } catch (\Throwable $th) {

            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        if(count($categoriesDb) !== count($categoriesId)) {
            throw new NotFoundException('Categories Not Found');
        }
    }
}

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

            $this->transaction->commit();

            return new GenreCreateOutputDto(
                id: (string)$genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );


        } catch (\Throwable $th) {

            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId,$categoriesDb);

        if(count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ',$arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}

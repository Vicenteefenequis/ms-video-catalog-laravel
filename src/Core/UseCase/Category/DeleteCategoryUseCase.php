<?php


namespace Core\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\DeleteCategory\{
    DeleteCategoryInputDto,
    DeleteCategoryOutputDto
};

class DeleteCategoryUseCase
{

    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(DeleteCategoryInputDto $input): DeleteCategoryOutputDto
    {
        $isDeleted = $this->repository->delete($input->id);

        return new DeleteCategoryOutputDto(success: $isDeleted);
    }
}

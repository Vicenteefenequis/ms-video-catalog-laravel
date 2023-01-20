<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\ListCategories\{
    CategoriesListInputDto,
    CategoriesListOutputDto
};


class ListCategoriesUseCase
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    public function execute(CategoriesListInputDto $input): CategoriesListOutputDto {
        $categories = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );

        return new CategoriesListOutputDto(
            items: $categories->items(),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from()
        );
    }
}
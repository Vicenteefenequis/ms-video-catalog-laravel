<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\ListCategoriesUseCase;
use Core\UseCase\DTO\Category\ListCategories\CategoriesListInputDto;
use Tests\TestCase;

class ListCategoriesUseCaseTest extends TestCase
{

    public function test_list_empty()
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new ListCategoriesUseCase($repository);
        $response = $useCase->execute(new CategoriesListInputDto());

        $this->assertCount(0,$response->items);

    }
}
